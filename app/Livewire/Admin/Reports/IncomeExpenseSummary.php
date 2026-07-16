<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Expense;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('admin.layouts.app')]
#[Title('আয়-ব্যয় সারসংক্ষেপ (Income vs Expense Summary)')]
class IncomeExpenseSummary extends Component
{
    public $date_from;
    public $date_to;
    public $view_type = 'monthly'; // 'monthly', 'daily'

    public function mount()
    {
        $this->date_from = Carbon::today()->startOfMonth()->format('Y-m-d');
        $this->date_to = Carbon::today()->endOfMonth()->format('Y-m-d');
    }

    public function getSummaryDataProperty()
    {
        // Fetch Income (Payments)
        $incomeQuery = Payment::where('payment_status', 'completed')
            ->whereDate('paid_at', '>=', $this->date_from)
            ->whereDate('paid_at', '<=', $this->date_to);

        // Fetch Expenses
        $expenseQuery = Expense::whereDate('expense_date', '>=', $this->date_from)
            ->whereDate('expense_date', '<=', $this->date_to);

        $incomeData = [];
        $expenseData = [];

        if ($this->view_type === 'monthly') {
            $incomes = clone $incomeQuery;
            $incomes = $incomes->select(
                DB::raw('YEAR(paid_at) as year'),
                DB::raw('MONTH(paid_at) as month'),
                DB::raw('SUM(amount_paid) as total')
            )
            ->groupBy('year', 'month')
            ->get();

            foreach ($incomes as $inc) {
                $key = sprintf("%04d-%02d", $inc->year, $inc->month);
                $incomeData[$key] = $inc->total;
            }

            $expenses = clone $expenseQuery;
            $expenses = $expenses->select(
                DB::raw('YEAR(expense_date) as year'),
                DB::raw('MONTH(expense_date) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('year', 'month')
            ->get();

            foreach ($expenses as $exp) {
                $key = sprintf("%04d-%02d", $exp->year, $exp->month);
                $expenseData[$key] = $exp->total;
            }

        } else {
            $incomes = clone $incomeQuery;
            $incomes = $incomes->select(
                DB::raw('DATE(paid_at) as date'),
                DB::raw('SUM(amount_paid) as total')
            )
            ->groupBy('date')
            ->get();

            foreach ($incomes as $inc) {
                $incomeData[$inc->date] = $inc->total;
            }

            $expenses = clone $expenseQuery;
            $expenses = $expenses->select(
                DB::raw('DATE(expense_date) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date')
            ->get();

            foreach ($expenses as $exp) {
                $expenseData[$exp->date] = $exp->total;
            }
        }

        // Merge keys
        $allKeys = array_unique(array_merge(array_keys($incomeData), array_keys($expenseData)));
        sort($allKeys);

        $merged = [];
        $totalIncome = 0;
        $totalExpense = 0;

        foreach ($allKeys as $key) {
            $inc = $incomeData[$key] ?? 0;
            $exp = $expenseData[$key] ?? 0;
            $profit = $inc - $exp;
            
            $displayKey = $key;
            if ($this->view_type === 'monthly') {
                $displayKey = Carbon::createFromFormat('Y-m', $key)->format('M Y');
            } else {
                $displayKey = Carbon::parse($key)->format('d M, Y');
            }

            $merged[] = [
                'key' => $key,
                'display' => $displayKey,
                'income' => $inc,
                'expense' => $exp,
                'profit' => $profit
            ];

            $totalIncome += $inc;
            $totalExpense += $exp;
        }

        // Category breakdown for expenses
        $expenseCategories = (clone $expenseQuery)->with('category')
            ->select('expense_category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('expense_category_id')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => optional($item->category)->name ?? 'Unknown',
                    'amount' => $item->total
                ];
            });

        return [
            'timeline' => $merged,
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'net_profit' => $totalIncome - $totalExpense,
            'expense_categories' => $expenseCategories
        ];
    }

    public function downloadCsv()
    {
        $data = $this->summaryData;
        $filename = 'income_expense_summary_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, ['Period', 'Total Income', 'Total Expense', 'Net Profit/Loss']);

            foreach ($data['timeline'] as $row) {
                fputcsv($file, [
                    $row['display'],
                    $row['income'],
                    $row['expense'],
                    $row['profit']
                ]);
            }
            fputcsv($file, ['', '', '', '']);
            fputcsv($file, ['Grand Total', $data['total_income'], $data['total_expense'], $data['net_profit']]);
            
            fputcsv($file, ['', '', '', '']);
            fputcsv($file, ['Expense Category Breakdown']);
            fputcsv($file, ['Category Name', 'Amount']);
            foreach ($data['expense_categories'] as $cat) {
                fputcsv($file, [$cat['name'], $cat['amount']]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        return view('livewire.admin.reports.income-expense-summary', [
            'data' => $this->summaryData
        ]);
    }
}
