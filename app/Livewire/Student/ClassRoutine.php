<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\ClassRoutine as RoutineModel;

#[Layout('student.layouts.app')]
#[Title('ক্লাস রুটিন - শিক্ষার্থী')]
class ClassRoutine extends Component
{
    public $selectedDay = null;
    
    public $days = [
        'saturday' => 'শনিবার',
        'sunday' => 'রবিবার',
        'monday' => 'সোমবার',
        'tuesday' => 'মঙ্গলবার',
        'wednesday' => 'বুধবার',
        'thursday' => 'বৃহস্পতিবার',
        'friday' => 'শুক্রবার',
    ];

    public function mount()
    {
        $this->selectedDay = strtolower(now()->format('l'));
    }

    public function render()
    {
        $student = auth()->user()->student;
        
        if (!$student) {
            return view('livewire.student.dashboard-empty');
        }

        // Fetch full week routines
        $allRoutines = RoutineModel::with(['subject', 'teacher.user'])
            ->where('class_id', $student->class_id)
            ->where('section_id', $student->section_id)
            ->orderBy('start_time')
            ->get();

        // Group by day for the full view, or just filter by selected day
        $routinesByDay = $allRoutines->groupBy('day_of_week');

        return view('livewire.student.class-routine', [
            'routinesByDay' => $routinesByDay,
        ]);
    }
}
