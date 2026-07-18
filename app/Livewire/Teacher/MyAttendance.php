<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use App\Models\EmployeeAttendance;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Carbon\Carbon;

#[Layout('teacher.layouts.app')]
#[Title('My Attendance')]
class MyAttendance extends Component
{
    public $selectedMonth;
    public $selectedYear;

    public function mount()
    {
        $this->selectedMonth = now()->format('m');
        $this->selectedYear = now()->format('Y');
    }

    public function render()
    {
        $teacher = auth()->user()->teacher;
        
        if (!$teacher) {
            return view('livewire.teacher.dashboard-empty');
        }

        $attendances = EmployeeAttendance::where('employee_type', 'teacher')
            ->where('employee_id', $teacher->id)
            ->whereMonth('date', $this->selectedMonth)
            ->whereYear('date', $this->selectedYear)
            ->orderBy('date', 'asc')
            ->get();

        $totalDays = $attendances->count();
        $presentDays = $attendances->where('status', 1)->count();
        $absentDays = $attendances->where('status', 2)->count();
        $lateDays = $attendances->where('status', 3)->count();
        $leaveDays = $attendances->where('status', 4)->count();
        $halfDays = $attendances->where('status', 5)->count();

        // For percentage: (present + (halfDay * 0.5)) / total
        $effectivePresent = $presentDays + ($halfDays * 0.5);
        $attendancePercentage = $totalDays > 0 ? round(($effectivePresent / $totalDays) * 100) : 0;

        $years = [];
        $currentYear = now()->format('Y');
        for ($i = $currentYear; $i >= $currentYear - 2; $i--) {
            $years[] = $i;
        }

        return view('livewire.teacher.my-attendance', [
            'attendances' => $attendances,
            'totalDays' => $totalDays,
            'presentDays' => $presentDays,
            'absentDays' => $absentDays,
            'lateDays' => $lateDays,
            'leaveDays' => $leaveDays,
            'halfDays' => $halfDays,
            'attendancePercentage' => $attendancePercentage,
            'years' => $years
        ]);
    }
}
