<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>বেতন স্লিপ - {{ $payment->voucher_no }}</title>
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
        .slip-badge {
            background-color: #e0f2fe; /* Light Cyan */
            color: #0369a1; /* Dark Cyan */
            padding: 8px 16px;
            border-radius: 9999px;
            font-size: 16px;
            font-weight: 700;
            border: 1px solid #bae6fd;
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
        .table .border-right {
            border-right: 1px solid #e5e7eb;
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
        .net-salary-box {
            background-color: #4f46e5;
            color: white;
            padding: 15px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .notes-section {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 12px 16px;
            margin-bottom: 40px;
            border-radius: 0 4px 4px 0;
            font-size: 13px;
        }
        .footer {
            display: table;
            width: 100%;
            margin-top: 50px;
        }
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #9ca3af;
            width: 200px;
            margin: 0 auto;
            padding-top: 8px;
            font-weight: 600;
            color: #374151;
        }
        .signature-name {
            color: #6b7280;
            font-size: 12px;
            margin-bottom: 5px;
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
                <div class="slip-badge">বেতন স্লিপ (মাস: {{ \Carbon\Carbon::parse($payment->month_year)->format('F, Y') }})</div>
            </div>
        </div>

        <!-- Meta Info -->
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell"><span class="label">কর্মচারী/শিক্ষক:</span> {{ $employeeName }}</div>
                <div class="info-cell"><span class="label">ভাউচার নং:</span> {{ $payment->voucher_no }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell"><span class="label">পদবি:</span> {{ $employeeDesignation }}</div>
                <div class="info-cell"><span class="label">পেমেন্ট তারিখ:</span> {{ $payment->paid_at->format('d M Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell"><span class="label">আইডি:</span> {{ $employeeId }}</div>
                <div class="info-cell"><span class="label">মাধ্যম:</span> {{ optional($payment->paymentMethod)->bn_name ?? optional($payment->paymentMethod)->en_name ?? 'Cash' }}</div>
            </div>
        </div>

        <!-- Salary Breakdown Table -->
        <table class="table" style="border: 1px solid #e5e7eb;">
            <thead>
                <tr>
                    <th colspan="2" style="border-radius: 6px 0 0 0;" class="border-right">আয়ের বিবরণ (Earnings)</th>
                    <th colspan="2" style="border-radius: 0 6px 0 0;">কর্তনের বিবরণ (Deductions)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td width="30%">মূল বেতন (Basic)</td>
                    <td width="20%" class="text-right border-right">{{ number_format($payment->basic_salary, 2) }}</td>
                    <td width="30%">অন্যান্য কর্তন (Tax/Provident)</td>
                    <td width="20%" class="text-right text-rose-600">{{ number_format($payment->total_deduction, 2) }}</td>
                </tr>
                <tr>
                    <td>অন্যান্য ভাতা (Allowances)</td>
                    <td class="text-right border-right">{{ number_format($payment->total_allowance, 2) }}</td>
                    <td>অগ্রিম কর্তন (Advance)</td>
                    <td class="text-right text-rose-600">{{ number_format($payment->advance_deducted, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>মোট আয় (Gross)</td>
                    <td class="text-right border-right text-indigo-600">৳ {{ number_format($payment->gross_salary, 2) }}</td>
                    <td>মোট কর্তন (Total Deductions)</td>
                    <td class="text-right text-rose-600">৳ {{ number_format($payment->total_deduction + $payment->advance_deducted, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Net Salary Box -->
        <div class="net-salary-box">
            <div style="font-size: 16px; font-weight: 600;">পরিশোধিত নেট বেতন (Net Payable):</div>
            <div style="font-size: 24px; font-weight: 700;">৳ {{ number_format($payment->net_salary, 2) }}</div>
        </div>

        <!-- Notes -->
        @if($payment->transaction_id || $payment->note)
        <div class="notes-section">
            @if($payment->transaction_id)
                <div style="margin-bottom: 4px;"><strong>লেনদেন আইডি (Txn ID):</strong> {{ $payment->transaction_id }}</div>
            @endif
            @if($payment->note)
                <div><strong>মন্তব্য:</strong> {{ $payment->note }}</div>
            @endif
        </div>
        @endif

        <!-- Signatures -->
        <div class="footer">
            <div class="signature-box">
                <div class="signature-name">{{ $payment->payer->name ?? 'System' }}</div>
                <div class="signature-line">অনুমোদনকারী/অ্যাকাউন্ট্যান্ট</div>
            </div>
            <div class="signature-box">
                <div class="signature-name"><br></div>
                <div class="signature-line">গ্রহীতার স্বাক্ষর</div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 30px; font-size: 11px; color: #9ca3af;">
            This is a computer generated salary slip and does not require a physical signature for validity.
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
