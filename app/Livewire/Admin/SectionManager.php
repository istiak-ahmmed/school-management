<?php

namespace App\Livewire\Admin;

use App\Models\SchoolClass;
use App\Models\Section;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('admin.layouts.app')]
class SectionManager extends Component
{
    public $sections;
    public $classes;

    public $sectionId = null;

    #[Validate('required|string|max:50')]
    public $name = '';

    #[Validate('required|integer|min:1')]
    public $capacity = 50;

    #[Validate('required|exists:classes,id')]
    public $class_id = '';

    public bool $showModal = false;
    public bool $isEditing = false;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->sections = Section::with('schoolClass')->orderBy('class_id')->orderBy('name')->get();
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
        $section = Section::findOrFail($id);
        $this->sectionId = $section->id;
        $this->name = $section->name;
        $this->capacity = $section->capacity;
        $this->class_id = $section->class_id;
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->sectionId) {
            $section = Section::findOrFail($this->sectionId);
            $section->update([
                'name' => $this->name,
                'capacity' => $this->capacity,
                'class_id' => $this->class_id,
            ]);
            session()->flash('message', 'শাখা সফলভাবে আপডেট করা হয়েছে।');
        } else {
            Section::create([
                'name' => $this->name,
                'capacity' => $this->capacity,
                'class_id' => $this->class_id,
            ]);
            session()->flash('message', 'নতুন শাখা যোগ করা হয়েছে।');
        }

        $this->showModal = false;
        $this->loadData();
    }

    public function delete($id)
    {
        Section::findOrFail($id)->delete();
        $this->loadData();
        session()->flash('message', 'শাখা মুছে ফেলা হয়েছে।');
    }

    public function resetForm()
    {
        $this->reset(['sectionId', 'name', 'capacity', 'class_id', 'isEditing']);
        $this->capacity = 50; // default capacity
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.section-manager');
    }
}
