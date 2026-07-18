<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$payments = \App\Models\SalaryPayment::all();
foreach ($payments as $payment) {
    $payment->load('employee.user');
}
foreach ($payments as $payment) {
    echo "Payment ID: " . $payment->id . "\n";
    $employee = $payment->employee;
    echo "Employee Relation: " . ($employee ? get_class($employee) : 'null') . "\n";
    if ($employee) {
        $user = $employee->user;
        echo "User Name: " . ($user ? $user->name : 'null') . "\n";
    }
    echo "------------------\n";
}
