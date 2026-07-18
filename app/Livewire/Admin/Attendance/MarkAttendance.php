<?php

namespace App\Livewire\Admin\Attendance;

use Livewire\Component;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

#[Layout('admin.layouts.app')]
class MarkAttendance extends Component
{
    public $classes = [];
    public $sections = [];
    
    public $selectedClass = null;
    public $selectedSection = null;
    public $date;

    public $students = [];
    public $attendanceData = [];

    public function mount()
    {
        $this->date = Carbon::today()->format('Y-m-d');
        
        // If teacher is restricted, we'd load only their classes/sections.
        // Assuming admin can see all for now, or spatie role is implemented later.
        $this->classes = SchoolClass::where('is_active', 1)->get();
    }

    public function updatedSelectedClass($value)
    {
        $this->sections = Section::where('class_id', $value)
            ->where('is_active', 1)
            ->get();
        $this->selectedSection = null;
        $this->students = [];
    }

    public function loadStudents()
    {
        $this->validate([
            'selectedClass' => 'required',
            'selectedSection' => 'required',
            'date' => 'required|date|before_or_equal:today',
        ], [
            'selectedClass.required' => 'শ্রেণী নির্বাচন করুন',
            'selectedSection.required' => 'শাখা নির্বাচন করুন',
            'date.required' => 'তারিখ নির্বাচন করুন',
            'date.before_or_equal' => 'ভবিষ্যতের তারিখের হাজিরা নেওয়া যাবে না',
        ]);

        $this->students = Student::where('class_id', $this->selectedClass)
            ->where('section_id', $this->selectedSection)
            ->where('status', 1) // Active students
            ->get();

        $existingAttendance = StudentAttendance::where('class_id', $this->selectedClass)
            ->where('section_id', $this->selectedSection)
            ->where('date', $this->date)
            ->get()
            ->keyBy('student_id');

        $this->attendanceData = [];

        foreach ($this->students as $student) {
            if ($existingAttendance->has($student->id)) {
                $this->attendanceData[$student->id] = (string) $existingAttendance->get($student->id)->status;
            } else {
                $this->attendanceData[$student->id] = '1';
            }
        }
    }

    public function markAllPresent()
    {
        foreach ($this->students as $student) {
            $this->attendanceData[$student->id] = '1';
        }
    }

    public function saveAttendance()
    {
        $this->validate([
            'selectedClass' => 'required',
            'selectedSection' => 'required',
            'date' => 'required|date|before_or_equal:today',
        ]);

        if (empty($this->students)) {
            session()->flash('error', 'কোনো শিক্ষার্থী পাওয়া যায়নি।');
            return;
        }

        foreach ($this->students as $student) {
            $status = $this->attendanceData[$student->id] ?? 1;
            
            StudentAttendance::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'date' => $this->date,
                ],
                [
                    'class_id' => $this->selectedClass,
                    'section_id' => $this->selectedSection,
                    'status' => $status,
                    'recorded_by' => auth()->id(),
                ]
            );
        }

        session()->flash('success', 'সফলভাবে হাজিরা সংরক্ষণ করা হয়েছে।');
    }

    public function render()
    {
        return view('livewire.admin.attendance.mark-attendance');
    }
}
