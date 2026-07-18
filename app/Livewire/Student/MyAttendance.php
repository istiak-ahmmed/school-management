<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\StudentAttendance;
use Carbon\Carbon;

#[Layout('student.layouts.app')]
#[Title('আমার হাজিরা - শিক্ষার্থী')]
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
        $student = auth()->user()->student;
        
        if (!$student) {
            return view('livewire.student.dashboard-empty');
        }

        $attendances = StudentAttendance::where('student_id', $student->id)
            ->whereMonth('date', $this->selectedMonth)
            ->whereYear('date', $this->selectedYear)
            ->orderBy('date', 'asc')
            ->get();

        $totalDays = $attendances->count();
        $presentDays = $attendances->where('status', 1)->count();
        $absentDays = $attendances->where('status', 2)->count();
        $lateDays = $attendances->where('status', 3)->count();
        $excusedDays = $attendances->where('status', 4)->count();

        $attendancePercentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100) : 0;

        // Generate years for dropdown (e.g., from student admission year to current year)
        $years = [];
        $currentYear = now()->format('Y');
        for ($i = $currentYear; $i >= $currentYear - 2; $i--) {
            $years[] = $i;
        }

        return view('livewire.student.my-attendance', [
            'attendances' => $attendances,
            'totalDays' => $totalDays,
            'presentDays' => $presentDays,
            'absentDays' => $absentDays,
            'lateDays' => $lateDays,
            'excusedDays' => $excusedDays,
            'attendancePercentage' => $attendancePercentage,
            'years' => $years
        ]);
    }
}
