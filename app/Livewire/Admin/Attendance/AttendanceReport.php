<?php

namespace App\Livewire\Admin\Attendance;

use Livewire\Component;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use App\Traits\WithExporting;

#[Layout('admin.layouts.app')]
class AttendanceReport extends Component
{
    use WithExporting;
    public $classes = [];
    public $sections = [];
    
    public $selectedClass = null;
    public $selectedSection = null;
    public $month;
    
    public $reportData = [];
    public $students = [];
    public $daysInMonth = 0;
    public $summary = [
        'present' => 0,
        'absent' => 0,
        'late' => 0,
        'excused' => 0,
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
        
        $this->students = Student::with('user')->where('class_id', $this->selectedClass)
            ->where('section_id', $this->selectedSection)
            ->where('status', 1)
            ->get();

        $attendances = StudentAttendance::where('class_id', $this->selectedClass)
            ->where('section_id', $this->selectedSection)
            ->whereYear('date', $carbonMonth->year)
            ->whereMonth('date', $carbonMonth->month)
            ->get();

        $this->reportData = [];
        $this->summary = ['present' => 0, 'absent' => 0, 'late' => 0, 'excused' => 0, 'total' => 0];

        foreach ($this->students as $student) {
            $studentAtts = $attendances->where('student_id', $student->id)->keyBy(function($item) {
                return Carbon::parse($item->date)->format('j');
            });
            
            $this->reportData[$student->id] = [];
            
            for ($day = 1; $day <= $this->daysInMonth; $day++) {
                if ($studentAtts->has($day)) {
                    $status = $studentAtts->get($day)->status;
                    $this->reportData[$student->id][$day] = $status;
                    
                    $statusStrKey = match($status) {
                        1 => 'present',
                        2 => 'absent',
                        3 => 'late',
                        4 => 'excused',
                        default => null,
                    };

                    if ($statusStrKey && isset($this->summary[$statusStrKey])) {
                        $this->summary[$statusStrKey]++;
                        $this->summary['total']++;
                    }
                } else {
                    $this->reportData[$student->id][$day] = null; // No record
                }
            }
        }
    }

    protected function getExportHeaders(): array
    {
        $headers = ['রোল', 'নাম'];
        for ($day = 1; $day <= $this->daysInMonth; $day++) {
            $headers[] = (string) $day;
        }
        return $headers;
    }

    protected function getExportData(): array
    {
        if (empty($this->students)) {
            $this->generateReport();
        }

        $data = [];
        foreach ($this->students as $student) {
            $row = [
                $student->roll_no ?? '-',
                $student->user->name ?? $student->name ?? '-'
            ];
            
            for ($day = 1; $day <= $this->daysInMonth; $day++) {
                $status = $this->reportData[$student->id][$day] ?? null;
                $statusStr = '-';
                if ($status === 1) $statusStr = 'P';
                elseif ($status === 2) $statusStr = 'A';
                elseif ($status === 3) $statusStr = 'L';
                elseif ($status === 4) $statusStr = 'E';
                
                $row[] = $statusStr;
            }
            $data[] = $row;
        }
        return $data;
    }

    public function render()
    {
        return view('livewire.admin.attendance.attendance-report');
    }
}
