<?php

namespace App\Livewire\Admin\HR;

use Livewire\Component;
use App\Models\LeaveType;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('admin.layouts.app')]
#[Title('Leave Types Management')]
class LeaveTypeManager extends Component
{
    public $leaveTypes;
    public $name;
    public $max_days_per_year = 10;
    public $is_paid = true;
    public $applicable_to = 'both';
    public $editId = null;

    public function mount()
    {
        $this->loadLeaveTypes();
    }

    public function loadLeaveTypes()
    {
        $this->leaveTypes = LeaveType::all();
    }

    public function resetFields()
    {
        $this->name = '';
        $this->max_days_per_year = 10;
        $this->is_paid = true;
        $this->applicable_to = 'both';
        $this->editId = null;
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'max_days_per_year' => 'required|integer|min:0',
            'applicable_to' => 'required|in:teacher,staff,both',
        ]);

        if ($this->editId) {
            $leaveType = LeaveType::find($this->editId);
            $leaveType->update([
                'name' => $this->name,
                'max_days_per_year' => $this->max_days_per_year,
                'is_paid' => $this->is_paid,
                'applicable_to' => $this->applicable_to,
            ]);
            session()->flash('success', 'ছুটির ধরন আপডেট করা হয়েছে।');
        } else {
            LeaveType::create([
                'name' => $this->name,
                'max_days_per_year' => $this->max_days_per_year,
                'is_paid' => $this->is_paid,
                'applicable_to' => $this->applicable_to,
            ]);
            session()->flash('success', 'নতুন ছুটির ধরন যুক্ত করা হয়েছে।');
        }

        $this->loadLeaveTypes();
        $this->resetFields();
    }

    public function edit($id)
    {
        $leaveType = LeaveType::find($id);
        if ($leaveType) {
            $this->editId = $leaveType->id;
            $this->name = $leaveType->name;
            $this->max_days_per_year = $leaveType->max_days_per_year;
            $this->is_paid = $leaveType->is_paid;
            $this->applicable_to = $leaveType->applicable_to;
        }
    }

    public function delete($id)
    {
        $leaveType = LeaveType::find($id);
        if ($leaveType) {
            $leaveType->delete();
            session()->flash('success', 'ছুটির ধরনটি ডিলিট করা হয়েছে।');
            $this->loadLeaveTypes();
        }
    }

    public function render()
    {
        return view('livewire.admin.h-r.leave-type-manager');
    }
}
