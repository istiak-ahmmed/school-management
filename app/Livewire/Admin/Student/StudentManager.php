<?php

namespace App\Livewire\Admin\Student;

use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Livewire\Traits\Sortable;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentManager extends Component
{
    use Sortable, WithPagination, \App\Traits\WithExporting;

    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'class')]
    public string $filterClass = '';

    #[Url(as: 'section')]
    public string $filterSection = '';

    #[Url(as: 'status')]
    public string $filterStatus = '';

    // Bulk selection
    /** @var array<int> */
    public array $selectedIds = [];
    public bool $selectAll    = false;

    public function updatedSearch(): void
    {
        $this->resetPage();
        $this->selectedIds = [];
    }

    public function updatedFilterClass(): void
    {
        $this->resetPage();
        $this->filterSection = '';
        $this->selectedIds   = [];
    }

    public function updatedFilterSection(): void
    {
        $this->resetPage();
        $this->selectedIds = [];
    }

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $this->selectedIds = $this->students->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedIds = [];
        }
    }

    #[Computed]
    public function students()
    {
        return Student::with(['user', 'schoolClass', 'section', 'guardians'])
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->whereHas('user', fn($q) => $q->where('name', 'like', "%{$this->search}%"))
                  ->orWhere('admission_no', 'like', "%{$this->search}%");
            }))
            ->when($this->filterClass, fn($q) => $q->where('class_id', $this->filterClass))
            ->when($this->filterSection, fn($q) => $q->where('section_id', $this->filterSection))
            ->when($this->filterStatus !== '', fn($q) => $q->where('status', $this->filterStatus))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(20);
    }

    #[Computed]
    public function classes()
    {
        return SchoolClass::orderBy('numeric_order')->get();
    }

    #[Computed]
    public function sections()
    {
        if (! $this->filterClass) {
            return collect();
        }

        return Section::where('class_id', $this->filterClass)->orderBy('name')->get();
    }

    /**
     * Deactivate a single student.
     */
    public function deactivate(int $id): void
    {
        Student::findOrFail($id)->update(['status' => 0]);
        $this->dispatch('notify', message: 'শিক্ষার্থী নিষ্ক্রিয় করা হয়েছে।');
        unset($this->students);
    }

    /**
     * Bulk deactivate selected students.
     */
    public function bulkDeactivate(): void
    {
        if (empty($this->selectedIds)) {
            return;
        }

        Student::whereIn('id', $this->selectedIds)->update(['status' => 0]);
        $count             = count($this->selectedIds);
        $this->selectedIds = [];
        $this->selectAll   = false;
        $this->dispatch('notify', message: "{$count}জন শিক্ষার্থী নিষ্ক্রিয় করা হয়েছে।");
        unset($this->students);
    }

    public function getExportHeaders(): array
    {
        return [
            'ভর্তি নম্বর', 'নাম', 'শ্রেণী', 'শাখা',
            'লিঙ্গ', 'জন্ম তারিখ', 'স্ট্যাটাস',
            'অভিভাবক', 'অভিভাবক ফোন', 'ইমেইল',
        ];
    }

    public function getExportData(): array
    {
        $students = Student::with(['user', 'schoolClass', 'section', 'guardians'])
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->whereHas('user', fn($q) => $q->where('name', 'like', "%{$this->search}%"))
                  ->orWhere('admission_no', 'like', "%{$this->search}%");
            }))
            ->when($this->filterClass, fn($q) => $q->where('class_id', $this->filterClass))
            ->when($this->filterSection, fn($q) => $q->where('section_id', $this->filterSection))
            ->when($this->filterStatus !== '', fn($q) => $q->where('status', $this->filterStatus))
            ->orderBy($this->sortField, $this->sortDirection)
            ->get();

        $data = [];
        foreach ($students as $student) {
            $guardian = $student->guardians->first();
            $data[] = [
                $student->admission_no,
                $student->user?->name,
                $student->schoolClass?->name,
                $student->section?->name,
                match($student->gender) {'male'=>'ছেলে','female'=>'মেয়ে','other'=>'অন্যান্য', default=>''},
                $student->date_birth?->format('d/m/Y'),
                $student->status_label,
                $guardian?->name,
                $guardian?->phone,
                $student->user?->email,
            ];
        }

        return $data;
    }

    public function render()
    {
        return view('livewire.admin.student.student-manager')
            ->layout('admin.layouts.app');
    }
}
