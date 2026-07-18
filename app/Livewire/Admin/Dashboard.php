<?php

namespace App\Livewire\Admin;

use App\Enums\LedgerAccountType;
use App\Models\FeeCollection;
use App\Models\Ledger;
use App\Models\Staff;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Teacher;
use App\Models\EmployeeAttendance;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('admin.layouts.app')]
#[Title('অ্যাডমিন ড্যাশবোর্ড')]
class Dashboard extends Component
{
    public function render()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // 1. Statistics
        $totalStudents = Student::where('status', 1)->count();
        $totalTeachers = Teacher::where('status', 1)->count();
        $totalStaff = Staff::where('status', 1)->count();

        // 2. Finance
        $monthlyRevenue = Ledger::where('account_type', LedgerAccountType::Income)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $monthlyExpense = Ledger::where('account_type', LedgerAccountType::Expense)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // 3. Today's Student Attendance
        $attendanceRecords = StudentAttendance::whereDate('date', $today)->get();
        $totalAttendanceTaken = $attendanceRecords->count();
        $presentCount = $attendanceRecords->where('status', 1)->count();
        $studentAttendanceRate = $totalAttendanceTaken > 0 ? round(($presentCount / $totalAttendanceTaken) * 100) : 0;

        // 4. Today's Employee Attendance
        $empAttendanceRecords = EmployeeAttendance::whereDate('date', $today)->get();
        $totalEmpAttendanceTaken = $empAttendanceRecords->count();
        $empPresentCount = $empAttendanceRecords->where('status', 1)->count();
        $employeeAttendanceRate = $totalEmpAttendanceTaken > 0 ? round(($empPresentCount / $totalEmpAttendanceTaken) * 100) : 0;

        // 5. Recent Fee Collections (Fix N+1 query by eager loading student.user)
        $recentFees = \App\Models\Payment::with(['student.user'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('livewire.admin.dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'totalStaff',
            'monthlyRevenue',
            'monthlyExpense',
            'studentAttendanceRate',
            'employeeAttendanceRate',
            'recentFees'
        ));
    }
}

