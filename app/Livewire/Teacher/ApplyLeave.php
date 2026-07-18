<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use App\Models\LeaveType;
use App\Models\LeaveApplication;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Carbon\Carbon;

#[Layout('teacher.layouts.app')]
#[Title('Apply Leave')]
class ApplyLeave extends Component
{
    public $leaveTypes;
    public $leave_type_id;
    public $from_date;
    public $to_date;
    public $is_half_day = false;
    public $reason;

    public $applications = [];
    public $leaveBalances = [];

    public function mount()
    {
        $this->leaveTypes = LeaveType::whereIn('applicable_to', ['teacher', 'both'])->get();
        $this->loadApplications();
        $this->calculateLeaveBalances();
    }

    public function calculateLeaveBalances()
    {
        $teacher = auth()->user()->teacher;
        if (!$teacher) return;

        $currentYear = Carbon::now()->year;

        // Get all approved leaves for this teacher in the current year
        $approvedLeaves = LeaveApplication::where('employee_type', 'teacher')
            ->where('employee_id', $teacher->id)
            ->where('status', 'approved')
            ->whereYear('from_date', $currentYear)
            ->get();

        $this->leaveBalances = [];
        foreach ($this->leaveTypes as $type) {
            $enjoyed = $approvedLeaves->where('leave_type_id', $type->id)->sum('total_days');
            $remaining = max(0, $type->max_days_per_year - $enjoyed);
            
            $this->leaveBalances[] = [
                'type' => $type,
                'total' => $type->max_days_per_year,
                'enjoyed' => $enjoyed,
                'remaining' => $remaining
            ];
        }
    }

    public function loadApplications()
    {
        // Assuming teacher's user has a teacher profile
        $teacher = auth()->user()->teacher; 
        if ($teacher) {
            $this->applications = LeaveApplication::with('leaveType')
                ->where('employee_type', 'teacher')
                ->where('employee_id', $teacher->id)
                ->latest()
                ->get();
        }
    }

    public function updatedIsHalfDay($value)
    {
        if ($value && $this->from_date) {
            $this->to_date = $this->from_date;
        }
    }

    public function updatedFromDate($value)
    {
        if ($this->is_half_day) {
            $this->to_date = $value;
        }
    }

    public function submit()
    {
        $this->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'from_date' => 'required|date',
            'to_date' => $this->is_half_day ? 'required|date|same:from_date' : 'required|date|after_or_equal:from_date',
            'reason' => 'required|string',
        ]);

        $teacher = auth()->user()->teacher;
        if (!$teacher) {
            session()->flash('error', 'আপনার টিচার প্রোফাইল খুঁজে পাওয়া যায়নি।');
            return;
        }

        $totalDays = 0;
        if ($this->is_half_day) {
            $totalDays = 0.5;
        } else {
            $from = Carbon::parse($this->from_date);
            $to = Carbon::parse($this->to_date);
            $totalDays = $from->diffInDays($to) + 1; // Inclusive
        }

        LeaveApplication::create([
            'employee_type' => 'teacher',
            'employee_id' => $teacher->id,
            'leave_type_id' => $this->leave_type_id,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'total_days' => $totalDays,
            'is_half_day' => $this->is_half_day,
            'reason' => $this->reason,
            'status' => 'pending',
        ]);

        session()->flash('success', 'আপনার ছুটির আবেদন সফলভাবে জমা দেওয়া হয়েছে।');
        
        $this->reset(['leave_type_id', 'from_date', 'to_date', 'is_half_day', 'reason']);
        $this->loadApplications();
    }

    public function render()
    {
        return view('livewire.teacher.apply-leave');
    }
}
