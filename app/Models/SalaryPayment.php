<?php

namespace App\Models;

use App\Enums\EmployeeType;
use App\Enums\SalaryPaymentMethod;
use App\Enums\SalaryStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_no',
        'employee_type',
        'employee_id',
        'month_year',
        'basic_salary',
        'total_allowance',
        'total_deduction',
        'gross_salary',
        'net_salary',
        'advance_deducted',
        'payment_method',
        'transaction_id',
        'paid_at',
        'paid_by',
        'status',
        'note',
    ];

    protected $casts = [
        'paid_at'        => 'datetime',
        'employee_type'  => EmployeeType::class,
        'payment_method' => SalaryPaymentMethod::class,
        'status'         => SalaryStatus::class,
    ];

    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }
}
