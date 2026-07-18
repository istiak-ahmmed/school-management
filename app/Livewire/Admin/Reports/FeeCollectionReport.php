<?php

namespace App\Livewire\Admin\Reports;

use App\Models\FeeType;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Support\Facades\Response;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Carbon\Carbon;

#[Layout('admin.layouts.app')]
#[Title('ফি সংগ্রহ রিপোর্ট (Fee Collection Report)')]
class FeeCollectionReport extends Component
{
    public $date_from;
    public $date_to;
    public $class_id = '';
    public $section_id = '';
    public $fee_type_id = '';
    public $payment_method_id = '';

    public $classes = [];
    public $sections = [];
    public $feeTypes = [];
    public $paymentMethods = [];

    public function mount()
    {
        $this->date_from = Carbon::today()->format('Y-m-d');
        $this->date_to = Carbon::today()->format('Y-m-d');
        
        $this->classes = SchoolClass::where('is_active', 1)->get();
        $this->feeTypes = FeeType::where('is_active', 1)->get();
        $this->paymentMethods = PaymentMethod::where('status', 1)->get();
    }

    public function updatedClassId($value)
    {
        $this->section_id = '';
        if ($value) {
            $this->sections = Section::where('class_id', $value)->where('is_active', 1)->get();
        } else {
            $this->sections = [];
        }
    }

    public function getPaymentsProperty()
    {
        return Payment::with(['invoice.student.user', 'invoice.student.schoolClass', 'invoice.student.section', 'invoice.feeType', 'paymentMethod'])
            ->where('payment_status', 'completed')
            ->when($this->date_from, function ($q) {
                $q->whereDate('paid_at', '>=', $this->date_from);
            })
            ->when($this->date_to, function ($q) {
                $q->whereDate('paid_at', '<=', $this->date_to);
            })
            ->when($this->fee_type_id, function ($q) {
                $q->whereHas('invoice', function ($sq) {
                    $sq->where('fee_type_id', $this->fee_type_id);
                });
            })
            ->when($this->payment_method_id, function ($q) {
                $q->where('payment_method_id', $this->payment_method_id);
            })
            ->when($this->class_id, function ($q) {
                $q->whereHas('invoice.student', function ($sq) {
                    $sq->where('class_id', $this->class_id);
                });
            })
            ->when($this->section_id, function ($q) {
                $q->whereHas('invoice.student', function ($sq) {
                    $sq->where('section_id', $this->section_id);
                });
            })
            ->orderBy('paid_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function downloadCsv()
    {
        $payments = $this->payments;
        $filename = 'fee_collection_report_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, ['Date', 'Receipt No', 'Student Name', 'Class/Section', 'Fee Type', 'Payment Method', 'Amount']);

            $total = 0;
            foreach ($payments as $payment) {
                $student = optional($payment->invoice)->student;
                $classInfo = $student ? optional($student->schoolClass)->name . ' - ' . optional($student->section)->name : '-';
                $feeTypeName = optional(optional($payment->invoice)->feeType)->name ?? '-';
                $methodName = optional($payment->paymentMethod)->bn_name ?? optional($payment->paymentMethod)->en_name ?? '-';

                fputcsv($file, [
                    $payment->payment_date->format('Y-m-d'),
                    $payment->payment_no,
                    optional(optional(optional($payment->invoice)->student)->user)->name ?? '-',
                    $classInfo,
                    $feeTypeName,
                    $methodName,
                    $payment->amount_paid
                ]);
                $total += $payment->amount_paid;
            }
            fputcsv($file, ['', '', '', '', '', 'Total:', $total]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        $payments = $this->payments;
        $totalAmount = $payments->sum('amount_paid');
        $methodsBreakdown = $payments->groupBy('payment_method_id')->map(function($group) {
            return [
                'name' => optional($group->first()->paymentMethod)->bn_name ?? optional($group->first()->paymentMethod)->en_name ?? 'Unknown',
                'amount' => $group->sum('amount_paid')
            ];
        });

        return view('livewire.admin.reports.fee-collection-report', [
            'payments' => $payments,
            'summary' => [
                'total' => $totalAmount,
                'count' => $payments->count(),
                'methods' => $methodsBreakdown
            ]
        ]);
    }
}
