<?php

namespace App\Livewire\Admin\Exam;

use App\Models\Exam;
use App\Models\ExamRoutine;
use App\Models\GradeRule;
use App\Models\Mark;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('admin.layouts.app')]
#[Title('মার্কস এন্ট্রি')]
class MarksEntry extends Component
{
    public $exam_id = '';

    public $class_id = '';

    public $section_id = '';

    public $subject_id = '';

    public $exams = [];

    public $classes = [];

    public $sections = [];

    public $subjects = [];

    public $students = [];

    // State for marks input: array keyed by student_id
    // e.g. $marksData[1] = ['marks_obtained' => 85, 'is_absent' => false]
    public $marksData = [];

    // Loaded routine to get full marks and pass marks
    public $routine = null;

    public function mount()
    {
        $this->exams = Exam::whereIn('status', [1, 2])->orderBy('id', 'desc')->get(); // Ongoing or MarksEntry status
        $this->classes = SchoolClass::all();
    }

    public function updatedClassId()
    {
        $this->section_id = '';
        $this->subject_id = '';
        $this->students = [];
        $this->marksData = [];
        $this->routine = null;

        if ($this->class_id) {
            $this->sections = Section::where('class_id', $this->class_id)->get();
            $this->subjects = Subject::all(); // Alternatively, load only subjects assigned to this class
        } else {
            $this->sections = [];
            $this->subjects = [];
        }
    }

    public function updatedSectionId()
    {
        $this->loadStudents();
    }

    public function updatedSubjectId()
    {
        $this->loadStudents();
    }

    public function updatedExamId()
    {
        $this->loadStudents();
    }

    public function loadStudents()
    {
        if (! $this->exam_id || ! $this->class_id || ! $this->subject_id) {
            $this->students = [];
            $this->marksData = [];
            $this->routine = null;

            return;
        }

        // Fetch the routine to get full marks
        $this->routine = ExamRoutine::where('exam_id', $this->exam_id)
            ->where('class_id', $this->class_id)
            ->where('subject_id', $this->subject_id)
            ->first();

        if (! $this->routine) {
            session()->flash('error', 'এই পরীক্ষা এবং শ্রেণীর জন্য নির্বাচিত বিষয়ের কোনো রুটিন পাওয়া যায়নি। দয়া করে আগে রুটিন তৈরি করুন।');
            $this->students = [];

            return;
        }

        // Fetch students
        $query = Student::with('user')->where('class_id', $this->class_id)->where('status', 1);
        if ($this->section_id) {
            $query->where('section_id', $this->section_id);
        }
        $this->students = $query->orderBy('roll_no')->get();

        // Fetch existing marks
        $existingMarks = Mark::where('exam_id', $this->exam_id)
            ->where('subject_id', $this->subject_id)
            ->whereIn('student_id', $this->students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        $this->marksData = [];
        foreach ($this->students as $student) {
            if ($existingMarks->has($student->id)) {
                $mark = $existingMarks->get($student->id);
                $this->marksData[$student->id] = [
                    'marks_obtained' => $mark->marks_obtained,
                    'is_absent' => $mark->is_absent == 1,
                    'existing' => true,
                    'grade' => $mark->grade,
                ];
            } else {
                $this->marksData[$student->id] = [
                    'marks_obtained' => '',
                    'is_absent' => false,
                    'existing' => false,
                    'grade' => null,
                ];
            }
        }
    }

    public function saveMarks()
    {
        if (! $this->routine) {
            return;
        }

        $academicYearId = Exam::find($this->exam_id)->academic_year_id;
        $gradeRules = GradeRule::where('academic_year_id', $academicYearId)
            ->orWhereNull('academic_year_id')
            ->get();

        $errors = 0;
        $saved = 0;

        foreach ($this->students as $student) {
            $data = $this->marksData[$student->id] ?? null;
            if (! $data) {
                continue;
            }

            $isAbsent = $data['is_absent'] ?? false;
            $marksObtained = $data['marks_obtained'] !== '' ? (float) $data['marks_obtained'] : null;

            if ($isAbsent) {
                $marksObtained = 0;
            } elseif ($marksObtained === null) {
                // Skip empty marks unless marked absent
                continue;
            }

            // Validate against full marks
            if ($marksObtained > $this->routine->full_marks) {
                $this->addError("marksData.{$student->id}.marks_obtained", 'প্রাপ্ত নম্বর পূর্ণমানের চেয়ে বেশি হতে পারে না।');
                $errors++;

                continue;
            }

            // Calculate Grade based on percentage
            $percentage = ($marksObtained / $this->routine->full_marks) * 100;

            $grade = 'F';
            $gradePoint = 0.0;

            if (! $isAbsent) {
                foreach ($gradeRules as $rule) {
                    if ($percentage >= $rule->min_marks && $percentage <= $rule->max_marks) {
                        $grade = $rule->grade;
                        $gradePoint = $rule->grade_point;
                        break;
                    }
                }
            }

            Mark::updateOrCreate(
                [
                    'exam_id' => $this->exam_id,
                    'student_id' => $student->id,
                    'subject_id' => $this->subject_id,
                ],
                [
                    'marks_obtained' => $isAbsent ? null : $marksObtained,
                    'full_marks' => $this->routine->full_marks,
                    'pass_marks' => $this->routine->pass_marks,
                    'is_absent' => $isAbsent ? 1 : 0,
                    'grade' => $grade,
                    'grade_point' => $gradePoint,
                    'entered_by' => auth()->id(),
                    'entered_at' => now(),
                ]
            );
            $saved++;
        }

        if ($errors > 0) {
            session()->flash('warning', "{$saved} জনের মার্কস সংরক্ষিত হয়েছে, তবে {$errors} জনের মার্কসে ভুল থাকায় সংরক্ষণ করা যায়নি।");
        } else {
            session()->flash('success', 'সকল মার্কস সফলভাবে সংরক্ষণ করা হয়েছে!');
            $this->loadStudents(); // Refresh to show grades
        }
    }

    public function render()
    {
        return view('livewire.admin.exam.marks-entry');
    }
}
