<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Report - {{ $monthName }}</title>
    <style>
        body { font-family: 'freeserif', sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 4px; text-align: center; }
        th { background-color: #f3f4f6; }
        .text-left { text-align: left; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; padding: 0; font-size: 18px; }
        .header p { margin: 5px 0; font-size: 12px; color: #555; }
        .summary-box { border: 1px solid #ddd; padding: 10px; display: inline-block; margin-right: 15px; }
        .p { color: green; font-weight: bold; }
        .a { color: red; font-weight: bold; }
        .l { color: orange; font-weight: bold; }
        .e { color: blue; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>হাজিরা রিপোর্ট (Attendance Report)</h2>
        <p>শ্রেণী: {{ $className }} | শাখা: {{ $sectionName }} | মাস: {{ $monthName }}</p>
    </div>

    <div style="margin-bottom: 15px;">
        <span class="summary-box">মোট শিক্ষার্থী: {{ count($students) }}</span>
        <span class="summary-box">উপস্থিত: {{ $summary['present'] }}</span>
        <span class="summary-box">অনুপস্থিত: {{ $summary['absent'] }}</span>
        <span class="summary-box">বিলম্বে: {{ $summary['late'] }}</span>
        <span class="summary-box">ছুটি: {{ $summary['excused'] }}</span>
        <span class="summary-box">হাজিরার হার: {{ $summary['total'] > 0 ? number_format(($summary['present'] / $summary['total']) * 100, 1) : 0 }}%</span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">রোল</th>
                <th class="text-left" style="width: 120px;">নাম</th>
                @for($d = 1; $d <= $daysInMonth; $d++)
                    <th>{{ $d }}</th>
                @endfor
                <th>%</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                @php
                    $studentPresent = 0;
                    $studentTotal = 0;
                @endphp
                <tr>
                    <td>{{ $student->roll_no ?? '-' }}</td>
                    <td class="text-left">{{ $student->user->name ?? $student->name ?? '-' }}</td>
                    @for($d = 1; $d <= $daysInMonth; $d++)
                        @php
                            $status = $reportData[$student->id][$d] ?? null;
                            if ($status) $studentTotal++;
                            if ($status === 1) $studentPresent++;
                            
                            $class = '';
                            $text = '-';
                            if ($status === 1) { $class = 'p'; $text = 'P'; }
                            elseif ($status === 2) { $class = 'a'; $text = 'A'; }
                            elseif ($status === 3) { $class = 'l'; $text = 'L'; }
                            elseif ($status === 4) { $class = 'e'; $text = 'E'; }
                        @endphp
                        <td class="{{ $class }}">{{ $text }}</td>
                    @endfor
                    <td>
                        {{ $studentTotal > 0 ? number_format(($studentPresent / $studentTotal) * 100, 0) : '-' }}%
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px; font-size: 9px; color: #666;">
        P = Present (উপস্থিত), A = Absent (অনুপস্থিত), L = Late (বিলম্বে), E = Excused (ছুটি)
    </div>
</body>
</html>
