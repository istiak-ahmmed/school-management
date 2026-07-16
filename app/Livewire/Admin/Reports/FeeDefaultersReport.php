<?php

namespace App\Livewire\Admin\Reports;

use App\Enums\InvoiceStatus;
use App\Models\AcademicYear;
use App\Models\Invoice;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('admin.layouts.app')]
#[Title('বকেয়া ফি রিপোর্ট (Fee Defaulters Report)')]
class FeeDefaultersReport extends Component
{
    public $academic_year_id = '';
    public $class_id = '';
    public $section_id = '';
    public $overdue_days = '';

    public $academicYears = [];
    public $classes = [];
    public $sections = [];

    // For SMS functionality
    public $selectedInvoices = [];
    public $selectAll = false;
    public $smsMessage = '';
    public $isSending = false;

    public function mount()
    {
        $this->academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $activeYear = $this->academicYears->where('is_active', 1)->first();
        if ($activeYear) {
            $this->academic_year_id = $activeYear->id;
        }

        $this->classes = SchoolClass::where('is_active', 1)->get();
        $this->smsMessage = "সম্মানিত অভিভাবক, আপনার সন্তানের স্কুলের মাসিক/অন্যান্য ফি বকেয়া রয়েছে। অনুগ্রহ করে দ্রুত পরিশোধ করুন। ধন্যবাদ, দারুল হিকমাহ।";
    }

    public function updatedClassId($value)
    {
        $this->section_id = '';
        if ($value) {
            $this->sections = Section::where('class_id', $value)->where('is_active', 1)->get();
        } else {
            $this->sections = [];
        }
        $this->resetSelection();
    }

    public function updatedAcademicYearId()
    {
        $this->resetSelection();
    }
    
    public function updatedOverdueDays()
    {
        $this->resetSelection();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedInvoices = $this->defaulters->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedInvoices = [];
        }
    }

    public function resetSelection()
    {
        $this->selectAll = false;
        $this->selectedInvoices = [];
    }

    public function getDefaultersProperty()
    {
        if (!$this->academic_year_id) {
            return collect();
        }

        return Invoice::with(['student.schoolClass', 'student.section', 'feeType'])
            ->whereIn('status', [InvoiceStatus::Unpaid, InvoiceStatus::Partial])
            ->where('academic_year_id', $this->academic_year_id)
            ->where('due_date', '<', Carbon::today())
            ->when($this->class_id, function ($q) {
                $q->whereHas('student', function ($sq) {
                    $sq->where('class_id', $this->class_id);
                });
            })
            ->when($this->section_id, function ($q) {
                $q->whereHas('student', function ($sq) {
                    $sq->where('section_id', $this->section_id);
                });
            })
            ->when($this->overdue_days, function ($q) {
                $q->where('due_date', '<=', Carbon::today()->subDays((int)$this->overdue_days));
            })
            ->orderBy('due_date', 'asc')
            ->get();
    }

    public function sendBulkSms(SmsService $smsService)
    {
        if (empty($this->selectedInvoices)) {
            session()->flash('error', 'কোনো শিক্ষার্থী নির্বাচন করা হয়নি।');
            return;
        }

        $this->validate([
            'smsMessage' => 'required|min:10|max:160'
        ], [
            'smsMessage.required' => 'এসএমএস মেসেজ লিখতে হবে।',
            'smsMessage.max' => 'মেসেজ ১৬০ অক্ষরের বেশি হতে পারবে না।'
        ]);

        $this->isSending = true;
        $successCount = 0;

        $invoices = Invoice::with('student')->whereIn('id', $this->selectedInvoices)->get();

        foreach ($invoices as $invoice) {
            $phone = $invoice->student->phone;
            if ($phone && strlen($phone) >= 11) {
                $smsService->send($phone, $this->smsMessage);
                $successCount++;
            }
        }

        $this->isSending = false;
        $this->resetSelection();
        session()->flash('success', $successCount . ' জনকে সফলভাবে SMS পাঠানো হয়েছে।');
    }

    public function downloadCsv()
    {
        $defaulters = $this->defaulters;
        $filename = 'fee_defaulters_report_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($defaulters) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, ['Invoice No', 'Student Name', 'Class/Section', 'Fee Type', 'Due Date', 'Overdue Days', 'Due Amount', 'Phone']);

            $totalDue = 0;
            foreach ($defaulters as $invoice) {
                $enrollment = optional($invoice->student)->currentEnrollment;
                $classInfo = $enrollment ? optional($enrollment->schoolClass)->name . ' - ' . optional($enrollment->section)->name : '-';
                
                $dueAmount = $invoice->net_amount - ($invoice->payments_sum_amount_paid ?? 0); // Need to calculate properly or use a method
                // Let's use the DB query or a model method if available. Usually amount - discount + fine is net_amount, and if partial we must subtract paid.
                // Assuming net_amount is total, we might need to load sum of payments.
                // For simplicity, let's just use net_amount if unpaid, or calc partial.
                $paid = $invoice->payments()->where('payment_status', 'completed')->sum('amount_paid');
                $actualDue = $invoice->net_amount - $paid;

                $overdueDays = Carbon::parse($invoice->due_date)->diffInDays(Carbon::today());

                fputcsv($file, [
                    $invoice->invoice_no,
                    optional($invoice->student)->name ?? '-',
                    $classInfo,
                    optional($invoice->feeType)->name ?? '-',
                    $invoice->due_date->format('Y-m-d'),
                    $overdueDays . ' Days',
                    $actualDue,
                    optional($invoice->student)->phone ?? '-'
                ]);
                $totalDue += $actualDue;
            }
            fputcsv($file, ['', '', '', '', '', 'Total Due:', $totalDue]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        $defaulters = $this->defaulters;
        
        $totalDue = 0;
        foreach ($defaulters as $invoice) {
            $paid = $invoice->payments()->where('payment_status', 'completed')->sum('amount_paid');
            $totalDue += ($invoice->net_amount - $paid);
            $invoice->actual_due = $invoice->net_amount - $paid; // attach for view
        }

        return view('livewire.admin.reports.fee-defaulters-report', [
            'defaulters' => $defaulters,
            'summary' => [
                'total_students' => $defaulters->unique('student_id')->count(),
                'total_invoices' => $defaulters->count(),
                'total_due' => $totalDue
            ]
        ]);
    }
}
