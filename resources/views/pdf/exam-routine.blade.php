<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>পরীক্ষার রুটিন - {{ $schoolClass->name }}</title>
    <style>
        body {
            font-family: 'freeserif', sans-serif;
            margin: 0;
            padding: 10px;
            font-size: 14px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #3f51b5;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .routine-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
            background-color: #e8eaf6;
            padding: 8px;
            border: 1px solid #c5cae9;
            text-align: center;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px;
            font-size: 15px;
        }
        .routine-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .routine-table th, .routine-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .routine-table th {
            background-color: #f9fafb;
            font-weight: bold;
            color: #444;
        }
        .text-left {
            text-align: left !important;
        }
        .footer {
            margin-top: 50px;
            width: 100%;
        }
        .signature {
            border-top: 1px solid #333;
            width: 200px;
            text-align: center;
            padding-top: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="school-name">{{ config('app.name', 'School Management System') }}</div>
        <div>School Address Here</div>
    </div>

    <div class="routine-title">
        {{ $exam->name }} ({{ $exam->academicYear->name ?? '' }}) - পরীক্ষার রুটিন
    </div>

    <table class="info-table">
        <tr>
            <td width="50%"><strong>শ্রেণী:</strong> {{ $schoolClass->name }}</td>
            <td width="50%" style="text-align: right;"><strong>প্রকাশের তারিখ:</strong> {{ now()->format('d M, Y') }}</td>
        </tr>
    </table>

    <table class="routine-table">
        <thead>
            <tr>
                <th>তারিখ ও বার</th>
                <th>সময়</th>
                <th class="text-left">বিষয়</th>
                <th>রুম</th>
                <th>মান (পূর্ণ/পাস)</th>
                <th class="text-left">গার্ড (শিক্ষক)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($routines as $routine)
                <tr>
                    <td>
                        <strong>{{ $routine->exam_date->format('d M, Y') }}</strong><br>
                        <span style="font-size: 12px; color: #555;">{{ $routine->exam_date->locale('bn')->translatedFormat('l') }}</span>
                    </td>
                    <td>{{ $routine->start_time->format('h:i A') }} - {{ $routine->end_time->format('h:i A') }}</td>
                    <td class="text-left font-bold">{{ $routine->subject->name ?? '-' }}</td>
                    <td>{{ $routine->room ?? '-' }}</td>
                    <td>{{ $routine->full_marks }} / {{ $routine->pass_marks }}</td>
                    <td class="text-left" style="font-size: 12px;">
                        @foreach($routine->teachers as $teacher)
                            {{ $teacher->user->name ?? '' }}@if(!$loop->last), @endif
                        @endforeach
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding: 30px;">এই শ্রেণীর জন্য এখনো কোনো রুটিন তৈরি করা হয়নি।</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="footer">
        <tr>
            <td width="50%"></td>
            <td width="50%" align="right">
                <div class="signature" style="float: right;">
                    প্রধান শিক্ষক / পরীক্ষা নিয়ন্ত্রক
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
