<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_no',
        'invoice_id',
        'student_id',
        'amount_paid',
        'payment_method_id',
        'payment_date',
        'transaction_id',
        'gateway_response',
        'payment_status',
        'paid_by',
        'due_amount',
        'paid_at',
        'collected_by',
        'receipt_generated',
        'note',
    ];

    protected $casts = [
        'gateway_response'  => 'array',
        'paid_at'           => 'datetime',
        'payment_date'      => 'date',
        'receipt_generated' => 'boolean',
        'payment_status'    => PaymentStatus::class,
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function collector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collected_by');
    }
}
