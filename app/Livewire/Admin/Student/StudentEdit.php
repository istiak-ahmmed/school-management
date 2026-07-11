<?php

namespace App\Livewire\Admin\Student;

use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class StudentEdit extends Component
{
    public Student $student;

    #[Validate('required')]
    public $class_id = '';

    #[Validate('nullable')]
    public $section_id = '';

    #[Validate('nullable|string|max:20')]
    public $roll_no = '';

    #[Validate('required|integer')]
    public $status = 1;

    public function mount(Student $student)
    {
        $this->student = $student;
        $this->class_id = $student->class_id;
        $this->section_id = $student->section_id;
        $this->roll_no = $student->roll_no;
        $this->status = $student->status;
    }

    public function updatedClassId()
    {
        // When class changes, reset section
        $this->section_id = '';
    }

    public function save()
    {
        $this->validate();

        $this->student->update([
            'class_id' => $this->class_id,
            'section_id' => $this->section_id ?: null,
            'roll_no' => $this->roll_no,
            'status' => $this->status,
        ]);

        session()->flash('message', 'শিক্ষার্থীর তথ্য সফলভাবে আপডেট করা হয়েছে।');
        return redirect()->route('admin.students');
    }

    public function render()
    {
        return view('livewire.admin.student.student-edit', [
            'classes' => SchoolClass::orderBy('numeric_order')->get(),
            'sections' => Section::where('class_id', $this->class_id)->get(),
        ]);
    }
}
