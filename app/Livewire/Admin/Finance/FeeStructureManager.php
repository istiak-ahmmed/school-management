<?php

namespace App\Livewire\Admin\Finance;

use App\Models\AcademicYear;
use App\Models\FeeStructure;
use App\Models\FeeType;
use App\Models\SchoolClass;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Livewire\Traits\Sortable;

#[Layout('admin.layouts.app')]
#[Title('ফি স্ট্রাকচার (Fee Structure)')]
class FeeStructureManager extends Component
{
    use Sortable;

    public $academic_year_id;
    public $class_id;

    public $academicYears = [];
    public $classes = [];
    public $feeTypes = [];

    // Array to hold the amounts: feeAmounts[fee_type_id] = amount
    public $feeAmounts = [];

    public function mount()
    {
        $this->academicYears = AcademicYear::orderBy('id', 'desc')->get();
        $this->classes = SchoolClass::all();
        $this->feeTypes = FeeType::where('is_active', true)->get();

        $activeYear = $this->academicYears->where('is_current', 1)->first();
        if ($activeYear) {
            $this->academic_year_id = $activeYear->id;
        }

        if ($this->classes->count() > 0) {
            $this->class_id = $this->classes->first()->id;
        }

        $this->loadStructures();
    }

    public function updatedAcademicYearId()
    {
        $this->loadStructures();
    }

    public function updatedClassId()
    {
        $this->loadStructures();
    }

    public function loadStructures()
    {
        $this->feeAmounts = [];

        if (!$this->academic_year_id || !$this->class_id) {
            return;
        }

        $structures = FeeStructure::where('academic_year_id', $this->academic_year_id)
            ->where('class_id', $this->class_id)
            ->get();

        foreach ($this->feeTypes as $type) {
            $struct = $structures->where('fee_type_id', $type->id)->first();
            $this->feeAmounts[$type->id] = $struct ? $struct->amount : '';
        }
    }

    public function save()
    {
        $this->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:classes,id',
        ]);

        foreach ($this->feeTypes as $type) {
            $amount = $this->feeAmounts[$type->id] ?? null;

            if ($amount !== '' && $amount !== null && is_numeric($amount)) {
                FeeStructure::updateOrCreate(
                    [
                        'academic_year_id' => $this->academic_year_id,
                        'class_id' => $this->class_id,
                        'fee_type_id' => $type->id,
                    ],
                    [
                        'amount' => $amount,
                    ]
                );
            } else {
                // If empty, delete if exists (means no fee for this class)
                FeeStructure::where('academic_year_id', $this->academic_year_id)
                    ->where('class_id', $this->class_id)
                    ->where('fee_type_id', $type->id)
                    ->delete();
            }
        }

        session()->flash('success', 'ফি স্ট্রাকচার সফলভাবে সংরক্ষণ করা হয়েছে!');
    }

    public function render()
    {
        return view('livewire.admin.finance.fee-structure-manager');
    }
}
