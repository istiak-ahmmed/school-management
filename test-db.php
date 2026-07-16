<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo '1. Enrollment: ' . App\Models\StudentEnrollment::count() . PHP_EOL;
echo '2. Payment: ' . App\Models\Payment::count() . PHP_EOL;
echo '3. Invoice: ' . App\Models\Invoice::count() . PHP_EOL;
echo '4. Expense: ' . App\Models\Expense::count() . PHP_EOL;
echo '5. Mark: ' . App\Models\Mark::count() . PHP_EOL;
echo '6. SalaryPayment: ' . App\Models\SalaryPayment::count() . PHP_EOL;
echo '7. Attendance: ' . App\Models\StudentAttendance::count() . PHP_EOL;
echo '8. Student: ' . App\Models\Student::count() . PHP_EOL;
echo '9. User: ' . App\Models\User::count() . PHP_EOL;
