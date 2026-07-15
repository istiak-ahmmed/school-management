<?php

namespace App\Livewire\Admin\Exam;

use App\Models\AcademicYear;
use App\Models\GradeRule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Livewire\Traits\Sortable;
use Livewire\WithPagination;

#[Layout('admin.layouts.app')]
#[Title('গ্রেডিং সিস্টেম')]
class GradeRuleManager extends Component
{
    use Sortable;

    use WithPagination;

    public $academic_year_id = '';
    public $min_marks;
    public $max_marks;
    public $grade;
    public $grade_point;
    public $remarks;

    public $ruleId;
    public $isEditMode = false;
    public $showModal = false;

    protected $rules = [
        'academic_year_id' => 'nullable|exists:academic_years,id',
        'min_marks' => 'required|numeric|min:0',
        'max_marks' => 'required|numeric|min:0|gte:min_marks',
        'grade' => 'required|string|max:5',
        'grade_point' => 'required|numeric|min:0|max:5',
        'remarks' => 'nullable|string|max:50',
    ];

    public function mount()
    {
        // No defaults to load initially
    }

    public function openModal()
    {
        $this->reset(['academic_year_id', 'min_marks', 'max_marks', 'grade', 'grade_point', 'remarks', 'ruleId', 'isEditMode']);
        $this->resetValidation();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function edit(int $id)
    {
        $rule = GradeRule::findOrFail($id);
        $this->ruleId = $rule->id;
        $this->academic_year_id = $rule->academic_year_id;
        $this->min_marks = $rule->min_marks;
        $this->max_marks = $rule->max_marks;
        $this->grade = $rule->grade;
        $this->grade_point = $rule->grade_point;
        $this->remarks = $rule->remarks;

        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        GradeRule::updateOrCreate(
            ['id' => $this->ruleId],
            [
                'academic_year_id' => $this->academic_year_id ?: null,
                'min_marks' => $this->min_marks,
                'max_marks' => $this->max_marks,
                'grade' => $this->grade,
                'grade_point' => $this->grade_point,
                'remarks' => $this->remarks,
            ]
        );

        $this->closeModal();
        session()->flash('success', $this->isEditMode ? 'গ্রেড রুল আপডেট হয়েছে!' : 'নতুন গ্রেড রুল যোগ করা হয়েছে!');
    }

    public function delete(int $id)
    {
        GradeRule::findOrFail($id)->delete();
        session()->flash('success', 'গ্রেড রুল মুছে ফেলা হয়েছে!');
    }

    public function render()
    {
        $rules = GradeRule::with('academicYear')->orderBy('grade_point', 'desc')->paginate(15);
        $academicYears = AcademicYear::all();

        return view('livewire.admin.exam.grade-rule-manager', compact('rules', 'academicYears'));
    }
}
