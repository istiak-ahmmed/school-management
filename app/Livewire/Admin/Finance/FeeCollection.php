<?php

namespace App\Livewire\Admin\Finance;

use App\Enums\InvoiceStatus;
use App\Enums\LedgerAccountType;
use App\Enums\LedgerEntryType;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Invoice;
use App\Models\Ledger;
use App\Models\Payment;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('admin.layouts.app')]
#[Title('বেতন সংগ্রহ')]
class FeeCollection extends Component
{
    // Step 1: Student Search
    public string $search = '';
    public ?Student $selectedStudent = null;
    public array $searchResults = [];

    // Step 2-3: Invoice Selection
    public array $pendingInvoices = [];
    public array $selectedInvoiceIds = [];

    // Step 4: Payment Form
    public ?float $amountReceived = null;
    public ?float $discountAmount = 0;
    public float $totalDueAmount  = 0;
    public int $paymentMethod = 0; // 0=cash default
    public string $transactionId = '';
    public string $paidByName = '';
    public string $paymentNote = '';

    // Step 6: Receipt
    public bool $showReceipt = false;
    public ?Payment $lastPayment = null;

    /**
     * Live-search students as user types.
     */
    public function updatedSearch(): void
    {
        $this->resetPaymentState();

        if (strlen($this->search) < 2) {
            $this->searchResults = [];
            return;
        }

        $this->searchResults = Student::where('status', 1)
            ->where(function ($q) {
                $q->whereHas('user', fn ($u) => $u->where('name', 'LIKE', "%{$this->search}%"))
                  ->orWhere('admission_no', 'LIKE', "%{$this->search}%");
            })
            ->with('user', 'schoolClass', 'section')
            ->limit(8)
            ->get()
            ->toArray();
    }

    /**
     * Select a student from search results.
     */
    public function selectStudent(int $studentId): void
    {
        $this->selectedStudent = Student::with(['user', 'schoolClass', 'section'])
            ->findOrFail($studentId);

        $this->search        = '';
        $this->searchResults = [];
        $this->loadPendingInvoices();
        $this->resetPaymentState();
    }

    /**
     * Load unpaid / partial invoices for selected student.
     */
    protected function loadPendingInvoices(): void
    {
        if (! $this->selectedStudent) {
            return;
        }

        $this->pendingInvoices = Invoice::where('student_id', $this->selectedStudent->id)
            ->whereIn('status', [InvoiceStatus::Unpaid->value, InvoiceStatus::Partial->value])
            ->with('feeType')
            ->orderBy('due_date')
            ->get()
            ->toArray();

        $this->selectedInvoiceIds = [];
        $this->amountReceived = null;
    }

    /**
     * Auto-calculate total amount when invoices are selected/deselected.
     */
    public function updatedSelectedInvoiceIds(): void
    {
        if (empty($this->selectedInvoiceIds)) {
            $this->amountReceived = null;
            $this->discountAmount = 0;
            $this->totalDueAmount = 0;
            return;
        }

        $this->recalculateAmountReceived();
    }

    public function updatedDiscountAmount(): void
    {
        if (!empty($this->selectedInvoiceIds)) {
            $this->recalculateAmountReceived();
        }
    }

    protected function recalculateAmountReceived(): void
    {
        $total = 0;
        foreach ($this->pendingInvoices as $invoice) {
            if (in_array($invoice['id'], $this->selectedInvoiceIds)) {
                // Calculate remaining for this specific invoice
                $paid = \App\Models\Payment::where('invoice_id', $invoice['id'])
                            ->where('payment_status', PaymentStatus::Success->value)
                            ->sum('amount_paid');
                $total += max(0, $invoice['net_amount'] - $paid);
            }
        }

        $this->totalDueAmount = $total;
        $discount = (float) ($this->discountAmount ?: 0);
        
        // Ensure discount doesn't exceed total due
        if ($discount > $total) {
            $this->discountAmount = $total;
            $discount = $total;
        }
        
        $this->amountReceived = $total > 0 ? max(0, $total - $discount) : null;
    }

