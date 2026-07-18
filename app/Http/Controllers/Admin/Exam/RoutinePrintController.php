<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\SchoolClass;
use Barryvdh\DomPDF\Facade\Pdf;

class RoutinePrintController extends Controller
{
    public function show(int $exam_id, int $class_id)
    {
        $exam = Exam::with('academicYear')->findOrFail($exam_id);
        $schoolClass = SchoolClass::findOrFail($class_id);
        
        $routines = $exam->routines()
            ->where('class_id', $class_id)
            ->with(['subject', 'teachers.user'])
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();

        return view('pdf.exam-routine', compact('exam', 'schoolClass', 'routines'));
    }
}
