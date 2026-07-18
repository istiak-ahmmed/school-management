<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\StudentAttendance;
use App\Models\Invoice;
use App\Models\ExamRoutine;
use App\Models\ClassRoutine;
use App\Models\Notice;
use Carbon\Carbon;

#[Layout('student.layouts.app')]
#[Title('ড্যাশবোর্ড - শিক্ষার্থী')]
class Dashboard extends Component
{
    public function render()
    {
        $student = auth()->user()->student;
        
        if (!$student) {
            return view('livewire.student.dashboard-empty');
        }

        // Attendance stats for current month
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $totalDays = StudentAttendance::where('student_id', $student->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->count();
            
        $presentDays = StudentAttendance::where('student_id', $student->id)
            ->whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->where('status', 1)
            ->count();
            
        $attendancePercentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100) : 0;

        // Pending Fees
        $pendingFees = Invoice::where('student_id', $student->id)
            ->where('status', '!=', 'paid')
            ->get()
            ->sum(function($invoice) {
                return $invoice->remaining_amount;
            });

        // Upcoming Exams (from today onwards)
        $upcomingExams = ExamRoutine::with(['exam', 'subject'])
            ->where('class_id', $student->class_id)
            ->where('exam_date', '>=', now()->toDateString())
            ->orderBy('exam_date')
            ->take(3)
            ->get();

        // Today's Class Routine
        $dayOfWeek = strtolower(now()->format('l'));
        $todayClasses = ClassRoutine::with(['subject', 'teacher'])
            ->where('class_id', $student->class_id)
            ->where('section_id', $student->section_id)
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time')
            ->get();

        // Latest Notices
        $notices = Notice::where('is_published', true)
            ->where(function($query) use ($student) {
                $query->whereNull('audience')
                      ->orWhereJsonContains('audience', 'students')
                      ->orWhereJsonContains('audience', 'class_' . $student->class_id)
                      ->orWhereJsonContains('audience', 'section_' . $student->section_id);
            })
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        return view('livewire.student.dashboard', [
            'student' => $student,
            'attendancePercentage' => $attendancePercentage,
            'totalDays' => $totalDays,
            'presentDays' => $presentDays,
            'pendingFees' => $pendingFees,
            'upcomingExams' => $upcomingExams,
            'todayClasses' => $todayClasses,
            'notices' => $notices
        ]);
    }
}
