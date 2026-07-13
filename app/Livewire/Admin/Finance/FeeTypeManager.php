<?php

namespace App\Livewire\Admin\Finance;

use App\Enums\FeeFrequency;
use App\Models\FeeType;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('admin.layouts.app')]
#[Title('ফি ধরণ (Fee Types)')]
class FeeTypeManager extends Component
{
    public $name;
    public $code;
    public $is_recurring = false;
    public $frequency = 0; // OneTime by default
    public $is_active = true;

    public $typeId;
    public $isEditMode = false;
    public $showModal = false;

    public function updatedIsRecurring($value)
    {
        if ($value && $this->frequency == 0) {
            $this->frequency = 1; // Default to Monthly if checked
        } elseif (!$value) {
            $this->frequency = 0;
        }
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:fee_types,code,' . $this->typeId,
            'is_recurring' => 'boolean',
            'frequency' => ['required', new Enum(FeeFrequency::class)],
            'is_active' => 'boolean',
        ];
    }

    public function openModal()
    {
        $this->reset(['name', 'code', 'is_recurring', 'frequency', 'is_active', 'typeId', 'isEditMode']);
        $this->frequency = 0;
        $this->is_active = true;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function edit(int $id)
    {
        $feeType = FeeType::findOrFail($id);
        $this->typeId = $feeType->id;
        $this->name = $feeType->name;
        $this->code = $feeType->code;
        $this->is_recurring = $feeType->is_recurring;
        $this->frequency = $feeType->frequency->value;
        $this->is_active = $feeType->is_active;

        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        FeeType::updateOrCreate(
            ['id' => $this->typeId],
            [
                'name' => $this->name,
                'code' => $this->code,
                'is_recurring' => $this->is_recurring,
                'frequency' => $this->frequency,
                'is_active' => $this->is_active,
            ]
        );

        $this->closeModal();
        session()->flash('success', $this->isEditMode ? 'ফি এর ধরণ আপডেট করা হয়েছে!' : 'নতুন ফি এর ধরণ যোগ করা হয়েছে!');
    }

    public function toggleStatus(int $id)
    {
        $feeType = FeeType::findOrFail($id);
        $feeType->update(['is_active' => !$feeType->is_active]);
        session()->flash('success', 'স্ট্যাটাস পরিবর্তন করা হয়েছে!');
    }

    public function render()
    {
        $feeTypes = FeeType::orderBy('id', 'desc')->get();
        return view('livewire.admin.finance.fee-type-manager', compact('feeTypes'));
    }
}
