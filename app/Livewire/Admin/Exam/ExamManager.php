<?php

namespace App\Livewire\Admin\Exam;

use App\Enums\ExamStatus;
use App\Enums\ExamType;
use App\Models\AcademicYear;
use App\Models\Exam;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('admin.layouts.app')]
#[Title('পরীক্ষা ব্যবস্থাপনা')]
class ExamManager extends Component
{
    use WithPagination;

    public $name;
    public $code;
    public $exam_type = '';
    public $academic_year_id = '';
    public $start_date;
    public $end_date;
    public $result_publish_date;
    
    public $examId;
    public $isEditMode = false;
    public $showModal = false;
    
    public $statusUpdateId;
    public $newStatus = '';
    public $showStatusModal = false;

    protected $rules = [
        'name' => 'required|string|max:150',
        'code' => 'nullable|string|max:20',
        'exam_type' => 'required|integer',
        'academic_year_id' => 'required|exists:academic_years,id',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'result_publish_date' => 'nullable|date|after_or_equal:end_date',
    ];

    public function mount()
    {
        $activeYear = AcademicYear::where('is_current', 1)->first();
        if ($activeYear) {
            $this->academic_year_id = $activeYear->id;
        }
    }

    public function openModal()
    {
        $this->reset(['name', 'code', 'exam_type', 'start_date', 'end_date', 'result_publish_date', 'examId', 'isEditMode']);
        $this->resetValidation();
        
        $activeYear = AcademicYear::where('is_current', 1)->first();
        if ($activeYear) {
            $this->academic_year_id = $activeYear->id;
        }
        
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function edit(int $id)
    {
        $exam = Exam::findOrFail($id);
        $this->examId = $exam->id;
        $this->name = $exam->name;
        $this->code = $exam->code;
        $this->exam_type = $exam->exam_type->value;
        $this->academic_year_id = $exam->academic_year_id;
        $this->start_date = $exam->start_date?->format('Y-m-d');
        $this->end_date = $exam->end_date?->format('Y-m-d');
        $this->result_publish_date = $exam->result_publish_date?->format('Y-m-d');
        
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'code' => $this->code,
            'exam_type' => $this->exam_type,
            'academic_year_id' => $this->academic_year_id,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'result_publish_date' => $this->result_publish_date ?: null,
        ];

        if (! $this->isEditMode) {
            $data['status'] = ExamStatus::Upcoming->value;
            $data['created_by'] = auth()->id();
        }

        Exam::updateOrCreate(
            ['id' => $this->examId],
            $data
        );

        $this->closeModal();
        session()->flash('success', $this->isEditMode ? 'পরীক্ষা আপডেট হয়েছে!' : 'নতুন পরীক্ষা যোগ করা হয়েছে!');
    }

    public function delete(int $id)
    {
        Exam::findOrFail($id)->delete();
        session()->flash('success', 'পরীক্ষা মুছে ফেলা হয়েছে!');
    }

    public function openStatusModal(int $id)
    {
        $exam = Exam::findOrFail($id);
        $this->statusUpdateId = $exam->id;
        $this->newStatus = $exam->status->value;
        $this->showStatusModal = true;
    }

    public function closeStatusModal()
    {
        $this->showStatusModal = false;
        $this->statusUpdateId = null;
    }

    public function updateStatus()
    {
        $this->validate([
            'newStatus' => 'required|integer'
        ]);

        $exam = Exam::findOrFail($this->statusUpdateId);
        $exam->update(['status' => $this->newStatus]);

        $this->closeStatusModal();
        session()->flash('success', 'পরীক্ষার অবস্থা আপডেট করা হয়েছে!');
    }

    public function render()
    {
        $exams = Exam::with(['academicYear', 'creator'])->latest()->paginate(10);
        $academicYears = AcademicYear::all();
        $examTypes = ExamType::cases();
        $examStatuses = ExamStatus::cases();

        return view('livewire.admin.exam.exam-manager', compact('exams', 'academicYears', 'examTypes', 'examStatuses'));
    }
}
