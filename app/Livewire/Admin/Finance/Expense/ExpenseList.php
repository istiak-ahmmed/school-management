<?php

namespace App\Livewire\Admin\Finance\Expense;

use App\Models\Expense;
use Livewire\Component;
use Livewire\WithPagination;

class ExpenseList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'expense_date';
    public $sortDirection = 'desc';
    
    // For Expense Details Modal
    public $viewingExpense = null;
    public $showDetailsModal = false;

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->clearMediaCollection('receipts');
        $expense->delete();
        session()->flash('message', 'খরচ সফলভাবে ডিলিট করা হয়েছে।');
    }

    public function viewExpense($id)
    {
        $this->viewingExpense = Expense::with(['category', 'enterer', 'approver', 'media', 'paymentMethod'])->findOrFail($id);
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->viewingExpense = null;
    }

    public function render()
    {
        $expenses = Expense::with(['category', 'enterer', 'media', 'paymentMethod'])
            ->when($this->search, function ($query) {
                $query->where('voucher_no', 'like', '%' . $this->search . '%')
                    ->orWhere('paid_to', 'like', '%' . $this->search . '%')
                    ->orWhereHas('category', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        return view('livewire.admin.finance.expense.expense-list', [
            'expenses' => $expenses,
        ])->layout('admin.layouts.app');
    }
}
