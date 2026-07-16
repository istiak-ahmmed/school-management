<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Exam;
use App\Models\Mark;
use App\Enums\ExamStatus;

#[Layout('student.layouts.app')]
#[Title('পরীক্ষার ফলাফল - শিক্ষার্থী')]
class MyResults extends Component
{
    public $selectedExamId = null;

    public function render()
    {
        $student = auth()->user()->student;
        
        if (!$student) {
            return view('livewire.student.dashboard-empty');
        }

        // Get exams where results are published and the student is in that class
        $publishedExams = Exam::where('status', ExamStatus::Published->value)
            ->whereHas('routines', function ($query) use ($student) {
                $query->where('class_id', $student->class_id);
            })
            ->orderBy('id', 'desc')
            ->get();

        if (!$this->selectedExamId && $publishedExams->count() > 0) {
            $this->selectedExamId = $publishedExams->first()->id;
        }

        $marks = collect();
        if ($this->selectedExamId) {
            $marks = Mark::with('subject')
                ->where('exam_id', $this->selectedExamId)
                ->where('student_id', $student->id)
                ->get();
        }

        // Calculate summary
        $totalMarks = 0;
        $obtainedMarks = 0;
        $totalGradePoints = 0;
        $hasFailed = false;

        foreach ($marks as $mark) {
            $totalMarks += $mark->full_marks;
            $obtainedMarks += $mark->marks_obtained;
            $totalGradePoints += $mark->grade_point;
            
            if ($mark->is_absent || $mark->grade === 'F') {
                $hasFailed = true;
            }
        }

        $gpa = $marks->count() > 0 ? number_format($totalGradePoints / $marks->count(), 2) : 0;
        if ($hasFailed) {
            $gpa = 0;
            $finalGrade = 'F';
        } else {
            // Simplified logic: calculate grade based on GPA
            $finalGrade = $this->calculateGradeFromGpa($gpa);
        }

        return view('livewire.student.my-results', [
            'publishedExams' => $publishedExams,
            'marks' => $marks,
            'totalMarks' => $totalMarks,
            'obtainedMarks' => $obtainedMarks,
            'gpa' => $gpa,
            'finalGrade' => $finalGrade,
            'hasFailed' => $hasFailed
        ]);
    }

    private function calculateGradeFromGpa($gpa)
    {
        if ($gpa >= 5.0) return 'A+';
        if ($gpa >= 4.0) return 'A';
        if ($gpa >= 3.5) return 'A-';
        if ($gpa >= 3.0) return 'B';
        if ($gpa >= 2.0) return 'C';
        if ($gpa >= 1.0) return 'D';
        return 'F';
    }
}
