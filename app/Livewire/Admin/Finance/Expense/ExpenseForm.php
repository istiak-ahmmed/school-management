<?php

namespace App\Livewire\Admin\Finance\Expense;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Livewire\Component;
use Livewire\WithFileUploads;

class ExpenseForm extends Component
{
    use WithFileUploads;

    public $expenseId = null;
    public $voucher_no;
    public $expense_category_id;
    public $amount;
    public $payment_method_id; 
    public $paid_to;
    public $expense_date;
    public $note;
    
    public $receipts = []; // Array for multiple files
    public $existingMedia = []; // To display existing files

    public function mount($id = null)
    {
        if ($id) {
            $expense = Expense::with('media')->findOrFail($id);
            $this->expenseId = $expense->id;
            $this->voucher_no = $expense->voucher_no;
            $this->expense_category_id = $expense->expense_category_id;
            $this->amount = $expense->amount;
            $this->payment_method_id = $expense->payment_method_id;
            $this->paid_to = $expense->paid_to;
            $this->expense_date = $expense->expense_date->format('Y-m-d');
            $this->note = $expense->note;
            $this->existingMedia = $expense->getMedia('receipts');
        } else {
            $this->voucher_no = 'EXP-' . strtoupper(uniqid());
            $this->expense_date = date('Y-m-d');
            // Set default payment method to Cash if exists
            $cashMethod = \App\Models\PaymentMethod::where('en_name', 'Cash')->first();
            $this->payment_method_id = $cashMethod ? $cashMethod->id : null;
        }
    }

    protected function rules()
    {
        return [
            'voucher_no' => 'required|string|max:30|unique:expenses,voucher_no,' . $this->expenseId,
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'paid_to' => 'nullable|string|max:150',
            'expense_date' => 'required|date',
            'note' => 'nullable|string',
            'receipts.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:5120', // Max 5MB per file
        ];
    }

    public function save()
    {
        $this->validate();

        $expense = Expense::updateOrCreate(
            ['id' => $this->expenseId],
            [
                'voucher_no' => $this->voucher_no,
                'expense_category_id' => $this->expense_category_id,
                'amount' => $this->amount,
                'payment_method_id' => $this->payment_method_id,
                'paid_to' => $this->paid_to,
                'expense_date' => $this->expense_date,
                'note' => $this->note,
                'entered_by' => auth()->id() ?? 1,
                'approved_by' => auth()->id() ?? 1, // Auto-approve as requested
            ]
        );

        if (!empty($this->receipts)) {
            foreach ($this->receipts as $receipt) {
                $expense->addMedia($receipt->getRealPath())
                        ->usingName($receipt->getClientOriginalName())
                        ->usingFileName($receipt->hashName())
                        ->toMediaCollection('receipts');
            }
        }

        session()->flash('message', 'Expense saved successfully.');
        return redirect()->route('admin.finance.expenses.index');
    }
    
    public function removeExistingMedia($mediaId)
    {
        $expense = Expense::findOrFail($this->expenseId);
        $media = $expense->media()->findOrFail($mediaId);
        $media->delete();
        
        $this->existingMedia = $expense->getMedia('receipts');
    }

    public function render()
    {
        return view('livewire.admin.finance.expense.expense-form', [
            'categories' => ExpenseCategory::where('is_active', 1)->orderBy('name')->get(),
            'paymentMethods' => \App\Models\PaymentMethod::where('status', 1)->get(),
        ])->layout('admin.layouts.app');
    }
}
