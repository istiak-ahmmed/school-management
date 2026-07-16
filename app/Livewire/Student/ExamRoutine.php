<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Exam;
use App\Enums\ExamStatus;
use Carbon\Carbon;

#[Layout('student.layouts.app')]
#[Title('পরীক্ষার রুটিন - শিক্ষার্থী')]
class ExamRoutine extends Component
{
    public $selectedExamId = null;

    public function render()
    {
        $student = auth()->user()->student;
        
        if (!$student) {
            return view('livewire.student.dashboard-empty');
        }

        // Get upcoming or ongoing exams for this student's class
        $exams = Exam::whereIn('status', [ExamStatus::Upcoming->value, ExamStatus::Ongoing->value])
            ->whereHas('routines', function ($query) use ($student) {
                $query->where('class_id', $student->class_id);
            })
            ->orderBy('start_date', 'asc')
            ->get();

        if (!$this->selectedExamId && $exams->count() > 0) {
            // Find the closest exam
            $this->selectedExamId = $exams->first()->id;
        }

        $routines = collect();
        if ($this->selectedExamId) {
            $routines = \App\Models\ExamRoutine::with('subject')
                ->where('exam_id', $this->selectedExamId)
                ->where('class_id', $student->class_id)
                ->orderBy('exam_date')
                ->orderBy('start_time')
                ->get();
        }

        return view('livewire.student.exam-routine', [
            'exams' => $exams,
            'routines' => $routines
        ]);
    }
}
