<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentAttendance;
use Carbon\Carbon;

#[Layout('teacher.layouts.app')]
#[Title('হাজিরা গ্রহণ - শিক্ষক পোর্টাল')]
class AttendanceManager extends Component
{
    public $formSections = [];
    public $selectedSectionId = null;
    public $date;
    public $students = [];
    public $attendanceData = [];
    public bool $isLoaded = false;
    public $message = null;
    public $messageType = null;

    public function mount()
    {
        $user = auth()->user();
        $this->date = Carbon::today()->format('Y-m-d');

        // Gate: Only Form Teachers can access this page
        $this->formSections = Section::with('schoolClass')
            ->where('teacher_id', $user->id)
            ->get();

        if ($this->formSections->isEmpty()) {
            // Not a form teacher – redirect or show an error
            return;
        }

        // Auto-select the first section
        $this->selectedSectionId = $this->formSections->first()->id;
        $this->loadStudents();
    }

    public function updatedSelectedSectionId()
    {
        $this->isLoaded = false;
        $this->students = [];
        $this->attendanceData = [];
        $this->loadStudents();
    }

    public function updatedDate()
    {
        $this->isLoaded = false;
        $this->loadStudents();
    }

    public function loadStudents()
    {
        if (!$this->selectedSectionId) return;

        $section = Section::find($this->selectedSectionId);
        if (!$section) return;

        $this->students = Student::where('section_id', $this->selectedSectionId)
            ->where('status', 1)
            ->orderBy('roll_no')
            ->get();

        // Load existing attendance for this date
        $existing = StudentAttendance::where('section_id', $this->selectedSectionId)
            ->where('date', $this->date)
            ->get()
            ->keyBy('student_id');

        $this->attendanceData = [];
        foreach ($this->students as $student) {
            $this->attendanceData[$student->id] = $existing->has($student->id)
                ? $existing->get($student->id)->status
                : 'present';
        }

        $this->isLoaded = true;
    }

    public function markAll(string $status)
    {
        foreach ($this->students as $student) {
            $this->attendanceData[$student->id] = $status;
        }
    }

    public function saveAttendance()
    {
        if (!$this->selectedSectionId || empty($this->students)) {
            $this->message = 'কোনো শিক্ষার্থী পাওয়া যায়নি।';
            $this->messageType = 'error';
            return;
        }

        if (Carbon::parse($this->date)->isAfter(Carbon::today())) {
            $this->message = 'ভবিষ্যতের তারিখের হাজিরা নেওয়া যাবে না।';
            $this->messageType = 'error';
            return;
        }

        $section = Section::find($this->selectedSectionId);

        foreach ($this->students as $student) {
            $status = $this->attendanceData[$student->id] ?? 'present';

            StudentAttendance::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'date' => $this->date,
                ],
                [
                    'class_id' => $student->class_id,
                    'section_id' => $this->selectedSectionId,
                    'status' => $status,
                    'recorded_by' => auth()->id(),
                ]
            );
        }

        $this->message = 'সফলভাবে হাজিরা সংরক্ষণ করা হয়েছে!';
        $this->messageType = 'success';
        $this->loadStudents(); // Refresh to reflect saved data
    }

    public function render()
    {
        return view('livewire.teacher.attendance-manager');
    }
}
