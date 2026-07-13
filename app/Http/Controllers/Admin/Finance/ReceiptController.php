<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Payment;

class ReceiptController extends Controller
{
    /**
     * Generate and stream payment receipt PDF.
     */
    public function show(Payment $payment)
    {
        $payment->load([
            'invoice.feeType',
            'invoice.academicYear',
            'student.user',
            'student.schoolClass',
            'collector',
        ]);

        // Mark receipt as generated
        $payment->update(['receipt_generated' => 1]);

        return view('pdf.receipt', compact('payment'));
    }
}
