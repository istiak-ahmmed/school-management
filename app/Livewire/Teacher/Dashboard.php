<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\ClassRoutine;
use App\Models\ExamRoutine;
use App\Models\Section;
use App\Models\Mark;
use App\Models\Exam;
use App\Models\Notice;
use App\Enums\ExamStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

#[Layout('teacher.layouts.app')]
#[Title('ড্যাশবোর্ড - শিক্ষক পোর্টাল')]
class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        $teacher = $user->teacher;

        if (!$teacher) {
            return view('livewire.teacher.dashboard-empty');
        }

        // Get teacher's assigned class IDs
        $assignedClassIds = DB::table('teacher_subjects')
            ->where('teacher_id', $teacher->id)
            ->pluck('class_id')
            ->unique()
            ->toArray();

        // Today's class schedule (based on class_routines where teacher_id = this teacher)
        $dayOfWeekInt = (int) Carbon::now()->format('w'); // 0=Sun, 1=Mon, ...6=Sat
        $todayClasses = ClassRoutine::with(['schoolClass', 'section', 'subject'])
            ->where('teacher_id', $teacher->id)
            ->where('day_of_week', $dayOfWeekInt)
            ->orderBy('start_time')
            ->get();

        // Am I a Form Teacher?
        $formTeacherSections = Section::with('schoolClass')
            ->where('teacher_id', $user->id)
            ->get();
        $isFormTeacher = $formTeacherSections->isNotEmpty();

        // Pending marks entry: exams in MarksEntry status where teacher has subjects
        // but no marks recorded yet
        $ongoingExams = Exam::whereIn('status', [ExamStatus::MarksEntry->value, ExamStatus::Ongoing->value])
            ->whereHas('routines', function ($q) use ($assignedClassIds) {
                $q->whereIn('class_id', $assignedClassIds);
            })
            ->get();

        $pendingMarksCount = 0;
        foreach ($ongoingExams as $exam) {
            $assignedSubjects = DB::table('teacher_subjects')
                ->where('teacher_id', $teacher->id)
                ->pluck('subject_id')
                ->toArray();

            // Count subjects for which no marks have been entered
            foreach ($assignedSubjects as $subjectId) {
                $hasMarks = Mark::where('exam_id', $exam->id)
                    ->where('subject_id', $subjectId)
                    ->exists();
                if (!$hasMarks) {
                    $pendingMarksCount++;
                }
            }
        }

        // Upcoming exams (for teacher's classes)
        $upcomingExams = ExamRoutine::with(['exam', 'subject', 'schoolClass'])
            ->whereIn('class_id', $assignedClassIds)
            ->where('exam_date', '>=', now()->toDateString())
            ->orderBy('exam_date')
            ->take(4)
            ->get();

        // Relevant notices: public + for teachers + for their classes
        $classConditions = collect($assignedClassIds)->map(fn($id) => 'class_' . $id)->toArray();
        $notices = Notice::where('is_published', true)
            ->where(function ($q) use ($classConditions) {
                $q->whereNull('audience')
                  ->orWhereJsonContains('audience', 'teachers');
                foreach ($classConditions as $condition) {
                    $q->orWhereJsonContains('audience', $condition);
                }
            })
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('livewire.teacher.dashboard', [
            'teacher' => $teacher,
            'todayClasses' => $todayClasses,
            'isFormTeacher' => $isFormTeacher,
            'formTeacherSections' => $formTeacherSections,
            'pendingMarksCount' => $pendingMarksCount,
            'ongoingExamsCount' => $ongoingExams->count(),
            'upcomingExams' => $upcomingExams,
            'notices' => $notices,
            'assignedClassIds' => $assignedClassIds,
        ]);
    }
}
