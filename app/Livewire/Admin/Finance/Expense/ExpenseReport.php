<?php

namespace App\Livewire\Admin\Finance\Expense;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Response;
use Livewire\Component;
use App\Traits\WithExporting;

class ExpenseReport extends Component
{
    use WithExporting;
    public $date_from;
    public $date_to;
    public $category_id = '';

    public function mount()
    {
        $this->date_from = date('Y-m-01'); // First day of current month
        $this->date_to = date('Y-m-t');    // Last day of current month
    }

    public function getExpensesProperty()
    {
        return Expense::with('category')
            ->when($this->date_from, function ($query) {
                $query->whereDate('expense_date', '>=', $this->date_from);
            })
            ->when($this->date_to, function ($query) {
                $query->whereDate('expense_date', '<=', $this->date_to);
            })
            ->when($this->category_id, function ($query) {
                $query->where('expense_category_id', $this->category_id);
            })
            ->orderBy('expense_date', 'asc')
            ->get();
    }

    protected function getExportHeaders(): array
    {
        return ['তারিখ (Date)', 'ভাউচার নং (Voucher)', 'খাত (Category)', 'প্রদান করা হয়েছে (Paid To)', 'মাধ্যম (Method)', 'নোট (Note)', 'পরিমাণ (Amount)'];
    }

    protected function getExportData(): array
    {
        $expenses = $this->expenses;
        $data = [];
        $total = 0;

        foreach ($expenses as $expense) {
            $method = optional($expense->paymentMethod)->bn_name ?? optional($expense->paymentMethod)->en_name ?? 'Unknown';
            $data[] = [
                $expense->expense_date->format('Y-m-d'),
                $expense->voucher_no,
                $expense->category->name ?? '-',
                $expense->paid_to,
                $method,
                $expense->note,
                $expense->amount
            ];
            $total += $expense->amount;
        }
        
        $data[] = ['', '', '', '', '', 'মোট (Total):', $total];
        return $data;
    }

    public function render()
    {
        return view('livewire.admin.finance.expense.expense-report', [
            'categories' => ExpenseCategory::where('is_active', 1)->orderBy('name')->get(),
        ])->layout('admin.layouts.app');
    }
}
