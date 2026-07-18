<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>ইনভয়েস - {{ $invoice->invoice_no }}</title>
    <style>
        @import url('https://fonts.maateen.me/kalpurush/font.css');
        
        body {
            font-family: 'Kalpurush', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            color: #374151;
            background-color: #fff;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #e5e7eb;
            padding: 30px;
            border-radius: 8px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #0891b2; /* Cyan-600 */
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .school-info {
            text-align: left;
        }
        .school-name {
            font-size: 26px;
            font-weight: 700;
            color: #4f46e5; /* Indigo-600 */
            margin-bottom: 4px;
        }
        .school-address {
            color: #6b7280;
            font-size: 13px;
        }
        .invoice-badge {
            background-color: #eef2ff; /* Light Indigo */
            color: #4338ca; /* Dark Indigo */
            padding: 8px 16px;
            border-radius: 9999px;
            font-size: 16px;
            font-weight: 700;
            border: 1px solid #c7d2fe;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            background-color: #f9fafb;
            border-radius: 6px;
            padding: 15px;
        }
        .info-row {
            display: table-row;
        }
        .info-cell {
            display: table-cell;
            padding: 6px 12px;
            vertical-align: top;
        }
        .label {
            font-weight: 600;
            color: #4b5563;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table th {
            background-color: #4f46e5; /* Indigo-600 */
            color: #ffffff;
            font-weight: 600;
            text-align: left;
            padding: 12px;
        }
        .table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .text-right {
            text-align: right !important;
        }
        .text-center {
            text-align: center !important;
        }
        .total-row td {
            font-weight: 700;
            font-size: 16px;
            background-color: #f3f4f6;
            color: #111827;
        }
        .highlight-due {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        
        @media print {
            body { padding: 0; background-color: #fff; }
            .container { border: none; padding: 0; }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="school-info">
                <div class="school-name">{{ config('app.name', 'School Management System') }}</div>
                <div class="school-address">School Address Here<br>Phone: +880 1234 567890 | Email: info@school.com</div>
            </div>
            <div>
                <div class="invoice-badge">শিক্ষার্থী ইনভয়েস</div>
            </div>
        </div>

        <!-- Meta Info -->
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell"><span class="label">ইনভয়েস নং:</span> {{ $invoice->invoice_no }}</div>
                <div class="info-cell"><span class="label">ইস্যুর তারিখ:</span> {{ $invoice->created_at->format('d M Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell"><span class="label">শিক্ষার্থী:</span> {{ $invoice->student->user->name ?? 'N/A' }}</div>
                <div class="info-cell"><span class="label">বকেয়া তারিখ:</span> {{ $invoice->due_date ? $invoice->due_date->format('d M Y') : 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell"><span class="label">ভর্তি নং:</span> {{ $invoice->student->admission_no ?? 'N/A' }}</div>
                <div class="info-cell"><span class="label">অবস্থা:</span> {{ $invoice->status->label() }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell" colspan="2"><span class="label">শ্রেণী:</span> {{ $invoice->student->schoolClass->name ?? 'N/A' }} ({{ $invoice->student->section->name ?? '' }})</div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="table">
            <thead>
                <tr>
                    <th style="border-radius: 6px 0 0 0;">বিবরণ (ফি প্রকার)</th>
                    <th>মাস</th>
                    <th class="text-right" style="border-radius: 0 6px 0 0;">পরিমাণ (৳)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $invoice->feeType->name ?? 'N/A' }}</td>
                    <td>{{ $invoice->month_year ?? '-' }}</td>
                    <td class="text-right">{{ number_format($invoice->amount, 2) }}</td>
                </tr>
                @if($invoice->discount > 0)
                <tr>
                    <td colspan="2" class="text-right text-emerald-600">ছাড় (-)</td>
                    <td class="text-right text-emerald-600">{{ number_format($invoice->discount, 2) }}</td>
                </tr>
                @endif
                @if($invoice->fine > 0)
                <tr>
                    <td colspan="2" class="text-right text-rose-600">জরিমানা (+)</td>
                    <td class="text-right text-rose-600">{{ number_format($invoice->fine, 2) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td colspan="2" class="text-right">মোট বিল (নেট):</td>
                    <td class="text-right text-indigo-600">৳ {{ number_format($invoice->net_amount, 2) }}</td>
                </tr>
                
                @php
                    $paid = $invoice->payments()->where('payment_status', 0)->sum('amount_paid');
                    $due = max(0, $invoice->net_amount - $paid);
                @endphp
                
                @if($paid > 0)
                <tr>
                    <td colspan="2" class="text-right text-emerald-600">ইতিমধ্যে পরিশোধিত (-)</td>
                    <td class="text-right text-emerald-600">{{ number_format($paid, 2) }}</td>
                </tr>
                @endif
                <tr class="total-row {{ $due > 0 ? 'highlight-due' : '' }}">
                    <td colspan="2" class="text-right">বর্তমান বকেয়া:</td>
                    <td class="text-right">৳ {{ number_format($due, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 50px; font-size: 11px; text-align: center; color: #9ca3af;">
            This is a computer generated invoice and does not require a physical signature for validity.
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
