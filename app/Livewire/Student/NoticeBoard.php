<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Notice;

#[Layout('student.layouts.app')]
#[Title('নোটিশ বোর্ড - শিক্ষার্থী')]
class NoticeBoard extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function render()
    {
        $student = auth()->user()->student;
        
        if (!$student) {
            return view('livewire.student.dashboard-empty');
        }

        $query = Notice::where('is_published', true)
            ->where(function($q) use ($student) {
                $q->whereNull('audience')
                  ->orWhereJsonContains('audience', 'students')
                  ->orWhereJsonContains('audience', 'class_' . $student->class_id)
                  ->orWhereJsonContains('audience', 'section_' . $student->section_id);
            });

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('body', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->category) {
            $query->where('category', $this->category);
        }

        $notices = $query->orderBy('is_pinned', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);

        return view('livewire.student.notice-board', [
            'notices' => $notices,
        ]);
    }
}
