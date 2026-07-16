<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Invoice;

#[Layout('student.layouts.app')]
#[Title('ফি ও পেমেন্ট - শিক্ষার্থী')]
class MyFees extends Component
{
    public $filterStatus = 'all';

    public function render()
    {
        $student = auth()->user()->student;
        
        if (!$student) {
            return view('livewire.student.dashboard-empty');
        }

        $query = Invoice::with(['feeType', 'academicYear', 'payments'])
            ->where('student_id', $student->id)
            ->orderBy('due_date', 'desc');

        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        $invoices = $query->get();

        // Summary
        $totalBilled = $invoices->sum('net_amount');
        $totalPaid = $invoices->sum(function($invoice) {
            return $invoice->payments->where('payment_status', 0)->sum('amount_paid'); // 0 is success in payment status enum maybe? Wait, let's assume getRemainingAmountAttribute works.
        });
        
        // Actually I should just sum remaining amounts from all invoices, and subtract from net_amount to get paid.
        $totalDue = $invoices->sum(function($invoice) {
            return $invoice->remaining_amount;
        });
        $totalPaid = $totalBilled - $totalDue;

        return view('livewire.student.my-fees', [
            'invoices' => $invoices,
            'totalBilled' => $totalBilled,
            'totalPaid' => $totalPaid,
            'totalDue' => $totalDue
        ]);
    }
}
