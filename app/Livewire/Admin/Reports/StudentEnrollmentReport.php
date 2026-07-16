<?php

namespace App\Livewire\Admin\Reports;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Support\Facades\Response;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('admin.layouts.app')]
#[Title('শিক্ষার্থী ভর্তি রিপোর্ট (Student Enrollment Report)')]
class StudentEnrollmentReport extends Component
{
    public $academic_year_id = '';
    public $class_id = '';
    public $section_id = '';
    public $gender = '';

    public $academicYears = [];
    public $classes = [];
    public $sections = [];

    public function mount()
    {
        $this->academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $activeYear = $this->academicYears->where('is_active', 1)->first();
        if ($activeYear) {
            $this->academic_year_id = $activeYear->id;
        }

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

    public function getEnrollmentsProperty()
    {
        if (!$this->academic_year_id) {
            return collect();
        }

        return Student::with(['schoolClass', 'section'])
            ->where('academic_year_id', $this->academic_year_id)
            ->when($this->class_id, function ($q) {
                $q->where('class_id', $this->class_id);
            })
            ->when($this->section_id, function ($q) {
                $q->where('section_id', $this->section_id);
            })
            ->when($this->gender, function ($q) {
                $q->where('gender', $this->gender);
            })
            ->get()
            ->sortBy([
                ['class_id', 'asc'],
                ['section_id', 'asc'],
                ['roll_no', 'asc']
            ]);
    }

    public function downloadCsv()
    {
        $enrollments = $this->enrollments;
        $filename = 'student_enrollment_report_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($enrollments) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Excel Bengali rendering
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, ['ID', 'Roll No', 'Name', 'Class', 'Section', 'Gender', 'Phone']);

            foreach ($enrollments as $student) {
                fputcsv($file, [
                    $student->admission_no ?? '',
                    $student->roll_no,
                    $student->user->name ?? $student->name ?? '',
                    $student->schoolClass->name ?? '',
                    $student->section->name ?? '',
                    ucfirst($student->gender->value ?? $student->gender ?? ''),
                    $student->guardians->first()->phone ?? ''
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        $enrollments = $this->enrollments;
        
        $totalStudents = $enrollments->count();
        $maleCount = $enrollments->filter(function($e) {
            return strtolower($e->student->gender ?? '') === 'male';
        })->count();
        $femaleCount = $enrollments->filter(function($e) {
            return strtolower($e->student->gender ?? '') === 'female';
        })->count();

        return view('livewire.admin.reports.student-enrollment-report', [
            'enrollments' => $enrollments,
            'summary' => [
                'total' => $totalStudents,
                'male' => $maleCount,
                'female' => $femaleCount,
            ]
        ]);
    }
}
