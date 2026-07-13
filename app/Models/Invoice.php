<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'student_id',
        'fee_type_id',
        'academic_year_id',
        'month_year',
        'amount',
        'discount',
        'fine',
        'net_amount',
        'due_date',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'due_date' => 'date',
        'status'   => InvoiceStatus::class,
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function feeType(): BelongsTo
    {
        return $this->belongsTo(FeeType::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Remaining amount to be collected.
     */
    public function getRemainingAmountAttribute(): float
    {
        $paid = $this->payments()->where('payment_status', 0)->sum('amount_paid');
        return max(0, $this->net_amount - $paid);
    }
}
