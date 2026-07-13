<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Enums\EmployeeType;
use App\Http\Controllers\Controller;
use App\Models\SalaryPayment;
use App\Models\Staff;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;

class SalarySlipController extends Controller
{
    /**
     * Generate and stream salary slip PDF.
     */
    public function show(SalaryPayment $payment)
    {
        $payment->load('payer');
        
        $employeeName = 'N/A';
        $employeeDesignation = 'N/A';
        $employeeId = 'N/A';

        if ($payment->employee_type === EmployeeType::Teacher) {
            $teacher = Teacher::with('user')->find($payment->employee_id);
            if ($teacher) {
                $employeeName = $teacher->user->name ?? 'N/A';
                $employeeDesignation = $teacher->designation ?? 'Teacher';
                $employeeId = $teacher->employee_id ?? 'N/A';
            }
        } else {
            $staff = Staff::with('user')->find($payment->employee_id);
            if ($staff) {
                $employeeName = $staff->user->name ?? 'N/A';
                $employeeDesignation = $staff->designation ?? 'Staff';
                $employeeId = $staff->employee_id ?? 'N/A';
            }
        }

        $pdf = Pdf::loadView('pdf.salary-slip', compact('payment', 'employeeName', 'employeeDesignation', 'employeeId'))
            ->setPaper('a5', 'portrait')
            ->setOption('dpi', 150);

        return $pdf->stream("salary-slip-{$payment->voucher_no}.pdf");
    }
}
