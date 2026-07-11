<?php

namespace App\Livewire\Website;

use App\Models\Teacher;
use Livewire\Component;
use Livewire\Attributes\Layout;

class TeacherDirectory extends Component
{
    public $search = '';

    #[Layout('layouts.public')]
    public function render()
    {
        $teachers = Teacher::with('user')
            ->whereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->where('status', 1) // Only active
            ->get();

        return view('livewire.website.teacher-directory', [
            'teachers' => $teachers
        ]);
    }
}
