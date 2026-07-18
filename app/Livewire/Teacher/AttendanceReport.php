<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Traits\WithExporting;

#[Layout('teacher.layouts.app')]
#[Title('হাজিরা রিপোর্ট - শিক্ষক পোর্টাল')]
class AttendanceReport extends Component
{
    use WithExporting;

    public $sections = [];
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
        
        $user = auth()->user();
        $this->sections = Section::with('schoolClass')
            ->where('teacher_id', $user->id)
            ->get();

        if ($this->sections->isNotEmpty()) {
            $this->selectedSection = $this->sections->first()->id;
        }
    }

    public function generateReport()
    {
        $this->validate([
            'selectedSection' => 'required',
            'month' => 'required|date_format:Y-m',
        ]);

        $section = Section::find($this->selectedSection);
        if (!$section || $section->teacher_id !== auth()->id()) {
            return; // Prevent unauthorized access
        }

        $carbonMonth = Carbon::createFromFormat('Y-m', $this->month);
        $this->daysInMonth = $carbonMonth->daysInMonth;
        
        $this->students = Student::with('user')->where('section_id', $this->selectedSection)
            ->where('status', 1)
            ->orderBy('roll_no')
            ->get();

        $attendances = StudentAttendance::where('section_id', $this->selectedSection)
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
        return view('livewire.teacher.attendance-report');
    }
}
