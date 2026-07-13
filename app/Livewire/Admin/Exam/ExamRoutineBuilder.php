<?php

namespace App\Livewire\Admin\Exam;

use App\Models\Exam;
use App\Models\ExamRoutine;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('admin.layouts.app')]
#[Title('পরীক্ষার রুটিন')]
class ExamRoutineBuilder extends Component
{
    public $exam_id = '';
    public $class_id = '';

    // Form inputs for a new routine item
    public $subject_id = '';
    public $exam_date;
    public $start_time;
    public $end_time;
    public $room = '';
    public $full_marks = 100;
    public $pass_marks = 33;
    public $selected_teachers = []; // Array of teacher IDs

    public $routines = [];
    public $exams = [];
    public $classes = [];
    public $subjects = [];
    public $teachers = [];

    protected $rules = [
        'subject_id' => 'required|exists:subjects,id',
        'exam_date' => 'required|date',
        'start_time' => 'required',
        'end_time' => 'required|after:start_time',
        'room' => 'nullable|string|max:50',
        'full_marks' => 'required|integer|min:1',
        'pass_marks' => 'required|integer|min:1|lte:full_marks',
        'selected_teachers' => 'nullable|array',
        'selected_teachers.*' => 'exists:teachers,id',
    ];

    public function mount()
    {
        $this->exams = Exam::orderBy('id', 'desc')->get();
        $this->classes = SchoolClass::all();
        $this->subjects = Subject::all();
        $this->teachers = Teacher::with('user')->where('status', 1)->get();
    }

    public function updatedExamId()
    {
        $this->loadRoutines();
    }

    public function updatedClassId()
    {
        $this->loadRoutines();
    }

    public function loadRoutines()
    {
        if ($this->exam_id && $this->class_id) {
            $this->routines = ExamRoutine::with(['subject', 'teachers.user'])
                ->where('exam_id', $this->exam_id)
                ->where('class_id', $this->class_id)
                ->orderBy('exam_date')
                ->orderBy('start_time')
                ->get();
        } else {
            $this->routines = [];
        }
    }

    public function addRoutine()
    {
        $this->validate();

        if (!$this->exam_id || !$this->class_id) {
            session()->flash('error', 'দয়া করে প্রথমে পরীক্ষা এবং শ্রেণী নির্বাচন করুন।');
            return;
        }

        // Check if subject already exists in this exam and class
        $exists = ExamRoutine::where('exam_id', $this->exam_id)
            ->where('class_id', $this->class_id)
            ->where('subject_id', $this->subject_id)
            ->exists();

        if ($exists) {
            $this->addError('subject_id', 'এই বিষয়ের রুটিন ইতিমধ্যে যোগ করা হয়েছে।');
            return;
        }

        $routine = ExamRoutine::create([
            'exam_id' => $this->exam_id,
            'class_id' => $this->class_id,
            'subject_id' => $this->subject_id,
            'exam_date' => $this->exam_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'room' => $this->room,
            'full_marks' => $this->full_marks,
            'pass_marks' => $this->pass_marks,
        ]);

        if (!empty($this->selected_teachers)) {
            $routine->teachers()->sync($this->selected_teachers);
        }

        $this->reset(['subject_id', 'exam_date', 'start_time', 'end_time', 'room', 'selected_teachers']);
        $this->full_marks = 100;
        $this->pass_marks = 33;
        
        $this->loadRoutines();
        session()->flash('success', 'রুটিন সফলভাবে যোগ করা হয়েছে!');
    }

    public function deleteRoutine($id)
    {
        ExamRoutine::findOrFail($id)->delete();
        $this->loadRoutines();
        session()->flash('success', 'রুটিন মুছে ফেলা হয়েছে!');
    }

    public function render()
    {
        return view('livewire.admin.exam.routine-builder');
    }
}
