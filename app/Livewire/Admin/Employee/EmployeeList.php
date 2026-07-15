<?php

namespace App\Livewire\Admin\Employee;

use App\Models\User;
use Livewire\Component;
use App\Livewire\Traits\Sortable;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('admin.layouts.app')]
#[Title('কর্মচারী তালিকা')]
class EmployeeList extends Component
{
    use Sortable;

    use WithPagination;

    public $search = '';
    public $roleFilter = ''; // teacher or staff
    public $statusFilter = ''; // 1=active, 0=inactive

    protected $queryString = [
        'search' => ['except' => ''],
        'roleFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::with(['teacher', 'staff'])
            ->whereIn('user_type', ['teacher', 'staff']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhereHas('teacher', function($t) {
                      $t->where('employee_id', 'like', '%' . $this->search . '%')
                        ->orWhere('designation', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('staff', function($s) {
                      $s->where('employee_id', 'like', '%' . $this->search . '%')
                        ->orWhere('designation', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->roleFilter) {
            $query->where('user_type', $this->roleFilter);
        }

        if ($this->statusFilter !== '') {
            $query->where('is_active', $this->statusFilter);
        }

        $employees = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        return view('livewire.admin.employee.employee-list', compact('employees'));
    }
}
