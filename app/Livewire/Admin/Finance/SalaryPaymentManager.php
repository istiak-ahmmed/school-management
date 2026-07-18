<?php

namespace App\Livewire\Admin\Finance;

use App\Enums\AdvanceStatus;
use App\Enums\EmployeeType;
use App\Enums\LedgerAccountType;
use App\Enums\LedgerEntryType;
use App\Enums\SalaryPaymentMethod;
use App\Enums\SalaryStatus;
use App\Models\Ledger;
use App\Models\SalaryAdvance;
use App\Models\SalaryPayment;
use App\Models\SalaryStructure;
use App\Models\Staff;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Livewire\Traits\Sortable;

#[Layout('admin.layouts.app')]
#[Title('বেতন পরিশোধ')]
class SalaryPaymentManager extends Component
{
    use Sortable;

    public string $monthFilter;
    public string $employeeTypeFilter = '';

    // Modal state
    public bool $showPaymentModal = false;
    public ?array $selectedEmployee = null;

    // Payment form
    public ?float $amountToPay = null;
    public string $paymentMethod = ''; // Empty by default
    public string $transactionId = '';
    public string $note = '';
    public bool $deductAdvance = false;
    public float $advanceToDeduct = 0;

    public ?SalaryPayment $lastPayment = null;
    public bool $showSlip = false;

    public function mount(): void
    {
        $this->monthFilter = Carbon::now()->format('Y-m');
    }

    /**
     * Load unified list of teachers and staff with their salary info for the selected month.
     */
    public function getEmployeesProperty(): array
    {
        $month = $this->monthFilter;
        $employees = [];

        // 1. Fetch Teachers
        if ($this->employeeTypeFilter === '' || $this->employeeTypeFilter === (string) EmployeeType::Teacher->value) {
            $teachers = Teacher::with('user')->where('status', 1)->orderBy($this->sortField, $this->sortDirection)->get();
            foreach ($teachers as $teacher) {
                $employees[] = $this->formatEmployeeData($teacher, EmployeeType::Teacher, $month);
            }
        }

        // 2. Fetch Staff
        if ($this->employeeTypeFilter === '' || $this->employeeTypeFilter === (string) EmployeeType::Staff->value) {
            $staffs = Staff::with('user')->where('status', 1)->orderBy($this->sortField, $this->sortDirection)->get();
            foreach ($staffs as $staff) {
                $employees[] = $this->formatEmployeeData($staff, EmployeeType::Staff, $month);
            }
        }

        return $employees;
    }

    protected function formatEmployeeData($model, EmployeeType $type, string $month): array
    {
        // Get structure
        $structure = SalaryStructure::where('employee_type', $type->value)
            ->where('employee_id', $model->id)
            ->where('effective_from', '<=', Carbon::parse($month)->endOfMonth())
            ->orderByDesc('effective_from')
            ->first();

        // If no structure, fallback to model basic_salary
        $basic = $structure ? $structure->basic_salary : $model->basic_salary;
        $gross = $structure ? $structure->gross_salary : $basic;
        $net   = $structure ? $structure->net_salary : $basic;

        // Check if already paid
        $payment = SalaryPayment::where('employee_type', $type->value)
            ->where('employee_id', $model->id)
            ->where('month_year', $month)
            ->where('status', SalaryStatus::Paid->value)
            ->first();

        $status = $payment ? SalaryStatus::Paid : SalaryStatus::Pending;

        // Pending advances
        $advances = SalaryAdvance::where('employee_type', $type->value)
            ->where('employee_id', $model->id)
            ->whereIn('status', [AdvanceStatus::Approved->value])
            ->get();

        $pendingAdvance = $advances->sum('remaining');

        return [
            'id'             => $model->id,
            'type'           => $type,
            'name'           => $model->user->name ?? 'N/A',
            'designation'    => $model->designation ?? '—',
            'basic_salary'   => $basic,
            'gross_salary'   => $gross,
            'net_salary'     => $net,
            'pending_advance'=> $pendingAdvance,
            'status'         => $status,
            'payment'        => $payment,
            'structure'      => $structure,
        ];
    }

    public function openPaymentModal($employeeId, $typeValue)
    {
        $employees = collect($this->employees);
        $this->selectedEmployee = $employees->first(function ($emp) use ($employeeId, $typeValue) {
            return $emp['id'] == $employeeId && $emp['type']->value == $typeValue;
        });

        if (! $this->selectedEmployee) return;

        $this->amountToPay     = $this->selectedEmployee['net_salary'];
        $this->paymentMethod   = '';
        $this->transactionId   = '';
        $this->note            = '';
        $this->deductAdvance   = false;
        $this->advanceToDeduct = 0;
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->selectedEmployee = null;
        $this->resetErrorBag();
    }

