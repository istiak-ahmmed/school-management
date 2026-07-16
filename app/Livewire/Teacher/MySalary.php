<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\SalaryPayment;
use App\Enums\EmployeeType;

#[Layout('teacher.layouts.app')]
#[Title('বেতন স্লিপ - শিক্ষক পোর্টাল')]
class MySalary extends Component
{
    public function render()
    {
        $teacher = auth()->user()->teacher;
        
        if (!$teacher) {
            return view('livewire.teacher.dashboard-empty');
        }

        $salaries = SalaryPayment::with(['paymentMethod'])
            ->where('employee_id', $teacher->id)
            ->where('employee_type', EmployeeType::Teacher->value)
            ->orderBy('month_year', 'desc')
            ->get();

        $totalPaid = $salaries->where('status.value', 0)->sum('net_salary');
        $pendingCount = $salaries->where('status.value', 1)->count();

        return view('livewire.teacher.my-salary', [
            'salaries' => $salaries,
            'totalPaid' => $totalPaid,
            'pendingCount' => $pendingCount,
        ]);
    }
}
