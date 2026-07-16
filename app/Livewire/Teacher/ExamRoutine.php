<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\ExamRoutine as RoutineModel;
use App\Models\Exam;
use Illuminate\Support\Facades\DB;

#[Layout('teacher.layouts.app')]
#[Title('পরীক্ষার রুটিন - শিক্ষক পোর্টাল')]
class ExamRoutine extends Component
{
    public function render()
    {
        $teacher = auth()->user()->teacher;
        
        if (!$teacher) {
            return view('livewire.teacher.dashboard-empty');
        }

        $assignedClassIds = DB::table('teacher_subjects')
            ->where('teacher_id', $teacher->id)
            ->pluck('class_id')
            ->unique()
            ->toArray();

        // Get exams that have routines for teacher's classes
        $exams = Exam::whereHas('routines', function ($q) use ($assignedClassIds) {
            $q->whereIn('class_id', $assignedClassIds);
        })
        ->with(['routines' => function ($q) use ($assignedClassIds) {
            $q->with(['subject', 'schoolClass'])
              ->whereIn('class_id', $assignedClassIds)
              ->orderBy('exam_date')
              ->orderBy('start_time');
        }])
        ->orderBy('id', 'desc')
        ->get();

        return view('livewire.teacher.exam-routine', [
            'exams' => $exams,
        ]);
    }
}
