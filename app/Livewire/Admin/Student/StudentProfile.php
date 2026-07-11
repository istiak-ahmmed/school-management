<?php

namespace App\Livewire\Admin\Student;

use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class StudentProfile extends Component
{
    public Student $student;

    public string $activeTab = 'personal';

    public function mount(Student $student): void
    {
        $this->student = $student->load([
            'user',
            'schoolClass',
            'section',
            'academicYear',
            'guardians',
        ]);
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.admin.student.student-profile');
    }
}
