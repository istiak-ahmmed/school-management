<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Exam;
use App\Models\ExamRoutine;
use App\Models\Mark;
use App\Models\GradeRule;
use App\Models\Student;
use App\Enums\ExamStatus;
use Illuminate\Support\Facades\DB;

#[Layout('teacher.layouts.app')]
#[Title('মার্কস এন্ট্রি - শিক্ষক পোর্টাল')]
class MarksEntry extends Component
{
    public $exam_id = '';
    public $class_id = '';
    public $subject_id = '';
    public $section_id = '';

    public $exams = [];
    public $allowedClasses = [];
    public $allowedSubjects = [];
    public $sections = [];
    public $students = [];
    public $marksData = [];
    public $routine = null;

    public function mount()
    {
        $teacher = auth()->user()->teacher;
        if (!$teacher) return;

        // Get exams that are in MarksEntry or Ongoing status
        $this->exams = Exam::whereIn('status', [
                ExamStatus::MarksEntry->value,
                ExamStatus::Ongoing->value
            ])
            ->orderBy('id', 'desc')
            ->get();
    }

    public function updatedExamId()
    {
        $teacher = auth()->user()->teacher;
        $this->class_id = '';
        $this->subject_id = '';
        $this->section_id = '';
        $this->students = [];
        $this->marksData = [];
        $this->routine = null;

        if (!$teacher || !$this->exam_id) return;

        // Get class IDs from teacher_subjects for this teacher
        $this->allowedClasses = DB::table('teacher_subjects')
            ->join('classes', 'teacher_subjects.class_id', '=', 'classes.id')
            ->where('teacher_id', $teacher->id)
            ->select('classes.id', 'classes.name', 'classes.numeric_order')
            ->distinct()
            ->orderBy('classes.numeric_order')
            ->get();
    }

    public function updatedClassId()
    {
        $teacher = auth()->user()->teacher;
        $this->subject_id = '';
        $this->section_id = '';
        $this->students = [];
        $this->marksData = [];
        $this->routine = null;

        if (!$teacher || !$this->class_id) return;

        // Get subjects this teacher is assigned to for this specific class
        $this->allowedSubjects = DB::table('teacher_subjects')
            ->join('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
            ->where('teacher_subjects.teacher_id', $teacher->id)
            ->where('teacher_subjects.class_id', $this->class_id)
            ->select('subjects.id', 'subjects.name')
            ->distinct()
            ->get();

        // Get sections for this class
        $this->sections = \App\Models\Section::where('class_id', $this->class_id)->get();
    }

    public function updatedSubjectId()
    {
        $this->loadStudents();
    }

    public function updatedSectionId()
    {
        $this->loadStudents();
    }

    public function loadStudents()
    {
        if (!$this->exam_id || !$this->class_id || !$this->subject_id) {
            $this->students = [];
            $this->marksData = [];
            $this->routine = null;
            return;
        }

        // Get exam routine for marks reference
        $this->routine = ExamRoutine::where('exam_id', $this->exam_id)
            ->where('class_id', $this->class_id)
            ->where('subject_id', $this->subject_id)
            ->first();

        if (!$this->routine) {
            session()->flash('error', 'এই পরীক্ষার জন্য নির্বাচিত ক্লাস/বিষয়ের কোনো রুটিন পাওয়া যায়নি।');
            $this->students = [];
            return;
        }

        // Fetch students
        $query = Student::where('class_id', $this->class_id)->where('status', 1);
        if ($this->section_id) {
            $query->where('section_id', $this->section_id);
        }
        $this->students = $query->orderBy('roll_no')->get();

        // Load existing marks
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
                    'marks_obtained' => $mark->is_absent ? '' : $mark->marks_obtained,
                    'is_absent' => (bool) $mark->is_absent,
                    'grade' => $mark->grade,
                    'existing' => true,
                ];
            } else {
                $this->marksData[$student->id] = [
                    'marks_obtained' => '',
                    'is_absent' => false,
                    'grade' => null,
                    'existing' => false,
                ];
            }
        }
    }

    public function saveMarks()
    {
        if (!$this->routine) return;

        $exam = Exam::find($this->exam_id);
        $gradeRules = GradeRule::where('academic_year_id', $exam->academic_year_id)
            ->orWhereNull('academic_year_id')
            ->get();

        $saved = 0;
        $errors = 0;

        foreach ($this->students as $student) {
            $data = $this->marksData[$student->id] ?? null;
            if (!$data) continue;

            $isAbsent = $data['is_absent'] ?? false;
            $marksObtained = isset($data['marks_obtained']) && $data['marks_obtained'] !== '' 
                ? (float) $data['marks_obtained'] 
                : null;

            if ($isAbsent) {
                $marksObtained = null;
            } elseif ($marksObtained === null) {
                continue; // skip empty
            }

            if (!$isAbsent && $marksObtained > $this->routine->full_marks) {
                $this->addError("marksData.{$student->id}.marks_obtained", 'পূর্ণমানের বেশি হতে পারে না।');
                $errors++;
                continue;
            }

            $grade = 'F';
            $gradePoint = 0.0;

            if (!$isAbsent && $marksObtained !== null) {
                $percentage = ($marksObtained / $this->routine->full_marks) * 100;
                foreach ($gradeRules as $rule) {
                    if ($percentage >= $rule->min_marks && $percentage <= $rule->max_marks) {
                        $grade = $rule->grade;
                        $gradePoint = $rule->grade_point;
                        break;
                    }
                }
            }

            Mark::updateOrCreate(
                ['exam_id' => $this->exam_id, 'student_id' => $student->id, 'subject_id' => $this->subject_id],
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
            session()->flash('warning', "{$saved} জনের মার্কস সংরক্ষিত হয়েছে, {$errors} জনের মার্কসে ভুল ছিল।");
        } else {
            session()->flash('success', 'সকল মার্কস সফলভাবে সংরক্ষণ করা হয়েছে!');
            $this->loadStudents(); // Refresh to show grades
        }
    }

    public function render()
    {
        return view('livewire.teacher.marks-entry');
    }
}
