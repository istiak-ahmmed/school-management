<?php

namespace App\Livewire\Admin;

use App\Models\AcademicYear;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Livewire\Traits\Sortable;

#[Layout('admin.layouts.app')]
class AcademicYearManager extends Component
{
    use Sortable;

    public $years;

    public $yearId = null;

    #[Validate('required|string|max:20')]
    public $name = '';

    #[Validate('required|date')]
    public $start_date = '';

    #[Validate('required|date|after_or_equal:start_date')]
    public $end_date = '';

    public $is_current = false;

    public bool $isEditing = false;

    public function mount()
    {
        $this->loadYears();
    }

    public function loadYears()
    {
        $this->years = AcademicYear::orderBy($this->sortField, $this->sortDirection)->get();
    }

    public function save()
    {
        $this->validate();

        if ($this->yearId) {
            $year = AcademicYear::findOrFail($this->yearId);
            $year->update([
                'name' => $this->name,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'is_current' => $this->is_current,
            ]);
        } else {
            AcademicYear::create([
                'name' => $this->name,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'is_current' => $this->is_current,
            ]);
        }

        $this->resetForm();
        $this->loadYears();
        session()->flash('message', 'শিক্ষাবর্ষ সফলভাবে সংরক্ষিত হয়েছে।');
    }

    public function edit($id)
    {
        $year = AcademicYear::findOrFail($id);
        $this->yearId = $year->id;
        $this->name = $year->name;
        $this->start_date = $year->start_date->format('Y-m-d');
        $this->end_date = $year->end_date->format('Y-m-d');
        $this->is_current = $year->is_current;
        
        $this->isEditing = true;
    }

    public function delete($id)
    {
        AcademicYear::findOrFail($id)->delete();
        $this->loadYears();
        session()->flash('message', 'শিক্ষাবর্ষ মুছে ফেলা হয়েছে।');
    }

    public function toggleCurrent($id)
    {
        $year = AcademicYear::findOrFail($id);
        
        if (!$year->is_current) {
            $year->update(['is_current' => true]);
            $this->loadYears();
            session()->flash('message', 'বর্তমান শিক্ষাবর্ষ সেট করা হয়েছে।');
        }
    }

    public function resetForm()
    {
        $this->reset(['yearId', 'name', 'start_date', 'end_date', 'is_current', 'isEditing']);
    }

    public function render()
    {
        return view('livewire.admin.academic-year-manager');
    }
}
