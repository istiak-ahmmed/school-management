<?php

namespace App\Livewire\Admin\Admission;

use App\Actions\Admission\ReviewAdmissionApplicationAction;
use App\Models\AdmissionApplication;
use App\Models\SchoolClass;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Livewire\Traits\Sortable;
use Livewire\WithPagination;

class ApplicationManager extends Component
{
    use Sortable;

    use WithPagination;

    #[Url(as: 'status')]
    public string $filterStatus = '';

    #[Url(as: 'class')]
    public string $filterClass = '';

    #[Url(as: 'q')]
    public string $search = '';

    // Detail view / review
    public ?int $viewingId     = null;
    public bool $showReview    = false;
    public string $reviewAction = ''; // 'accept' | 'reject'
    public string $reviewNote   = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatedFilterClass(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function applications()
    {
        return AdmissionApplication::with(['applyingForClass', 'academicYear', 'reviewer'])
            ->when($this->search, fn($q) => $q->where('applicant_name', 'like', "%{$this->search}%")
                ->orWhere('application_no', 'like', "%{$this->search}%")
                ->orWhere('guardian_phone', 'like', "%{$this->search}%"))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterClass, fn($q) => $q->where('applying_for_class_id', $this->filterClass))
            ->latest('submitted_at')
            ->paginate(15);
    }

    #[Computed]
    public function viewingApplication(): ?AdmissionApplication
    {
        if (! $this->viewingId) {
            return null;
        }

        return AdmissionApplication::with(['applyingForClass', 'academicYear', 'reviewer'])->find($this->viewingId);
    }

    #[Computed]
    public function classes()
    {
        return SchoolClass::orderBy('numeric_order')->get();
    }

    /**
     * Open the detail view for an application.
     */
    public function viewApplication(int $id): void
    {
        $this->viewingId   = $id;
        $this->showReview  = false;
        $this->reviewAction = '';
        $this->reviewNote  = '';
    }

    /**
     * Close the detail view.
     */
    public function closeDetail(): void
    {
        $this->viewingId    = null;
        $this->showReview   = false;
        $this->reviewAction = '';
        $this->reviewNote   = '';
    }

    /**
     * Prepare the review form for accept or reject.
     */
    public function prepareReview(string $action): void
    {
        $this->reviewAction = $action;
        $this->showReview   = true;
        $this->reviewNote   = '';
    }

    /**
     * Mark application as under review.
     */
    public function markUnderReview(int $id): void
    {
        AdmissionApplication::findOrFail($id)->update([
            'status'      => 2,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);
        $this->dispatch('notify', message: 'আবেদনটি পর্যালোচনাধীন হিসেবে চিহ্নিত করা হয়েছে।');
        unset($this->applications);
    }

    /**
     * Submit the review decision (accept or reject).
     */
    public function submitReview(ReviewAdmissionApplicationAction $action): void
    {
        $this->validate([
            'reviewNote' => $this->reviewAction === 'reject' ? 'required|min:10' : 'nullable',
        ], [
            'reviewNote.required' => 'প্রত্যাখ্যানের কারণ লিখুন (কমপক্ষে ১০ অক্ষর)।',
        ]);

        $application = AdmissionApplication::findOrFail($this->viewingId);

        if ($this->reviewAction === 'accept') {
            $action->accept($application, auth()->id());
            $message = 'আবেদন গৃহীত হয়েছে এবং ছাত্র ভর্তি সম্পন্ন হয়েছে।';
        } else {
            $action->reject($application, auth()->id(), $this->reviewNote);
            $message = 'আবেদন প্রত্যাখ্যান করা হয়েছে।';
        }

        $this->closeDetail();
        $this->dispatch('notify', message: $message, type: 'success');
        unset($this->applications);
    }

    public function render()
    {
        return view('livewire.admin.admission.application-manager')
            ->layout('admin.layouts.app');
    }
}
