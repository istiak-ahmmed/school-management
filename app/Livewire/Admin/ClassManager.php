<?php

namespace App\Livewire\Admin;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Livewire\Traits\Sortable;

#[Layout('admin.layouts.app')]
class ClassManager extends Component
{
    use Sortable;

    

    public $classes;
    public $academicYears;

    public $classId = null;

    #[Validate('required|string|max:100')]
    public $name = '';

    #[Validate('required|integer')]
    public $numeric_order = '';

    #[Validate('nullable|exists:academic_years,id')]
    public $academic_year_id = null;

    public bool $showModal = false;
    public bool $isEditing = false;

    public function mount()
    {
        $this->sortDirection = 'asc';
        $this->loadData();
    }

    public function loadData()
    {
        $this->classes = SchoolClass::with('academicYear')->orderBy($this->sortField, $this->sortDirection)->get();
        $this->academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetValidation();
        $cls = SchoolClass::findOrFail($id);
        $this->classId = $cls->id;
        $this->name = $cls->name;
        $this->numeric_order = $cls->numeric_order;
        $this->academic_year_id = $cls->academic_year_id;
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->classId) {
            $cls = SchoolClass::findOrFail($this->classId);
            $cls->update([
                'name' => $this->name,
                'numeric_order' => $this->numeric_order,
                'academic_year_id' => $this->academic_year_id,
            ]);
            session()->flash('message', 'শ্রেণী সফলভাবে আপডেট করা হয়েছে।');
        } else {
            SchoolClass::create([
                'name' => $this->name,
                'numeric_order' => $this->numeric_order,
                'academic_year_id' => $this->academic_year_id,
            ]);
            session()->flash('message', 'নতুন শ্রেণী যোগ করা হয়েছে।');
        }

        $this->showModal = false;
        $this->loadData();
    }

    public function delete($id)
    {
        SchoolClass::findOrFail($id)->delete();
        $this->loadData();
        session()->flash('message', 'শ্রেণী মুছে ফেলা হয়েছে।');
    }

    public function resetForm()
    {
        $this->reset(['classId', 'name', 'numeric_order', 'academic_year_id', 'isEditing']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.class-manager');
    }
}
