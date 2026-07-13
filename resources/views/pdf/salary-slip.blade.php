<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>বেতন স্লিপ - {{ $payment->voucher_no }}</title>
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
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .receipt-title {
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
        .amount-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .amount-table th, .amount-table td {
            border: 1px solid #ddd;
            padding: 6px;
        }
        .amount-table th {
            background-color: #f9fafb;
            text-align: left;
        }
        .text-right {
            text-align: right !important;
        }
        .bg-gray {
            background-color: #f3f4f6;
            font-weight: bold;
        }
        .signature {
            border-top: 1px solid #333;
            width: 180px;
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
        <div class="receipt-title">বেতন স্লিপ - মাস: {{ $payment->month_year }}</div>
    </div>

    <table class="info-table">
        <tr>
            <td width="20%"><strong>কর্মচারী/শিক্ষক:</strong></td>
            <td width="30%">{{ $employeeName }}</td>
            <td width="20%"><strong>ভাউচার নং:</strong></td>
            <td width="30%">{{ $payment->voucher_no }}</td>
        </tr>
        <tr>
            <td><strong>পদবি:</strong></td>
            <td>{{ $employeeDesignation }}</td>
            <td><strong>পেমেন্ট তারিখ:</strong></td>
            <td>{{ $payment->paid_at->format('d M Y') }}</td>
        </tr>
        <tr>
            <td><strong>আইডি:</strong></td>
            <td>{{ $employeeId }}</td>
            <td><strong>মাধ্যম:</strong></td>
            <td>{{ $payment->payment_method->label() }}</td>
        </tr>
    </table>

    <table class="amount-table">
        <thead>
            <tr>
                <th colspan="2">আয়ের বিবরণ</th>
                <th colspan="2">কর্তনের বিবরণ</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td width="30%">মূল বেতন</td>
                <td width="20%" class="text-right">{{ number_format($payment->basic_salary, 2) }}</td>
                <td width="30%">অন্যান্য কর্তন (ট্যাক্স/ইত্যাদি)</td>
                <td width="20%" class="text-right">{{ number_format($payment->total_deduction, 2) }}</td>
            </tr>
            <tr>
                <td>অন্যান্য ভাতা</td>
                <td class="text-right">{{ number_format($payment->total_allowance, 2) }}</td>
                <td>অগ্রিম কর্তন</td>
                <td class="text-right text-red-600">{{ number_format($payment->advance_deducted, 2) }}</td>
            </tr>
            <tr class="bg-gray">
                <td>মোট আয়</td>
                <td class="text-right">{{ number_format($payment->gross_salary, 2) }}</td>
                <td>মোট কর্তন</td>
                <td class="text-right">{{ number_format($payment->total_deduction + $payment->advance_deducted, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <table class="amount-table" style="margin-top: 10px;">
        <tr>
            <td width="50%" class="bg-gray" style="text-align: right; font-size: 14px;"><strong>পরিশোধিত নেট বেতন (প্রাপ্য):</strong></td>
            <td width="50%" style="font-size: 16px; font-weight: bold; color: #2c3e50;">৳ {{ number_format($payment->net_salary, 2) }}</td>
        </tr>
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
                    {{ $payment->payer->name ?? 'System' }}<br>
                    <strong>অনুমোদনকারী/অ্যাকাউন্ট্যান্ট</strong>
                </div>
            </td>
            <td>
                <div class="signature" style="float: right;">
                    <br>
                    <strong>গ্রহীতার স্বাক্ষর</strong>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
