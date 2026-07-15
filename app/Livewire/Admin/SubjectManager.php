<?php

namespace App\Livewire\Admin;

use App\Models\SchoolClass;
use App\Models\Subject;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Livewire\Traits\Sortable;

#[Layout('admin.layouts.app')]
class SubjectManager extends Component
{
    use Sortable;

    

    public $subjects;
    public $classes;

    public $subjectId = null;

    #[Validate('required|string|max:100')]
    public $name = '';

    #[Validate('required|string|max:20')]
    public $code = '';

    #[Validate('required|exists:classes,id')]
    public $class_id = '';

    #[Validate('required|in:1,2,3')]
    public $subject_type = 1;

    #[Validate('required|numeric|min:0')]
    public $full_marks = 100;

    #[Validate('required|numeric|min:0|lte:full_marks')]
    public $pass_marks = 33;

    public bool $showModal = false;
    public bool $isEditing = false;

    public function mount()
    {
        $this->sortDirection = 'asc';
        $this->loadData();
    }

    public function loadData()
    {
        $this->subjects = Subject::with('schoolClass')->orderBy($this->sortField, $this->sortDirection)->get();
        $this->classes = SchoolClass::orderBy('numeric_order')->get();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetValidation();
        $subject = Subject::findOrFail($id);
        $this->subjectId = $subject->id;
        $this->name = $subject->name;
        $this->code = $subject->code;
        $this->class_id = $subject->class_id;
        $this->subject_type = $subject->subject_type;
        $this->full_marks = $subject->full_marks;
        $this->pass_marks = $subject->pass_marks;
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->subjectId) {
            $subject = Subject::findOrFail($this->subjectId);
            $subject->update([
                'name' => $this->name,
                'code' => $this->code,
                'class_id' => $this->class_id,
                'subject_type' => $this->subject_type,
                'full_marks' => $this->full_marks,
                'pass_marks' => $this->pass_marks,
            ]);
            session()->flash('message', 'বিষয় সফলভাবে আপডেট করা হয়েছে।');
        } else {
            Subject::create([
                'name' => $this->name,
                'code' => $this->code,
                'class_id' => $this->class_id,
                'subject_type' => $this->subject_type,
                'full_marks' => $this->full_marks,
                'pass_marks' => $this->pass_marks,
            ]);
            session()->flash('message', 'নতুন বিষয় যোগ করা হয়েছে।');
        }

        $this->showModal = false;
        $this->loadData();
    }

    public function delete($id)
    {
        Subject::findOrFail($id)->delete();
        $this->loadData();
        session()->flash('message', 'বিষয় মুছে ফেলা হয়েছে।');
    }

    public function resetForm()
    {
        $this->reset(['subjectId', 'name', 'code', 'class_id', 'subject_type', 'isEditing']);
        $this->full_marks = 100;
        $this->pass_marks = 33;
        $this->subject_type = 1;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.subject-manager');
    }
}
