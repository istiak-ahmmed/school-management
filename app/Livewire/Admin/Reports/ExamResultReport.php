<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Exam;
use App\Models\Mark;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('admin.layouts.app')]
#[Title('পরীক্ষার ফলাফল বিশ্লেষণ (Exam Result Analysis)')]
class ExamResultReport extends Component
{
    public $exam_id = '';
    public $class_id = '';
    public $section_id = '';

    public $exams = [];
    public $classes = [];
    public $sections = [];

    public function mount()
    {
        $this->exams = Exam::orderBy('start_date', 'desc')->get();
        $this->classes = SchoolClass::where('is_active', 1)->get();
    }

    public function updatedClassId($value)
    {
        $this->section_id = '';
        if ($value) {
            $this->sections = Section::where('class_id', $value)->where('is_active', 1)->get();
        } else {
            $this->sections = [];
        }
    }

    public function getAnalysisDataProperty()
    {
        if (!$this->exam_id || !$this->class_id) {
            return null;
        }

        // Fetch marks with relationships
        $marksQuery = Mark::with(['student.user', 'student.schoolClass', 'student.section', 'subject'])
            ->where('exam_id', $this->exam_id)
            ->whereHas('student', function ($q) {
                $q->where('class_id', $this->class_id);
                
                if ($this->section_id) {
                    $q->where('section_id', $this->section_id);
                }
            });

        $marks = $marksQuery->get();

        if ($marks->isEmpty()) {
            return null;
        }

        $studentResults = [];
        $subjectAverages = [];

        foreach ($marks as $mark) {
            $studentId = $mark->student_id;
            $subjectId = $mark->subject_id;
            $subjectName = $mark->subject->name ?? 'Unknown';
            $isFail = $mark->grade === 'F' || $mark->grade_point == 0 || $mark->is_absent;

            // Student Grouping
            if (!isset($studentResults[$studentId])) {
                $studentResults[$studentId] = [
                    'student' => $mark->student,
                    'total_marks' => 0,
                    'total_gpa' => 0,
                    'subject_count' => 0,
                    'is_fail' => false,
                    'marks' => []
                ];
            }

            $studentResults[$studentId]['marks'][$subjectName] = $mark;
            $studentResults[$studentId]['total_marks'] += $mark->marks_obtained;
            $studentResults[$studentId]['total_gpa'] += $mark->grade_point;
            $studentResults[$studentId]['subject_count']++;
            
            if ($isFail) {
                $studentResults[$studentId]['is_fail'] = true;
            }

            // Subject Grouping
            if (!isset($subjectAverages[$subjectName])) {
                $subjectAverages[$subjectName] = [
                    'total_marks' => 0,
                    'count' => 0,
                    'pass_count' => 0,
                    'fail_count' => 0
                ];
            }
            $subjectAverages[$subjectName]['total_marks'] += $mark->marks_obtained;
            $subjectAverages[$subjectName]['count']++;
            
            if (!$isFail) {
                $subjectAverages[$subjectName]['pass_count']++;
            } else {
                $subjectAverages[$subjectName]['fail_count']++;
            }
        }

        // Calculate Student CGPA and Final Status
        $passCount = 0;
        $failCount = 0;
        foreach ($studentResults as $id => &$res) {
            $count = $res['subject_count'];
            $res['cgpa'] = $count > 0 ? round($res['total_gpa'] / $count, 2) : 0;
            if ($res['is_fail']) {
                $res['cgpa'] = 0;
                $res['final_grade'] = 'F';
                $failCount++;
            } else {
                $res['final_grade'] = $this->getGradeFromGpa($res['cgpa']);
                $passCount++;
            }
        }

        // Sort students by CGPA then Total Marks (Merit List)
        usort($studentResults, function($a, $b) {
            if ($b['cgpa'] == $a['cgpa']) {
                return $b['total_marks'] <=> $a['total_marks'];
            }
            return $b['cgpa'] <=> $a['cgpa'];
        });

        // Subject Analysis processing
        foreach ($subjectAverages as &$subj) {
            $subj['average'] = $subj['count'] > 0 ? round($subj['total_marks'] / $subj['count'], 2) : 0;
            $subj['pass_percent'] = $subj['count'] > 0 ? round(($subj['pass_count'] / $subj['count']) * 100, 1) : 0;
        }

        $totalStudents = count($studentResults);
        
        return [
            'students' => $studentResults,
            'subjects' => $subjectAverages,
            'summary' => [
                'total_students' => $totalStudents,
                'pass_count' => $passCount,
                'fail_count' => $failCount,
                'pass_percent' => $totalStudents > 0 ? round(($passCount / $totalStudents) * 100, 1) : 0
            ]
        ];
    }

    private function getGradeFromGpa($gpa)
    {
        if ($gpa >= 5.0) return 'A+';
        if ($gpa >= 4.0) return 'A';
        if ($gpa >= 3.5) return 'A-';
        if ($gpa >= 3.0) return 'B';
        if ($gpa >= 2.0) return 'C';
        if ($gpa >= 1.0) return 'D';
        return 'F';
    }

    public function downloadCsv()
    {
        $data = $this->analysisData;
        if (!$data) return;

        $filename = 'exam_result_report_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, ['Merit Position', 'Student Name', 'Roll No', 'Total Marks', 'CGPA', 'Grade', 'Status']);

            $pos = 1;
            foreach ($data['students'] as $student) {
                fputcsv($file, [
                    $pos++,
                    optional(optional($student['student'])->user)->name ?? '-',
                    optional($student['student'])->roll_no ?? '-',
                    $student['total_marks'],
                    $student['cgpa'],
                    $student['final_grade'],
                    $student['is_fail'] ? 'Fail' : 'Pass'
                ]);
            }
            
            fputcsv($file, ['', '', '', '', '', '', '']);
            fputcsv($file, ['Subject Analysis']);
            fputcsv($file, ['Subject', 'Average Marks', 'Pass %', 'Fail Count']);
            foreach ($data['subjects'] as $name => $subj) {
                fputcsv($file, [$name, $subj['average'], $subj['pass_percent'].'%', $subj['fail_count']]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        return view('livewire.admin.reports.exam-result-report', [
            'data' => $this->analysisData
        ]);
    }
}
