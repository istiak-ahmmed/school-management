<?php

namespace App\Livewire\Admin\HR;

use Livewire\Component;
use App\Models\Staff;
use App\Models\Teacher;
use App\Models\EmployeeAttendance;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('admin.layouts.app')]
#[Title('Mark Staff Attendance')]
class MarkStaffAttendance extends Component
{
    public $employeeType = 'teacher';
    public $date;
    public $employees = [];
    public $attendanceData = [];

    public function mount()
    {
        $this->date = Carbon::today()->format('Y-m-d');
        $this->loadEmployees();
    }

    public function updatedEmployeeType()
    {
        $this->loadEmployees();
    }

    public function updatedDate()
    {
        $this->loadEmployees();
    }

    public function loadEmployees()
    {
        if ($this->employeeType === 'teacher') {
            $this->employees = Teacher::where('status', 1)->get();
        } else {
            $this->employees = Staff::where('status', 1)->get();
        }

        $existingAttendance = EmployeeAttendance::where('employee_type', $this->employeeType)
            ->where('date', $this->date)
            ->get()
            ->keyBy('employee_id');

        $this->attendanceData = [];

        foreach ($this->employees as $emp) {
            if ($existingAttendance->has($emp->id)) {
                $this->attendanceData[$emp->id] = $existingAttendance->get($emp->id)->status;
            } else {
                $this->attendanceData[$emp->id] = 1; // Default to Present
            }
        }
    }

    public function markAllPresent()
    {
        foreach ($this->employees as $emp) {
            $this->attendanceData[$emp->id] = 1;
        }
    }

    public function saveAttendance()
    {
        $this->validate([
            'employeeType' => 'required|in:teacher,staff',
            'date' => 'required|date|before_or_equal:today',
        ], [
            'date.before_or_equal' => 'ভবিষ্যতের তারিখের হাজিরা নেওয়া যাবে না',
        ]);

        if (empty($this->employees)) {
            session()->flash('error', 'কোনো কর্মী পাওয়া যায়নি।');
            return;
        }

        foreach ($this->employees as $emp) {
            $status = $this->attendanceData[$emp->id] ?? 1;
            
            EmployeeAttendance::updateOrCreate(
                [
                    'employee_type' => $this->employeeType,
                    'employee_id' => $emp->id,
                    'date' => $this->date,
                ],
                [
                    'status' => $status,
                    'recorded_by' => auth()->id(),
                ]
            );
        }

        session()->flash('success', 'সফলভাবে হাজিরা সংরক্ষণ করা হয়েছে।');
    }

    public function render()
    {
        return view('livewire.admin.h-r.mark-staff-attendance');
    }
}
