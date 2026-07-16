<?php

namespace App\Livewire\Admin\Reports;

use Spatie\Activitylog\Models\Activity;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layouts.app')]
#[Title('অডিট লগ (System Audit Log)')]
class AuditLogReport extends Component
{
    use WithPagination;

    public $search = '';
    public $event = ''; // created, updated, deleted
    public $log_name = '';
    
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingEvent()
    {
        $this->resetPage();
    }

    public function getLogs()
    {
        $query = Activity::with('causer');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                  ->orWhere('subject_type', 'like', '%' . $this->search . '%')
                  ->orWhereHasMorph('causer', ['App\Models\User'], function ($userQuery) {
                      $userQuery->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->event) {
            $query->where('event', $this->event);
        }

        if ($this->log_name) {
            $query->where('log_name', $this->log_name);
        }

        return $query->latest()->paginate(20);
    }

    public function render()
    {
        return view('livewire.admin.reports.audit-log-report', [
            'logs' => $this->getLogs(),
            'logNames' => Activity::select('log_name')->distinct()->pluck('log_name')
        ]);
    }
}
