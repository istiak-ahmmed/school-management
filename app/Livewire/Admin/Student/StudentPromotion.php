<?php

namespace App\Livewire\Admin\Student;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Exam;
use App\Models\Mark;
use Illuminate\Support\Facades\DB;

#[Layout('admin.layouts.app')]
#[Title('শিক্ষার্থী প্রমোশন - Admin Panel')]
class StudentPromotion extends Component
{
    // Source Selection
    public $from_academic_year_id = '';
    public $from_class_id = '';
    public $from_section_id = '';
    
    // Target Selection
    public $to_academic_year_id = '';
    public $to_class_id = '';
    public $to_section_id = '';

    // Data Collections
    public $academicYears = [];
    public $fromClasses = [];
    public $fromSections = [];
    public $toClasses = [];
    public $toSections = [];
    
    // Exams in Source Year for calculation
    public $exams = [];
    public $selected_exams = [];

    public $students = [];
    public $promotions = []; // Checkbox and Roll states

    protected $messages = [
        'from_academic_year_id.required' => 'বর্তমান একাডেমিক ইয়ার নির্বাচন করা আবশ্যক।',
        'from_class_id.required' => 'বর্তমান শ্রেণী নির্বাচন করা আবশ্যক।',
        'from_section_id.required' => 'বর্তমান শাখা নির্বাচন করা আবশ্যক।',
        
        'to_academic_year_id.required' => 'নতুন একাডেমিক ইয়ার নির্বাচন করা আবশ্যক।',
        'to_class_id.required' => 'নতুন শ্রেণী নির্বাচন করা আবশ্যক।',
        'to_section_id.required' => 'নতুন শাখা নির্বাচন করা আবশ্যক।',
    ];

    public function mount()
    {
        $this->academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
    }

    public function updatedFromAcademicYearId($value)
    {
        $this->fromClasses = SchoolClass::all();
        $this->fromSections = [];
        $this->from_class_id = '';
        $this->from_section_id = '';
        
        $this->students = [];
        $this->promotions = [];
        $this->exams = [];
        $this->selected_exams = [];

        if ($value) {
            $this->exams = Exam::where('academic_year_id', $value)->get();
        }
    }

    public function updatedFromClassId($value)
    {
        if ($value) {
            $this->fromSections = Section::where('class_id', $value)->get();
        } else {
            $this->fromSections = [];
        }
        $this->from_section_id = '';
        $this->students = [];
        $this->promotions = [];
    }

    public function updatedToAcademicYearId($value)
    {
        $this->toClasses = SchoolClass::all();
        $this->toSections = [];
        $this->to_class_id = '';
        $this->to_section_id = '';
    }

    public function updatedToClassId($value)
    {
        if ($value) {
            $this->toSections = Section::where('class_id', $value)->get();
        } else {
            $this->toSections = [];
        }
        $this->to_section_id = '';
    }

    public function fetchStudents()
    {
        $this->validate([
            'from_academic_year_id' => 'required',
            'from_class_id' => 'required',
            'from_section_id' => 'required',
        ]);

        $query = Student::where('academic_year_id', $this->from_academic_year_id)
            ->where('class_id', $this->from_class_id)
            ->where('section_id', $this->from_section_id);

        $studentsList = $query->get();
        
        // Calculate Marks
        $studentMarks = [];
        if (!empty($this->selected_exams)) {
            $marksData = Mark::whereIn('exam_id', $this->selected_exams)
                ->whereIn('student_id', $studentsList->pluck('id'))
                ->select('student_id', DB::raw('SUM(marks_obtained) as total_marks'))
                ->groupBy('student_id')
                ->get()
                ->keyBy('student_id');
                
            foreach ($studentsList as $student) {
                $studentMarks[$student->id] = $marksData->has($student->id) ? $marksData[$student->id]->total_marks : 0;
            }
            
            // Sort by marks desc
            $studentsList = $studentsList->sortByDesc(function ($student) use ($studentMarks) {
                return $studentMarks[$student->id];
            })->values();
        }

        $this->students = $studentsList;
        $this->promotions = [];

        $roll = 1;
        foreach ($this->students as $student) {
            $this->promotions[$student->id] = [
                'promote' => true,
                'roll_no' => (!empty($this->selected_exams)) ? $roll++ : $student->roll_no,
                'total_marks' => $studentMarks[$student->id] ?? 0
            ];
        }
    }

    public function promoteStudents()
    {
        $this->validate([
            'to_academic_year_id' => 'required',
            'to_class_id' => 'required',
            'to_section_id' => 'required',
        ]);

        $promotedCount = 0;

        DB::transaction(function () {
            foreach ($this->promotions as $studentId => $data) {
                if ($data['promote']) {
                    $student = Student::find($studentId);
                    
                    if ($student) {
                        // 1. Record History in StudentEnrollment
                        StudentEnrollment::create([
                            'student_id' => $student->id,
                            'academic_year_id' => $student->academic_year_id,
                            'class_id' => $student->class_id,
                            'section_id' => $student->section_id,
                            'roll_no' => $student->roll_no,
                        ]);
                        
                        // 2. Update Student for the new academic year
                        $student->update([
                            'academic_year_id' => $this->to_academic_year_id,
                            'class_id' => $this->to_class_id,
                            'section_id' => $this->to_section_id,
                            'roll_no' => $data['roll_no'],
                        ]);
                        
                        $promotedCount++;
                    }
                }
            }
        });

        session()->flash('success', "সফলভাবে {$promotedCount} জন শিক্ষার্থীকে প্রমোট করা হয়েছে।");
        
        // Reset state
        $this->students = [];
        $this->promotions = [];
    }

    public function render()
    {
        return view('livewire.admin.student.student-promotion');
    }
}
