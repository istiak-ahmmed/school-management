<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$month = '2026-07';
$carbon = \Carbon\Carbon::createFromFormat('Y-m', $month);
$atts = \App\Models\StudentAttendance::where('section_id', 1)
    ->whereYear('date', $carbon->year)
    ->whereMonth('date', $carbon->month)
    ->get();

$studentAtts = $atts->where('student_id', 1)->keyBy(function($item) {
    return \Carbon\Carbon::parse($item->date)->format('j');
});

dump($atts->count());
dump($studentAtts->toArray());
dump($studentAtts->has(18));
dump($studentAtts->has('18'));
