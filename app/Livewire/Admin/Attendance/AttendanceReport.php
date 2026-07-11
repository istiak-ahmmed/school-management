<?php

namespace App\Livewire\Admin\Attendance;

use Livewire\Component;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Layout;

#[Layout('admin.layouts.app')]
class AttendanceReport extends Component
{
    public $classes = [];
    public $sections = [];
    
    public $selectedClass = null;
    public $selectedSection = null;
    public $month;
    
    public $reportData = [];
    public $students = [];
    public $daysInMonth = 0;
    public $summary = [
        1 => 0,
        2 => 0,
        3 => 0,
        4 => 0,
        'total' => 0
    ];

    public function mount()
    {
        $this->month = Carbon::today()->format('Y-m');
        $this->classes = SchoolClass::where('is_active', 1)->get();
    }

    public function updatedSelectedClass($value)
    {
        $this->sections = Section::where('class_id', $value)
            ->where('is_active', 1)
            ->get();
        $this->selectedSection = null;
    }

    public function generateReport()
    {
        $this->validate([
            'selectedClass' => 'required',
            'selectedSection' => 'required',
            'month' => 'required|date_format:Y-m',
        ]);

        $carbonMonth = Carbon::createFromFormat('Y-m', $this->month);
        $this->daysInMonth = $carbonMonth->daysInMonth;
        
        $this->students = Student::where('class_id', $this->selectedClass)
            ->where('section_id', $this->selectedSection)
            ->where('status', 1)
            ->get();

        $attendances = StudentAttendance::where('class_id', $this->selectedClass)
            ->where('section_id', $this->selectedSection)
            ->whereYear('date', $carbonMonth->year)
            ->whereMonth('date', $carbonMonth->month)
            ->get();

        $this->reportData = [];
        $this->summary = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 'total' => 0];

        foreach ($this->students as $student) {
            $studentAtts = $attendances->where('student_id', $student->id)->keyBy(function($item) {
                return Carbon::parse($item->date)->format('j');
            });
            
            $this->reportData[$student->id] = [];
            
            for ($day = 1; $day <= $this->daysInMonth; $day++) {
                if ($studentAtts->has($day)) {
                    $status = $studentAtts->get($day)->status;
                    $this->reportData[$student->id][$day] = $status;
                    
                    if (isset($this->summary[$status])) {
                        $this->summary[$status]++;
                        $this->summary['total']++;
                    }
                } else {
                    $this->reportData[$student->id][$day] = null; // No record
                }
            }
        }
    }

    public function exportPdf()
    {
        $this->generateReport();
        
        $className = SchoolClass::find($this->selectedClass)->name ?? '';
        $sectionName = Section::find($this->selectedSection)->name ?? '';
        $monthName = Carbon::createFromFormat('Y-m', $this->month)->translatedFormat('F Y');

        $pdf = Pdf::loadView('reports.attendance', [
            'students' => $this->students,
            'reportData' => $this->reportData,
            'daysInMonth' => $this->daysInMonth,
            'className' => $className,
            'sectionName' => $sectionName,
            'monthName' => $monthName,
            'summary' => $this->summary,
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, "attendance_report_{$monthName}.pdf");
    }

    public function render()
    {
        return view('livewire.admin.attendance.attendance-report');
    }
}
