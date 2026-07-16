<?php

namespace App\Livewire\Admin\Reports;

use App\Models\PaymentMethod;
use App\Models\SalaryPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('admin.layouts.app')]
#[Title('স্টাফ ও পে-রোল রিপোর্ট (Staff & Payroll Report)')]
class StaffPayrollReport extends Component
{
    public $month_year = '';
    public $employee_type = ''; // 'teacher', 'staff'
    public $status = ''; // 'paid', 'unpaid', 'partial' etc.

    public $paymentMethods = [];

    public function mount()
    {
        $this->month_year = Carbon::today()->format('Y-m');
        $this->paymentMethods = PaymentMethod::where('status', 1)->get();
    }

    public function getPayrollDataProperty()
    {
        if (!$this->month_year) {
            return collect();
        }

        $query = SalaryPayment::with(['paymentMethod'])
            ->where('month_year', $this->month_year);

        if ($this->employee_type === 'teacher') {
            $query->where('employee_type', \App\Enums\EmployeeType::Teacher->value);
        } elseif ($this->employee_type === 'staff') {
            $query->where('employee_type', \App\Enums\EmployeeType::Staff->value);
        }

        if ($this->status === 'paid') {
            $query->where('status', \App\Enums\SalaryStatus::Paid->value);
        } elseif ($this->status === 'unpaid') {
            $query->whereIn('status', [\App\Enums\SalaryStatus::Pending->value, \App\Enums\SalaryStatus::Partial->value]);
        }

        $payments = $query->orderBy('paid_at', 'desc')->get();

        // Load polymorphic relation manually since it's defined conditionally in model without proper morph map sometimes,
        // but let's assume $payment->employee works.
        // The employee relation might not work cleanly in eager loading if morph map is not used, 
        // so we load it per item.
        foreach ($payments as $payment) {
            $payment->load('employee.user');
        }

        return $payments;
    }

    public function downloadCsv()
    {
        $payments = $this->payrollData;
        $filename = 'staff_payroll_report_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, ['Voucher No', 'Employee Name', 'Role', 'Basic Salary', 'Allowances', 'Deductions', 'Net Salary', 'Status', 'Payment Method']);

            $totalNet = 0;
            foreach ($payments as $payment) {
                $employeeType = $payment->employee_type === \App\Enums\EmployeeType::Teacher ? 'Teacher' : 'Staff';
                $name = optional(optional($payment->employee)->user)->name ?? optional($payment->employee)->name ?? 'Unknown';

                fputcsv($file, [
                    $payment->voucher_no,
                    $name,
                    $employeeType,
                    $payment->basic_salary,
                    $payment->total_allowance,
                    $payment->total_deduction + $payment->advance_deducted,
                    $payment->net_salary,
                    $payment->status->label() ?? $payment->status,
                    optional($payment->paymentMethod)->bn_name ?? optional($payment->paymentMethod)->en_name ?? '-'
                ]);
                $totalNet += $payment->net_salary;
            }
            
            fputcsv($file, ['', '', '', '', '', 'Total Paid:', $totalNet, '', '']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        $payments = $this->payrollData;
        
        $totalPaid = $payments->where('status', \App\Enums\SalaryStatus::Paid)->sum('net_salary');
        $totalPending = $payments->where('status', \App\Enums\SalaryStatus::Pending)->sum('net_salary');

        return view('livewire.admin.reports.staff-payroll-report', [
            'payments' => $payments,
            'summary' => [
                'total_employees' => $payments->count(),
                'total_paid_amount' => $totalPaid,
                'total_pending_amount' => $totalPending
            ]
        ]);
    }
}