    public function updatedDeductAdvance($value)
    {
        if ($value && $this->selectedEmployee['pending_advance'] > 0) {
            $this->advanceToDeduct = min($this->selectedEmployee['net_salary'], $this->selectedEmployee['pending_advance']);
            $this->amountToPay = $this->selectedEmployee['net_salary'] - $this->advanceToDeduct;
        } else {
            $this->advanceToDeduct = 0;
            $this->amountToPay = $this->selectedEmployee['net_salary'];
        }
    }

    public function updatedAdvanceToDeduct($value)
    {
        $value = (float) $value;
        if ($value > $this->selectedEmployee['pending_advance']) {
            $this->advanceToDeduct = $this->selectedEmployee['pending_advance'];
        }
        if ($value > $this->selectedEmployee['net_salary']) {
            $this->advanceToDeduct = $this->selectedEmployee['net_salary'];
        }
        $this->amountToPay = max(0, $this->selectedEmployee['net_salary'] - $this->advanceToDeduct);
    }

    public function processPayment()
    {
        $this->validate([
            'amountToPay'     => 'required|numeric|min:0',
            'paymentMethod'   => 'required|exists:payment_methods,id',
            'advanceToDeduct' => 'nullable|numeric|min:0',
        ]);

        if (! $this->selectedEmployee) return;

        DB::transaction(function () {
            $year   = Carbon::now()->year;
            $lastId = SalaryPayment::max('id') ?? 0;
            $voucher= 'SAL-' . $year . '-' . str_pad($lastId + 1, 6, '0', STR_PAD_LEFT);

            $structure = $this->selectedEmployee['structure'];

            // 1. Create Salary Payment
            $payment = SalaryPayment::create([
                'voucher_no'       => $voucher,
                'employee_type'    => $this->selectedEmployee['type']->value,
                'employee_id'      => $this->selectedEmployee['id'],
                'month_year'       => $this->monthFilter,
                'basic_salary'     => $this->selectedEmployee['basic_salary'],
                'total_allowance'  => $structure ? ($structure->house_rent + $structure->medical_allowance + $structure->transport_allowance + $structure->other_allowance) : 0,
                'total_deduction'  => $structure ? ($structure->deduction_provident + $structure->deduction_tax) : 0,
                'gross_salary'     => $this->selectedEmployee['gross_salary'],
                'net_salary'       => $this->amountToPay, // Actual amount transferred
                'advance_deducted' => $this->advanceToDeduct,
                'payment_method_id'=> $this->paymentMethod,
                'transaction_id'   => $this->transactionId ?: null,
                'paid_at'          => Carbon::now(),
                'paid_by'          => auth()->id(),
                'status'           => SalaryStatus::Paid,
                'note'             => $this->note ?: null,
            ]);

            // 2. Handle Advance Deduction
            if ($this->advanceToDeduct > 0) {
                $remainingToDeduct = $this->advanceToDeduct;

                $advances = SalaryAdvance::where('employee_type', $this->selectedEmployee['type']->value)
                    ->where('employee_id', $this->selectedEmployee['id'])
                    ->where('status', AdvanceStatus::Approved->value)
                    ->orderBy('approved_at')
                    ->get();

                foreach ($advances as $advance) {
                    if ($remainingToDeduct <= 0) break;

                    $advRemaining = $advance->remaining;
                    $deduct = min($remainingToDeduct, $advRemaining);

                    $advance->recovered_amount += $deduct;
                    if ($advance->recovered_amount >= $advance->amount) {
                        $advance->status = AdvanceStatus::FullyRecovered;
                    }
                    $advance->save();

                    $remainingToDeduct -= $deduct;
                }
            }

            // 3. Create Ledger Entry (Expense)
            Ledger::create([
                'date'           => Carbon::today(),
                'account_head'   => 'বেতন ব্যয়',
                'account_type'   => LedgerAccountType::Expense,
                'amount'         => $this->amountToPay,
                'entry_type'     => LedgerEntryType::Debit,
                'reference_type' => 'salary_payment',
                'reference_id'   => $payment->id,
                'description'    => "কর্মচারী/শিক্ষক: {$this->selectedEmployee['name']} | মাস: {$this->monthFilter} | ভাউচার: {$voucher}",
                'created_by'     => auth()->id(),
            ]);

            $this->lastPayment = $payment;
        });

        $this->closePaymentModal();
        $this->showSlip = true;
        session()->flash('success', 'বেতন সফলভাবে পরিশোধ করা হয়েছে!');
    }

    public function closeSlip()
    {
        $this->showSlip = false;
        $this->lastPayment = null;
    }

    public function render()
    {
        return view('livewire.admin.finance.salary-payment-manager', [
            'paymentMethods' => \App\Models\PaymentMethod::where('status', 1)->get(),
        ]);
    }
}
