<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>রসিদ - {{ $payment->payment_no }}</title>
    <style>
        body {
            font-family: 'Kalpurush', 'SolaimanLipi', 'Hind Siliguri', 'Nirmala UI', sans-serif;
            margin: 0;
            padding: 10px;
            font-size: 14px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2ecc71;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .receipt-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
            background-color: #f1f8e9;
            padding: 5px;
            border: 1px solid #c5e1a5;
        }
        .info-table, .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 4px;
            vertical-align: top;
        }
        .item-table th, .item-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .item-table th {
            background-color: #f9fafb;
            font-weight: bold;
        }
        .text-right {
            text-align: right !important;
        }
        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .signature {
            border-top: 1px solid #333;
            width: 200px;
            text-align: center;
            padding-top: 5px;
            margin-top: 50px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="school-name">{{ config('app.name', 'School Management System') }}</div>
        <div>School Address Here</div>
        <div class="receipt-title">মানি রসিদ (পেমেন্ট স্লিপ)</div>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%"><strong>রসিদ নং:</strong></td>
            <td width="35%">{{ $payment->payment_no }}</td>
            <td width="15%"><strong>তারিখ:</strong></td>
            <td width="35%">{{ $payment->paid_at->format('d M Y, h:i A') }}</td>
        </tr>
        <tr>
            <td><strong>শিক্ষার্থী:</strong></td>
            <td>{{ $payment->student->user->name ?? 'N/A' }}</td>
            <td><strong>ভর্তি নং:</strong></td>
            <td>{{ $payment->student->admission_no ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>শ্রেণী:</strong></td>
            <td>{{ $payment->student->schoolClass->name ?? 'N/A' }}</td>
            <td><strong>রোল নং:</strong></td>
            <td>{{ $payment->student->roll_no ?? 'N/A' }}</td>
        </tr>
    </table>

    <table class="item-table">
        <thead>
            <tr>
                <th>বিবরণ (ইনভয়েস: {{ $payment->invoice->invoice_no }})</th>
                <th>মাস</th>
                <th class="text-right">পেমেন্ট মাধ্যম</th>
                <th class="text-right">পরিমাণ (৳)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $payment->invoice->feeType->name ?? 'ফি' }}</td>
                <td>{{ $payment->invoice->month_year ?? '-' }}</td>
                <td class="text-right">{{ $payment->payment_method->label() }}</td>
                <td class="text-right"><strong>{{ number_format($payment->amount_paid, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    @if($payment->transaction_id || $payment->note)
    <div style="font-size: 12px; margin-bottom: 20px;">
        @if($payment->transaction_id)
            <p><strong>লেনদেন আইডি:</strong> {{ $payment->transaction_id }}</p>
        @endif
        @if($payment->note)
            <p><strong>মন্তব্য:</strong> {{ $payment->note }}</p>
        @endif
    </div>
    @endif

    <table width="100%">
        <tr>
            <td>
                <div class="signature" style="float: left;">
                    {{ $payment->collector->name ?? 'System' }}<br>
                    <strong>সংগ্রহকারী</strong>
                </div>
            </td>
            <td>
                <div class="signature" style="float: right;">
                    <br>
                    <strong>প্রদানকারী/অভিভাবক</strong>
                </div>
            </td>
        </tr>
    </table>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
