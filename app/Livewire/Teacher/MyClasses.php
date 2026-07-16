<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

#[Layout('teacher.layouts.app')]
#[Title('আমার ক্লাস - শিক্ষক পোর্টাল')]
class MyClasses extends Component
{
    public $expandedClass = null;
    public $expandedSection = null;

    public function toggleClass($classId)
    {
        $this->expandedClass = ($this->expandedClass === $classId) ? null : $classId;
        $this->expandedSection = null;
    }

    public function toggleSection($sectionId)
    {
        $this->expandedSection = ($this->expandedSection === $sectionId) ? null : $sectionId;
    }

    public function render()
    {
        $teacher = auth()->user()->teacher;
        
        if (!$teacher) {
            return view('livewire.teacher.dashboard-empty');
        }

        // Get all teacher_subjects with class, section, subject info
        $assignments = DB::table('teacher_subjects')
            ->join('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
            ->join('classes', 'teacher_subjects.class_id', '=', 'classes.id')
            ->leftJoin('sections', 'teacher_subjects.section_id', '=', 'sections.id')
            ->where('teacher_subjects.teacher_id', $teacher->id)
            ->select(
                'teacher_subjects.class_id',
                'classes.name as class_name',
                'classes.numeric_order',
                'teacher_subjects.section_id',
                'sections.name as section_name',
                'subjects.name as subject_name',
                'subjects.id as subject_id'
            )
            ->orderBy('classes.numeric_order')
            ->get();

        // Group by class
        $grouped = $assignments->groupBy('class_id')->map(function ($items) {
            $first = $items->first();
            return [
                'class_id' => $first->class_id,
                'class_name' => $first->class_name,
                'subjects' => $items->pluck('subject_name')->unique()->values(),
                'sections' => $items->where('section_id', '!=', null)->pluck('section_name')->unique()->filter()->values(),
            ];
        });

        // Load students for expanded section
        $students = collect();
        if ($this->expandedSection) {
            $students = Student::where('section_id', $this->expandedSection)
                ->where('status', 1)
                ->orderBy('roll_no')
                ->get();
        }

        return view('livewire.teacher.my-classes', [
            'grouped' => $grouped,
            'assignments' => $assignments,
            'students' => $students,
        ]);
    }
}
