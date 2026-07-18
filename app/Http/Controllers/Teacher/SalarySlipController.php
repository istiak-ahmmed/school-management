<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\EmployeeType;
use App\Http\Controllers\Controller;
use App\Models\SalaryPayment;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class SalarySlipController extends Controller
{
    /**
     * Generate and stream salary slip PDF for teacher.
     */
    public function show(SalaryPayment $payment)
    {
        $user = Auth::user();
        
        // Ensure the logged in user is a teacher and owns this salary slip
        if (!$user || !$user->teacher || $payment->employee_type !== EmployeeType::Teacher || $payment->employee_id !== $user->teacher->id) {
            abort(403, 'Unauthorized action.');
        }

        $payment->load('payer');
        
        $teacher = $user->teacher;
        $employeeName = $user->name ?? 'N/A';
        $employeeDesignation = $teacher->designation ?? 'Teacher';
        $employeeId = $teacher->employee_id ?? 'N/A';

        return view('pdf.salary-slip', compact('payment', 'employeeName', 'employeeDesignation', 'employeeId'));
    }
}
