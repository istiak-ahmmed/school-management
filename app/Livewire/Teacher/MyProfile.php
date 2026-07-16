<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

#[Layout('teacher.layouts.app')]
#[Title('আমার প্রোফাইল - শিক্ষক পোর্টাল')]
class MyProfile extends Component
{
    use WithFileUploads;

    public $photo;

    // General Info
    public $phone;

    // Password Update
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $user = auth()->user();
        if ($user) {
            $this->phone = $user->phone;
        }
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:2048', // 2MB Max
        ]);

        $teacher = auth()->user()->teacher;

        if ($teacher) {
            // Delete old photo if exists
            if ($teacher->photo_path && Storage::disk('public')->exists($teacher->photo_path)) {
                Storage::disk('public')->delete($teacher->photo_path);
            }

            $path = $this->photo->store('teachers/photos', 'public');
            
            $teacher->update([
                'photo_path' => $path
            ]);

            session()->flash('success', 'প্রোফাইল ছবি সফলভাবে আপডেট করা হয়েছে।');
        }
    }

    public function updateGeneralInfo()
    {
        $this->validate([
            'phone' => 'nullable|string|max:15|unique:users,phone,' . auth()->id(),
        ]);

        $user = auth()->user();
        
        if ($this->phone !== $user->phone) {
            $user->update(['phone' => $this->phone]);
            session()->flash('success', 'সাধারণ তথ্য সফলভাবে আপডেট করা হয়েছে।');
        }

        $this->dispatch('close-modal', id: 'general-info-modal');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'বর্তমান পাসওয়ার্ড প্রদান করুন।',
            'current_password.current_password' => 'বর্তমান পাসওয়ার্ড সঠিক নয়।',
            'new_password.required' => 'নতুন পাসওয়ার্ড প্রদান করুন।',
            'new_password.min' => 'পাসওয়ার্ড কমপক্ষে ৮ অক্ষরের হতে হবে।',
            'new_password.confirmed' => 'নতুন পাসওয়ার্ড নিশ্চিতকরণ মেলেনি।',
        ]);

        auth()->user()->update([
            'password' => Hash::make($this->new_password)
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        
        session()->flash('success', 'পাসওয়ার্ড সফলভাবে পরিবর্তন করা হয়েছে।');
        $this->dispatch('close-modal', id: 'password-modal');
    }

    public function render()
    {
        $teacher = auth()->user()->teacher;
        
        if (!$teacher) {
            return view('livewire.teacher.dashboard-empty');
        }

        // Subjects this teacher teaches
        $teachingAssignments = \Illuminate\Support\Facades\DB::table('teacher_subjects')
            ->join('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
            ->join('classes', 'teacher_subjects.class_id', '=', 'classes.id')
            ->where('teacher_subjects.teacher_id', $teacher->id)
            ->select('subjects.name as subject_name', 'classes.name as class_name', 'teacher_subjects.section_id')
            ->get();

        // Form teacher sections
        $formTeacherSections = \App\Models\Section::with('schoolClass')
            ->where('teacher_id', auth()->id())
            ->get();

        return view('livewire.teacher.my-profile', [
            'teacher' => $teacher,
            'teachingAssignments' => $teachingAssignments,
            'formTeacherSections' => $formTeacherSections,
        ]);
    }
}
