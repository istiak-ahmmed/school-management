<?php

namespace App\Livewire\Admin\Academic;

use App\Models\AcademicYear;
use App\Models\ClassRoutine;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\Component;

#[Layout('admin.layouts.app')]
#[Title('ক্লাস রুটিন বিল্ডার')]
class ClassRoutineBuilder extends Component
{
    public $class_id = '';
    public $section_id = '';
    
    public $classes = [];
    public $sections = [];
    public $subjects = [];
    public $teachers = [];
    
    // Configurable Max Periods
    public $max_periods = 8;

    // Modal state for adding/editing a routine cell
    public $showModal = false;
    public $day_of_week = '';
    public $period_no = '';
    public $subject_id = '';
    public $teacher_id = '';
    public $start_time = '';
    public $end_time = '';
    public $room = '';
    
    // Enhancements
    public $is_break = false;
    public $is_combined = false;
    public $note = '';
    public $additional_sections = []; // Array of section IDs
    
    public $all_sections = []; // To populate the multi-select for combined classes
    
    public $routineId = null; // For editing

    public $days = [
        0 => 'রবিবার',
        1 => 'সোমবার',
        2 => 'মঙ্গলবার',
        3 => 'বুধবার',
        4 => 'বৃহস্পতিবার',
        6 => 'শনিবার', // Skipping Friday (5) as weekend
    ];

    #[Computed]
    public function periods()
    {
        return range(1, max(1, $this->max_periods));
    }

    protected function rules()
    {
        return [
            'subject_id' => $this->is_break ? 'nullable' : 'required|exists:subjects,id',
            'teacher_id' => $this->is_break ? 'nullable' : 'required|exists:teachers,id',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'room' => 'nullable|string|max:50',
            'note' => 'nullable|string|max:255',
            'additional_sections' => 'array',
        ];
    }

    public function mount()
    {
        $this->classes = SchoolClass::all();
        $this->subjects = Subject::all();
        $this->teachers = Teacher::with('user')->where('status', 1)->get();
        $this->all_sections = Section::with('schoolClass')->get();
    }

    public function updatedClassId()
    {
        $this->section_id = '';
        if ($this->class_id) {
            $this->sections = Section::where('class_id', $this->class_id)->get();
        } else {
            $this->sections = [];
        }
        $this->calculateMaxPeriods(true);
    }

    public function updatedSectionId()
    {
        $this->calculateMaxPeriods(true);
    }
    
    public function calculateMaxPeriods($reset = false)
    {
        if ($this->class_id) {
            $query = ClassRoutine::where('class_id', $this->class_id);
            if ($this->section_id) {
                $query->where('section_id', $this->section_id);
            }
            $maxInDb = $query->max('period_no');
            
            if ($reset) {
                $this->max_periods = max(8, $maxInDb ?? 8);
            } else {
                $this->max_periods = max(8, $maxInDb ?? 8, (int)$this->max_periods);
            }
        }
    }

    public function openModal($day, $period)
    {
        if (!$this->class_id) {
            session()->flash('error', 'দয়া করে প্রথমে শ্রেণী নির্বাচন করুন।');
            return;
        }

        $this->reset(['subject_id', 'teacher_id', 'start_time', 'end_time', 'room', 'routineId', 'is_break', 'is_combined', 'note', 'additional_sections']);
        $this->resetValidation();
        
        $this->day_of_week = $day;
        $this->period_no = $period;

        // Check if a routine already exists here
        $existing = ClassRoutine::where('class_id', $this->class_id)
            ->where('section_id', $this->section_id ?: null)
            ->where('day_of_week', $day)
            ->where('period_no', $period)
            ->first();

        if ($existing) {
            $this->routineId = $existing->id;
            $this->subject_id = $existing->subject_id;
            $this->teacher_id = $existing->teacher_id;
            $this->start_time = $existing->start_time->format('H:i');
            $this->end_time = $existing->end_time->format('H:i');
            $this->room = $existing->room;
            $this->is_break = $existing->is_break;
            $this->is_combined = $existing->is_combined;
            $this->note = $existing->note;
        } else {
            // Auto-suggest times based on previous period if exists, otherwise default
            $this->start_time = '10:00';
            $this->end_time = '10:45';
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function save()
    {
        $this->validate();

        $activeYear = AcademicYear::where('is_current', 1)->first();

        // VALIDATION: Teacher Conflict Check
        // Only check if it's not a break and not explicitly combined
        if (!$this->is_break && !$this->is_combined) {
            $conflict = ClassRoutine::where('teacher_id', $this->teacher_id)
                ->where('day_of_week', $this->day_of_week)
                ->where(function($query) {
                    // Time overlap logic
                    $query->where(function($q) {
                        $q->where('start_time', '<', $this->end_time)
                          ->where('end_time', '>', $this->start_time);
                    });
                });
                
            if ($this->routineId) {
                $conflict->where('id', '!=', $this->routineId);
            }

            if ($conflict->exists()) {
                $this->addError('teacher_id', 'এই শিক্ষক উক্ত সময়ে অন্য একটি ক্লাসে নিযুক্ত আছেন (Teacher Clash)।');
                return;
            }
        }

        $routineData = [
            'class_id' => $this->class_id,
            'section_id' => $this->section_id ?: null,
            'subject_id' => $this->is_break ? null : $this->subject_id,
            'teacher_id' => $this->is_break ? null : $this->teacher_id,
            'day_of_week' => $this->day_of_week,
            'period_no' => $this->period_no,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'room' => $this->room,
            'is_break' => $this->is_break,
            'is_combined' => $this->is_combined,
            'note' => $this->note,
            'academic_year_id' => $activeYear?->id,
        ];

        ClassRoutine::updateOrCreate(
            ['id' => $this->routineId],
            $routineData
        );
        
        // If it's a combined class, save to additional sections
        if ($this->is_combined && !empty($this->additional_sections)) {
            foreach ($this->additional_sections as $secId) {
                $sec = Section::find($secId);
                if ($sec) {
                    $combinedData = $routineData;
                    $combinedData['class_id'] = $sec->class_id;
                    $combinedData['section_id'] = $sec->id;
                    
                    ClassRoutine::updateOrCreate(
                        [
                            'class_id' => $sec->class_id,
                            'section_id' => $sec->id,
                            'day_of_week' => $this->day_of_week,
                            'period_no' => $this->period_no,
                        ],
                        $combinedData
                    );
                }
            }
        }

        $this->closeModal();
        $this->calculateMaxPeriods();
        session()->flash('success', 'রুটিন আপডেট করা হয়েছে!');
    }

    public function delete()
    {
        if ($this->routineId) {
            ClassRoutine::findOrFail($this->routineId)->delete();
            $this->closeModal();
            $this->calculateMaxPeriods();
            session()->flash('success', 'পিরিয়ডটি মুছে ফেলা হয়েছে!');
        }
    }

    public function render()
    {
        $routines = [];
        
        if ($this->class_id) {
            $query = ClassRoutine::with(['subject', 'teacher.user'])
                ->where('class_id', $this->class_id);
                
            if ($this->section_id) {
                $query->where('section_id', $this->section_id);
            } else {
                $query->whereNull('section_id');
            }

            // Organize routines into a matrix: [day][period]
            $fetchedRoutines = $query->get();
            foreach ($fetchedRoutines as $r) {
                $routines[$r->day_of_week][$r->period_no] = $r;
            }
        }

        return view('livewire.admin.academic.class-routine-builder', [
            'routinesMatrix' => $routines
        ]);
    }
}
