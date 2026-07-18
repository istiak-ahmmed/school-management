<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>পরীক্ষার রুটিন - {{ $schoolClass->name }}</title>
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
            max-width: 900px;
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
        .routine-badge {
            background-color: #eef2ff; /* Light Indigo */
            color: #4338ca; /* Dark Indigo */
            padding: 8px 16px;
            border-radius: 9999px;
            font-size: 16px;
            font-weight: 700;
            border: 1px solid #c7d2fe;
            text-align: center;
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
            vertical-align: middle;
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
            text-align: center;
            padding: 12px;
        }
        .table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
        }
        .text-left {
            text-align: left !important;
        }
        .text-right {
            text-align: right !important;
        }
        .date-box {
            font-weight: 700;
            color: #111827;
        }
        .day-box {
            font-size: 12px;
            color: #6b7280;
        }
        .footer {
            display: table;
            width: 100%;
            margin-top: 50px;
        }
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: right;
        }
        .signature-line {
            border-top: 1px solid #9ca3af;
            width: 250px;
            margin: 0 0 0 auto;
            padding-top: 8px;
            font-weight: 600;
            color: #374151;
            text-align: center;
        }
        
        @media print {
            body { padding: 0; background-color: #fff; }
            .container { border: none; padding: 0; max-width: 100%; }
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
                <div class="routine-badge">{{ $exam->name }} ({{ $exam->academicYear->name ?? '' }})</div>
            </div>
        </div>

        <!-- Meta Info -->
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell" style="font-size: 18px;"><span class="label">শ্রেণী:</span> <strong>{{ $schoolClass->name }}</strong></div>
                <div class="info-cell text-right"><span class="label">প্রকাশের তারিখ:</span> {{ now()->format('d M Y') }}</div>
            </div>
        </div>

        <!-- Routine Table -->
        <table class="table">
            <thead>
                <tr>
                    <th style="border-radius: 6px 0 0 0;">তারিখ ও বার</th>
                    <th>সময়</th>
                    <th class="text-left">বিষয়</th>
                    <th>রুম</th>
                    <th>মান (পূর্ণ/পাস)</th>
                    <th class="text-left" style="border-radius: 0 6px 0 0;">গার্ড (শিক্ষক)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($routines as $routine)
                    <tr>
                        <td>
                            <div class="date-box">{{ $routine->exam_date->format('d M Y') }}</div>
                            <div class="day-box">{{ $routine->exam_date->locale('bn')->translatedFormat('l') }}</div>
                        </td>
                        <td>{{ $routine->start_time->format('h:i A') }} - {{ $routine->end_time->format('h:i A') }}</td>
                        <td class="text-left font-bold" style="font-weight: 600; color: #111827;">{{ $routine->subject->name ?? '-' }}</td>
                        <td>{{ $routine->room ?? '-' }}</td>
                        <td>{{ $routine->full_marks }} / {{ $routine->pass_marks }}</td>
                        <td class="text-left" style="font-size: 12px; color: #4b5563;">
                            @foreach($routine->teachers as $teacher)
                                {{ $teacher->user->name ?? '' }}@if(!$loop->last), @endif
                            @endforeach
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 40px; color: #6b7280;">এই শ্রেণীর জন্য এখনো কোনো রুটিন তৈরি করা হয়নি।</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Signatures -->
        <div class="footer">
            <div class="signature-box">
                <div class="signature-line">প্রধান শিক্ষক / পরীক্ষা নিয়ন্ত্রক</div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 30px; font-size: 11px; color: #9ca3af;">
            This document is auto-generated by the system.
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
