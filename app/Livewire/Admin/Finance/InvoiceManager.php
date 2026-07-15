<?php

namespace App\Livewire\Admin\Finance;

use App\Enums\InvoiceStatus;
use App\Models\AcademicYear;
use App\Models\FeeStructure;
use App\Models\FeeType;
use App\Models\Invoice;
use App\Models\SchoolClass;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Livewire\Traits\Sortable;
use Livewire\WithPagination;

#[Layout('admin.layouts.app')]
#[Title('ইনভয়েস ব্যবস্থাপনা')]
class InvoiceManager extends Component
{
    use Sortable;

    use WithPagination;

    public string $search       = '';
    public string $monthFilter  = '';
    public string $statusFilter = '';
    public bool $generating     = false;
    public ?string $generateMessage = null;
    public bool $generateSuccess    = false;

    // Custom Invoice Properties
    public bool $showCustomModal    = false;
    public $customFeeTypeId         = '';
    public array $customClassIds    = [];
    public $customDueDate           = '';

    protected $queryString = [
        'search'       => ['except' => ''],
        'monthFilter'  => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->monthFilter   = Carbon::now()->format('Y-m');
        $this->customDueDate = Carbon::now()->addDays(10)->format('Y-m-d');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Trigger the monthly invoice generation command.
     */
    public function generateMonthlyInvoices(): void
    {
        $this->generating      = true;
        $this->generateMessage = null;

        try {
            $month = $this->monthFilter ?: Carbon::now()->format('Y-m');
            Artisan::call('fees:generate-monthly', ['--month' => $month]);
            $output = Artisan::output();

            $this->generateSuccess = true;
            // Extract the last relevant line from command output
            $lines = array_filter(explode("\n", $output));
            $this->generateMessage = '✅ ' . ($lines ? end($lines) : "{$month} মাসের ইনভয়েস তৈরি সম্পন্ন হয়েছে।");
        } catch (\Exception $e) {
            $this->generateSuccess = false;
            $this->generateMessage = '❌ ত্রুটি: ' . $e->getMessage();
        } finally {
            $this->generating = false;
        }
    }

    public function openCustomModal(): void
    {
        $this->reset(['customFeeTypeId', 'customClassIds']);
        $this->customDueDate = Carbon::now()->addDays(10)->format('Y-m-d');
        $this->resetValidation();
        $this->showCustomModal = true;
    }

    public function closeCustomModal(): void
    {
        $this->showCustomModal = false;
    }

    public function saveCustomInvoice(): void
    {
        $this->validate([
            'customFeeTypeId' => 'required',
            'customClassIds'  => 'required|array|min:1',
            'customDueDate'   => 'required|date',
        ], [
            'customFeeTypeId.required' => 'ফি এর ধরন নির্বাচন করুন।',
            'customClassIds.required'  => 'কমপক্ষে একটি ক্লাস নির্বাচন করুন।',
            'customDueDate.required'   => 'বকেয়া তারিখ নির্বাচন করুন।',
        ]);

        $academicYear = AcademicYear::where('is_current', 1)->first();
        if (!$academicYear) {
            $this->addError('customFeeTypeId', 'কোনো সক্রিয় শিক্ষাবর্ষ পাওয়া যায়নি।');
            return;
        }

        $feeType = FeeType::find($this->customFeeTypeId);
        
        // Month year for one-time fees can be set to current month just for tracking, or null
        $monthYear = Carbon::now()->format('Y-m');
        $dueDate   = Carbon::parse($this->customDueDate);
        
        $created = 0;
        $skipped = 0;
        
        $year   = Carbon::now()->year;
        
        // Get all students for selected classes
        $students = Student::where('status', 1)
            ->whereIn('class_id', $this->customClassIds)
            ->select('id', 'class_id')
            ->get();
            
        if ($students->isEmpty()) {
            $this->addError('customClassIds', 'নির্বাচিত ক্লাসে কোনো সক্রিয় শিক্ষার্থী নেই।');
            return;
        }
        
        // Get structures
        $feeStructures = FeeStructure::where('academic_year_id', $academicYear->id)
            ->where('fee_type_id', $feeType->id)
            ->get()
            ->keyBy('class_id'); // null class_id will be keyed as ''

        DB::transaction(function () use ($students, $feeType, $feeStructures, $academicYear, $monthYear, $dueDate, &$created, &$skipped, $year) {
            $lastId = Invoice::max('id') ?? 0;
            
            foreach ($students as $student) {
                // Find structure amount
                $amount = 0;
                if (isset($feeStructures[$student->class_id])) {
                    $amount = $feeStructures[$student->class_id]->amount;
                } elseif (isset($feeStructures[''])) {
                    $amount = $feeStructures['']->amount; // school-wide
                } else {
                    continue; // No fee structure found for this student's class, skip
                }
                
                // Check existing
                $exists = Invoice::where('student_id', $student->id)
                    ->where('fee_type_id', $feeType->id)
                    ->exists(); // For one-time fee, check if any exists at all. For recurring, check month. We assume one-time here.
                    
                if ($exists) {
                    $skipped++;
                    continue;
                }
                
                $lastId++;
                $invoiceNo = 'INV-' . $year . '-' . str_pad($lastId, 6, '0', STR_PAD_LEFT);
                
                Invoice::create([
                    'invoice_no'       => $invoiceNo,
                    'student_id'       => $student->id,
                    'fee_type_id'      => $feeType->id,
                    'academic_year_id' => $academicYear->id,
                    'month_year'       => $feeType->is_recurring ? $monthYear : null,
                    'amount'           => $amount,
                    'discount'         => 0,
                    'fine'             => 0,
                    'net_amount'       => $amount,
                    'due_date'         => $dueDate,
                    'status'           => InvoiceStatus::Unpaid,
                ]);
                $created++;
            }
        });

        $this->closeCustomModal();
        session()->flash('success', "সফলভাবে {$created}টি ইনভয়েস তৈরি হয়েছে। {$skipped}টি স্কিপ করা হয়েছে (ইতিমধ্যে বিদ্যমান)।");
    }

    public function render()
    {
        $invoices = Invoice::query()
            ->when($this->search, fn ($q) =>
                $q->whereHas('student.user', fn ($u) =>
                    $u->where('name', 'LIKE', "%{$this->search}%")
                )->orWhere('invoice_no', 'LIKE', "%{$this->search}%")
            )
            ->when($this->monthFilter, fn ($q) => $q->where('month_year', $this->monthFilter))
            ->when($this->statusFilter !== '', fn ($q) => $q->where('status', $this->statusFilter))
            ->with('student.user', 'feeType')
            ->withSum(['payments' => fn($q) => $q->where('payment_status', 0)], 'amount_paid')
            ->orderByDesc('created_at')
            ->paginate(20);

        $currentMonth   = $this->monthFilter ?: Carbon::now()->format('Y-m');
        $totalExpected  = Invoice::where('month_year', $currentMonth)->sum('net_amount');
        $totalCollected = Invoice::where('month_year', $currentMonth)
            ->whereIn('status', [InvoiceStatus::Paid->value, InvoiceStatus::Partial->value])
            ->join('payments', 'invoices.id', '=', 'payments.invoice_id')
            ->where('payments.payment_status', 0)
            ->sum('payments.amount_paid');
        $totalDue       = $totalExpected - $totalCollected;
        $statusOptions  = InvoiceStatus::cases();
        $feeTypes       = FeeType::where('is_active', 1)->get();
        $schoolClasses  = SchoolClass::orderBy('numeric_order')->get();

        return view('livewire.admin.finance.invoice-manager', compact(
            'invoices', 'totalExpected', 'totalCollected', 'totalDue', 'statusOptions', 'currentMonth', 'feeTypes', 'schoolClasses'
        ));
    }
}
