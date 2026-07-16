<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Enums\BloodGroup;
use Illuminate\Validation\Rule;

#[Layout('student.layouts.app')]
#[Title('আমার প্রোফাইল - শিক্ষার্থী')]
class MyProfile extends Component
{
    use WithFileUploads;

    public $photo;

    // General Info
    public $blood_group;
    public $student_phone;
    public $address_present;

    // Password Update
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $student = auth()->user()->student;
        $user = auth()->user();

        if ($student) {
            $this->blood_group = $student->blood_group?->value;
            $this->student_phone = $user->phone; // Assuming student phone is on user table
            $this->address_present = $student->address_present;
        }
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:2048', // 2MB Max
        ]);

        $student = auth()->user()->student;

        // Delete old photo if exists
        if ($student->photo_path && Storage::disk('public')->exists($student->photo_path)) {
            Storage::disk('public')->delete($student->photo_path);
        }

        $path = $this->photo->store('students/photos', 'public');
        
        $student->update([
            'photo_path' => $path
        ]);

        session()->flash('success', 'প্রোফাইল ছবি সফলভাবে আপডেট করা হয়েছে।');
    }

    public function updateGeneralInfo()
    {
        $this->validate([
            'blood_group' => ['nullable', Rule::enum(BloodGroup::class)],
            'student_phone' => 'nullable|string|max:15|unique:users,phone,' . auth()->id(),
            'address_present' => 'nullable|string',
        ]);

        $student = auth()->user()->student;
        $user = auth()->user();

        if ($student) {
            $student->update([
                'blood_group' => $this->blood_group,
                'address_present' => $this->address_present,
            ]);

            if ($this->student_phone !== $user->phone) {
                $user->update(['phone' => $this->student_phone]);
            }

            session()->flash('success', 'সাধারণ তথ্য সফলভাবে আপডেট করা হয়েছে।');
            $this->dispatch('close-modal', id: 'general-info-modal');
        }
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
        $student = auth()->user()->student;
        
        if (!$student) {
            return view('livewire.student.dashboard-empty');
        }

        // Eager load relationships if needed
        $student->load(['schoolClass', 'section']);

        return view('livewire.student.my-profile', [
            'student' => $student
        ]);
    }
}
