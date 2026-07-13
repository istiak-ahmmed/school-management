<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>ইনভয়েস - {{ $invoice->invoice_no }}</title>
    <style>
        body {
            font-family: 'freeserif', sans-serif;
            margin: 0;
            padding: 10px;
            font-size: 13px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #3f51b5;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .school-name {
            font-size: 22px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .invoice-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
            background-color: #e8eaf6;
            padding: 5px;
            border: 1px solid #c5cae9;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 4px;
            vertical-align: top;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .item-table th, .item-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        .item-table th {
            background-color: #f9fafb;
            font-weight: bold;
        }
        .text-right {
            text-align: right !important;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="school-name">{{ config('app.name', 'School Management System') }}</div>
        <div>School Address Here</div>
        <div class="invoice-title">শিক্ষার্থী ইনভয়েস</div>
    </div>

    <table class="info-table">
        <tr>
            <td width="20%"><strong>ইনভয়েস নং:</strong></td>
            <td width="30%">{{ $invoice->invoice_no }}</td>
            <td width="20%"><strong>ইস্যুর তারিখ:</strong></td>
            <td width="30%">{{ $invoice->created_at->format('d M Y') }}</td>
        </tr>
        <tr>
            <td><strong>শিক্ষার্থী:</strong></td>
            <td>{{ $invoice->student->user->name ?? 'N/A' }}</td>
            <td><strong>বকেয়া তারিখ:</strong></td>
            <td>{{ $invoice->due_date ? $invoice->due_date->format('d M Y') : 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>ভর্তি নং:</strong></td>
            <td>{{ $invoice->student->admission_no ?? 'N/A' }}</td>
            <td><strong>অবস্থা:</strong></td>
            <td>{{ $invoice->status->label() }}</td>
        </tr>
        <tr>
            <td><strong>শ্রেণী:</strong></td>
            <td colspan="3">{{ $invoice->student->schoolClass->name ?? 'N/A' }} ({{ $invoice->student->section->name ?? '' }})</td>
        </tr>
    </table>

    <table class="item-table">
        <thead>
            <tr>
                <th>বিবরণ (ফি প্রকার)</th>
                <th>মাস</th>
                <th class="text-right">পরিমাণ (৳)</th>
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
                <td colspan="2" class="text-right">ছাড় (-)</td>
                <td class="text-right">{{ number_format($invoice->discount, 2) }}</td>
            </tr>
            @endif
            @if($invoice->fine > 0)
            <tr>
                <td colspan="2" class="text-right">জরিমানা (+)</td>
                <td class="text-right">{{ number_format($invoice->fine, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td colspan="2" class="text-right"><strong>মোট বিল (নেট)</strong></td>
                <td class="text-right"><strong>{{ number_format($invoice->net_amount, 2) }}</strong></td>
            </tr>
            
            @php
                $paid = $invoice->payments()->where('payment_status', 0)->sum('amount_paid');
                $due = max(0, $invoice->net_amount - $paid);
            @endphp
            
            @if($paid > 0)
            <tr>
                <td colspan="2" class="text-right">ইতিমধ্যে পরিশোধিত (-)</td>
                <td class="text-right">{{ number_format($paid, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td colspan="2" class="text-right" style="background-color: #f3f4f6;"><strong>বর্তমান বকেয়া</strong></td>
                <td class="text-right" style="background-color: #f3f4f6;"><strong>{{ number_format($due, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 30px; font-size: 11px; text-align: center; color: #777;">
        * এটি একটি কম্পিউটার জেনারেটেড ইনভয়েস, কোনো স্বাক্ষরের প্রয়োজন নেই।
    </div>

</body>
</html>
