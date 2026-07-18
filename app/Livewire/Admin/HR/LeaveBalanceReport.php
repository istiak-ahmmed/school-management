<?php

namespace App\Livewire\Admin\HR;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Teacher;
use App\Models\Staff;
use App\Models\LeaveType;
use App\Models\LeaveApplication;
use Carbon\Carbon;

#[Layout('admin.layouts.app')]
#[Title('Leave Balance Report')]
class LeaveBalanceReport extends Component
{
    public $employeeType = 'all';

    public function render()
    {
        $currentYear = Carbon::now()->year;
        
        $leaveTypes = LeaveType::all();
        $teachers = collect();
        $staffs = collect();

        if ($this->employeeType === 'all' || $this->employeeType === 'teacher') {
            $teachers = Teacher::with('user')->where('status', 1)->get()->map(function($teacher) {
                $teacher->employee_type = 'teacher';
                return $teacher;
            });
        }

        if ($this->employeeType === 'all' || $this->employeeType === 'staff') {
            $staffs = Staff::with('user')->where('status', 1)->get()->map(function($staff) {
                $staff->employee_type = 'staff';
                return $staff;
            });
        }

        $employees = $teachers->merge($staffs)->sortBy(function($emp) {
            return $emp->user->name ?? '';
        });

        // Pre-fetch all approved leaves for current year
        $approvedLeaves = LeaveApplication::where('status', 'approved')
            ->whereYear('from_date', $currentYear)
            ->get();

        $reportData = [];

        foreach ($employees as $emp) {
            $empLeaves = $approvedLeaves->where('employee_type', $emp->employee_type)
                                        ->where('employee_id', $emp->id);
            
            $balances = [];
            foreach ($leaveTypes as $type) {
                if ($type->applicable_to === 'both' || $type->applicable_to === $emp->employee_type) {
                    $enjoyed = $empLeaves->where('leave_type_id', $type->id)->sum('total_days');
                    $remaining = max(0, $type->max_days_per_year - $enjoyed);
                    
                    $balances[$type->id] = [
                        'total' => $type->max_days_per_year,
                        'enjoyed' => $enjoyed,
                        'remaining' => $remaining
                    ];
                }
            }

            $reportData[] = [
                'name' => $emp->user->name ?? 'Unknown',
                'employee_id' => $emp->employee_id,
                'type' => $emp->employee_type,
                'balances' => $balances
            ];
        }

        return view('livewire.admin.h-r.leave-balance-report', [
            'leaveTypes' => $leaveTypes,
            'reportData' => $reportData,
        ]);
    }
}
