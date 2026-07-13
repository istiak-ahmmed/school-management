<?php

namespace App\Models;

use App\Enums\SalaryPaymentMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_no',
        'expense_head',
        'amount',
        'payment_method',
        'paid_to',
        'receipt_path',
        'expense_date',
        'approved_by',
        'entered_by',
        'note',
    ];

    protected $casts = [
        'expense_date'   => 'date',
        'payment_method' => SalaryPaymentMethod::class, // 0=cash,1=bank,2=bkash
    ];

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function enterer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entered_by');
    }
}
