<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Generate and stream Invoice PDF.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load([
            'feeType',
            'academicYear',
            'student.user',
            'student.schoolClass',
            'student.section',
            'payments',
        ]);

        return view('pdf.invoice', compact('invoice'));
    }
}