    /**
     * Collect fee — saves payment, updates invoice status, writes ledger.
     */
    public function collectPayment(): void
    {
        $this->validate([
            'selectedInvoiceIds'  => 'required|array|min:1',
            'amountReceived'      => 'required|numeric|min:0',
            'discountAmount'      => 'nullable|numeric|min:0',
            'paymentMethod'       => 'required|integer|min:0|max:6',
            'transactionId'       => 'nullable|string|max:100',
        ], [
            'selectedInvoiceIds.required' => 'কমপক্ষে একটি ইনভয়েস নির্বাচন করুন',
            'amountReceived.required'     => 'পরিশোধিত পরিমাণ লিখুন',
            'amountReceived.min'          => 'পরিশোধিত পরিমাণ শূন্যের নিচে হতে পারে না',
        ]);

        $discount = $this->discountAmount ?: 0;
        
        // Ensure discount is not greater than the remaining amount
        $invoices        = Invoice::whereIn('id', $this->selectedInvoiceIds)->get();
        $totalDue        = $invoices->sum('net_amount');
        $alreadyPaid     = 0;

        foreach ($invoices as $inv) {
            $alreadyPaid += $inv->payments()->where('payment_status', PaymentStatus::Success->value)->sum('amount_paid');
        }

        $remaining = $totalDue - $alreadyPaid;

        if (($this->amountReceived + $discount) > $remaining) {
            $this->addError('amountReceived', "পরিশোধ এবং ছাড়ের যোগফল মোট বাকি পরিমাণ ৳" . number_format($remaining, 2) . " এর বেশি হতে পারবে না।");
            return;
        }

        DB::transaction(function () use ($invoices, $remaining, $discount) {
            $year      = Carbon::now()->year;
            
            // Distribute amount + discount across selected invoices
            $remainingAmount   = $this->amountReceived;
            $remainingDiscount = $discount;
            $createdPayments   = [];

            foreach ($invoices as $invoice) {
                if ($remainingAmount <= 0 && $remainingDiscount <= 0) break;

                $invoicePaid = $invoice->payments()
                    ->where('payment_status', PaymentStatus::Success->value)
                    ->sum('amount_paid');

                $invoiceDue    = $invoice->net_amount - $invoicePaid;
                
                // First distribute discount
                $discountForThis = min($remainingDiscount, $invoiceDue);
                $remainingDiscount -= $discountForThis;
                $invoiceDue      -= $discountForThis;
                
                // Then distribute actual paid amount
                $amountForThis = min($remainingAmount, $invoiceDue);
                $remainingAmount -= $amountForThis;
                
                // If this invoice gets a discount, update the invoice's total discount
                if ($discountForThis > 0) {
                    $invoice->update([
                        'discount'   => $invoice->discount + $discountForThis,
                        'net_amount' => $invoice->amount - ($invoice->discount + $discountForThis) + $invoice->fine
                    ]);
                }
                
                if ($amountForThis <= 0 && $discountForThis <= 0) continue;
                
                // We only create a payment record if there is an actual amount paid > 0
                if ($amountForThis > 0) {
                    $lastPayId = Payment::max('id') ?? 0;
                $lastPayId++;
                $paymentNo = 'PAY-' . $year . '-' . str_pad($lastPayId, 6, '0', STR_PAD_LEFT);

                $payment = Payment::create([
                    'payment_no'       => $paymentNo,
                    'invoice_id'       => $invoice->id,
                    'student_id'       => $this->selectedStudent->id,
                    'amount_paid'      => $amountForThis,
                    'payment_method_id'=> $this->paymentMethod,
                    'transaction_id'   => $this->transactionId ?: null,
                    'payment_status'   => PaymentStatus::Success,
                    'paid_by'          => $this->paidByName ?: null,
                    'paid_at'          => Carbon::now(),
                    'collected_by'     => auth()->id(),
                    'receipt_generated'=> 0,
                    'note'             => $this->paymentNote ?: null,
                ]);
                
                $createdPayments[] = $payment;
                }

                // Determine new status
                $totalPaidNow = $invoicePaid + $amountForThis;
                $currentNetAmount = $invoice->fresh()->net_amount;
                
                if ($totalPaidNow >= $currentNetAmount) {
                    $invoice->update(['status' => InvoiceStatus::Paid]);
                } else {
                    $invoice->update(['status' => InvoiceStatus::Partial]);
                }
            }

            // Write ledger entry (income / credit) only if actual amount received > 0
            if ($this->amountReceived > 0 && count($createdPayments) > 0) {
                Ledger::create([
                    'date'           => Carbon::today(),
                    'account_head'   => 'বেতন আয়',
                'account_type'   => LedgerAccountType::Income,
                'amount'         => $this->amountReceived,
                'entry_type'     => LedgerEntryType::Credit,
                'reference_type' => 'payment',
                'reference_id'   => $createdPayments[0]->id ?? 0,
                    'description'    => "শিক্ষার্থী: {$this->selectedStudent->user->name} | রসিদ: " . collect($createdPayments)->pluck('payment_no')->join(', '),
                    'created_by'     => auth()->id(),
                ]);
            }

            // For receipt, we just take the last payment (if multiple, they can print from history, but here we show the primary one)
            if (count($createdPayments) > 0) {
                $this->lastPayment = $createdPayments[0]->load('invoice.feeType', 'student.user');
            } else {
                $this->lastPayment = null;
            }
        });

        $this->showReceipt = true;
        $this->loadPendingInvoices(); // Refresh invoice list
        $this->resetPaymentState(keepStudent: true);

        session()->flash('success', 'পেমেন্ট সফলভাবে সংরক্ষণ হয়েছে!');
    }

    public function clearStudent(): void
    {
        $this->selectedStudent  = null;
        $this->pendingInvoices  = [];
        $this->searchResults    = [];
        $this->search           = '';
        $this->resetPaymentState();
    }

    public function closeReceipt(): void
    {
        $this->showReceipt = false;
        $this->lastPayment = null;
    }

    protected function resetPaymentState(bool $keepStudent = false): void
    {
        $this->selectedInvoiceIds = [];
        $this->amountReceived     = null;
        $this->discountAmount     = 0;
        $this->totalDueAmount     = 0;
        $this->paymentMethod      = 0;
        $this->transactionId      = '';
        $this->paidByName         = '';
        $this->paymentNote        = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.finance.fee-collection', [
            'paymentMethods' => \App\Models\PaymentMethod::where('status', 1)->get(),
        ]);
    }
}
