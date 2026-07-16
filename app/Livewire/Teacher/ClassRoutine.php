<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\ClassRoutine as RoutineModel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

#[Layout('teacher.layouts.app')]
#[Title('ক্লাস রুটিন - শিক্ষক পোর্টাল')]
class ClassRoutine extends Component
{
    public $selectedDay = null;

    public $days = [
        0 => 'রবিবার',
        1 => 'সোমবার',
        2 => 'মঙ্গলবার',
        3 => 'বুধবার',
        4 => 'বৃহস্পতিবার',
        5 => 'শুক্রবার',
        6 => 'শনিবার',
    ];

    public function mount()
    {
        // Default to today's day number (0=Sun, 6=Sat)
        $this->selectedDay = (int) Carbon::now()->format('w');
    }

    public function render()
    {
        $teacher = auth()->user()->teacher;
        
        if (!$teacher) {
            return view('livewire.teacher.dashboard-empty');
        }

        // Fetch all routines for this teacher across all their classes
        $allRoutines = RoutineModel::with(['schoolClass', 'section', 'subject'])
            ->where('teacher_id', $teacher->id)
            ->orderBy('start_time')
            ->get();

        $routinesByDay = $allRoutines->groupBy(function($routine) {
            // day_of_week is cast as DayOfWeek enum (0-6 based on the enum values)
            return $routine->getRawOriginal('day_of_week');
        });

        return view('livewire.teacher.class-routine', [
            'routinesByDay' => $routinesByDay,
        ]);
    }
}
