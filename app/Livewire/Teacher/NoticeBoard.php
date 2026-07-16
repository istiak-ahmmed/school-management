<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Notice;
use Illuminate\Support\Facades\DB;

#[Layout('teacher.layouts.app')]
#[Title('নোটিশ বোর্ড - শিক্ষক পোর্টাল')]
class NoticeBoard extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingCategory() { $this->resetPage(); }

    public function render()
    {
        $teacher = auth()->user()->teacher;
        
        if (!$teacher) {
            return view('livewire.teacher.dashboard-empty');
        }

        // Get class IDs assigned to this teacher
        $assignedClassIds = DB::table('teacher_subjects')
            ->where('teacher_id', $teacher->id)
            ->pluck('class_id')
            ->unique()
            ->toArray();

        $query = Notice::where('is_published', true)
            ->where(function ($q) use ($assignedClassIds) {
                // Public notices (no audience restriction)
                $q->whereNull('audience')
                  // Notices for all teachers
                  ->orWhereJsonContains('audience', 'teachers');
                // Notices targeted at specific classes this teacher teaches
                foreach ($assignedClassIds as $classId) {
                    $q->orWhereJsonContains('audience', 'class_' . $classId);
                }
            });

        if ($this->search) {
            $query->where(function ($q) {
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

        return view('livewire.teacher.notice-board', [
            'notices' => $notices,
        ]);
    }
}
