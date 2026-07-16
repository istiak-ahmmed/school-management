<?php

namespace App\Livewire\Admin\Communication;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\Notice;
use App\Models\SchoolClass;
use App\Models\Section;

#[Layout('admin.layouts.app')]
class NoticeManager extends Component
{
    use WithPagination, WithFileUploads;

    public $showForm = false;

    // Form fields
    public $title;
    public $body;
    public $category = 'general';
    public $attachment;
    public $is_pinned = false;
    public $is_published = true;
    
    // Targeting
    public $is_targeted = false;
    public $target_teachers = false;
    public $target_students = false;
    public $selected_class_id = null;
    public $selected_section_id = null;
    
    public $send_email = false;
    public $send_sms = false;

    public function getClassesProperty()
    {
        return SchoolClass::orderBy('numeric_order')->get();
    }

    public function getSectionsProperty()
    {
        if (!$this->selected_class_id) return [];
        return Section::where('class_id', $this->selected_class_id)->get();
    }

    public function resetForm()
    {
        $this->reset([
            'title', 'body', 'category', 'attachment', 'is_pinned', 'is_published',
            'is_targeted', 'target_teachers', 'target_students', 
            'selected_class_id', 'selected_section_id', 'send_email', 'send_sms'
        ]);
        $this->showForm = false;
    }

    public function saveNotice()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'category' => 'required|in:exam,holiday,fee,general',
            'attachment' => 'nullable|file|max:5120', // 5MB max
        ]);

        $audience = null;
        if ($this->is_targeted) {
            $audienceList = [];
            if ($this->target_teachers) {
                $audienceList[] = 'teachers';
            }
            if ($this->target_students) {
                if ($this->selected_section_id) {
                    $audienceList[] = 'section_' . $this->selected_section_id;
                } elseif ($this->selected_class_id) {
                    $audienceList[] = 'class_' . $this->selected_class_id;
                } else {
                    $audienceList[] = 'students';
                }
            }
            $audience = count($audienceList) > 0 ? $audienceList : null;
        }

        $path = null;
        if ($this->attachment) {
            $path = $this->attachment->store('notices', 'public');
        }

        $notice = Notice::create([
            'title' => $this->title,
            'body' => $this->body,
            'category' => $this->category,
            'audience' => $audience,
            'attachment_path' => $path,
            'is_pinned' => $this->is_pinned,
            'is_published' => $this->is_published,
            'is_sms_sent' => $this->is_targeted ? $this->send_sms : false,
            'is_email_sent' => $this->is_targeted ? $this->send_email : false,
            'created_by' => auth()->id(),
        ]);

        // Here we would dispatch jobs to send SMS/Email if requested
        if ($this->is_targeted && $this->send_sms) {
            // Mock: send sms logic
        }

        session()->flash('message', 'Notice created successfully.');
        $this->resetForm();
    }

    public function deleteNotice($id)
    {
        $notice = Notice::findOrFail($id);
        $notice->delete();
        session()->flash('message', 'Notice deleted.');
    }

    public function render()
    {
        $notices = Notice::latest()->paginate(10);
        return view('livewire.admin.communication.notice-manager', compact('notices'));
    }
}
