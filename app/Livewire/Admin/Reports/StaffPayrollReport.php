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

        $month = $this->month_year;
        $employees = [];

        // 1. Fetch Teachers
        if ($this->employee_type === '' || $this->employee_type === 'teacher') {
            $teachers = \App\Models\Teacher::with('user')->where('status', 1)->get();
            foreach ($teachers as $teacher) {
                $employees[] = $this->formatEmployeeData($teacher, \App\Enums\EmployeeType::Teacher, $month);
            }
        }

        // 2. Fetch Staff
        if ($this->employee_type === '' || $this->employee_type === 'staff') {
            $staffs = \App\Models\Staff::with('user')->where('status', 1)->get();
            foreach ($staffs as $staff) {
                $employees[] = $this->formatEmployeeData($staff, \App\Enums\EmployeeType::Staff, $month);
            }
        }

        $collection = collect($employees);

        if ($this->status === 'paid') {
            $collection = $collection->where('status', \App\Enums\SalaryStatus::Paid);
        } elseif ($this->status === 'unpaid') {
            $collection = $collection->where('status', \App\Enums\SalaryStatus::Pending);
        }

        return $collection;
    }

    protected function formatEmployeeData($model, \App\Enums\EmployeeType $type, string $month): array
    {
        // Get structure
        $structure = \App\Models\SalaryStructure::where('employee_type', $type->value)
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
            ->where('status', \App\Enums\SalaryStatus::Paid->value)
            ->first();

        $status = $payment ? \App\Enums\SalaryStatus::Paid : \App\Enums\SalaryStatus::Pending;

        // Pending advances
        $advances = \App\Models\SalaryAdvance::where('employee_type', $type->value)
            ->where('employee_id', $model->id)
            ->whereIn('status', [\App\Enums\AdvanceStatus::Approved->value])
            ->get();

        $pendingAdvance = $advances->sum('remaining');

        return [
            'id'             => $model->id,
            'type'           => $type,
            'name'           => $model->user->name ?? $model->name ?? 'N/A',
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
            foreach ($payments as $emp) {
                $employeeType = $emp['type'] === \App\Enums\EmployeeType::Teacher ? 'Teacher' : 'Staff';
                $name = $emp['name'];
                $payment = $emp['payment'];

                $voucher = $payment ? $payment->voucher_no : '-';
                $allowance = $payment ? $payment->total_allowance : ($emp['structure'] ? ($emp['structure']->house_rent + $emp['structure']->medical_allowance + $emp['structure']->transport_allowance + $emp['structure']->other_allowance) : 0);
                $deduction = $payment ? ($payment->total_deduction + $payment->advance_deducted) : ($emp['structure'] ? ($emp['structure']->deduction_provident + $emp['structure']->deduction_tax) : 0);
                $method = $payment && $payment->paymentMethod ? ($payment->paymentMethod->bn_name ?? $payment->paymentMethod->en_name) : '-';

                fputcsv($file, [
                    $voucher,
                    $name,
                    $employeeType,
                    $emp['basic_salary'],
                    $allowance,
                    $deduction,
                    $emp['net_salary'],
                    $emp['status'] === \App\Enums\SalaryStatus::Paid ? 'Paid' : 'Unpaid',
                    $method
                ]);
                
                if ($emp['status'] === \App\Enums\SalaryStatus::Paid) {
                    $totalNet += $emp['net_salary'];
                }
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
