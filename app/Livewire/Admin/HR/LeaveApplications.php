<?php

namespace App\Livewire\Admin\HR;

use Livewire\Component;
use App\Models\LeaveApplication;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('admin.layouts.app')]
#[Title('Leave Applications')]
class LeaveApplications extends Component
{
    public $applications;
    public $reviewNote;
    public $selectedAppId;
    public $showModal = false;
    public $actionType = '';

    public function mount()
    {
        $this->loadApplications();
    }

    public function loadApplications()
    {
        $this->applications = LeaveApplication::with('leaveType')->latest()->get();
    }

    public function openReviewModal($id, $action)
    {
        $this->selectedAppId = $id;
        $this->actionType = $action; // 'approved' or 'rejected'
        $this->reviewNote = '';
        $this->showModal = true;
    }

    public function closeReviewModal()
    {
        $this->showModal = false;
        $this->selectedAppId = null;
        $this->actionType = '';
        $this->reviewNote = '';
    }

    public function submitReview()
    {
        $this->validate([
            'reviewNote' => 'nullable|string|max:255'
        ]);

        $app = LeaveApplication::find($this->selectedAppId);
        if ($app) {
            $app->status = $this->actionType;
            $app->review_note = $this->reviewNote;
            $app->reviewed_by = auth()->id();
            $app->reviewed_at = now();
            $app->save();

            session()->flash('success', 'Leave application has been ' . $this->actionType . '.');
        }

        $this->closeReviewModal();
        $this->loadApplications();
    }

    public function render()
    {
        return view('livewire.admin.h-r.leave-applications');
    }
}
