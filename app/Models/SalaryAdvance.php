<?php

namespace App\Models;

use App\Enums\AdvanceStatus;
use App\Enums\EmployeeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryAdvance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_type',
        'employee_id',
        'amount',
        'reason',
        'approved_by',
        'approved_at',
        'recovery_months',
        'recovered_amount',
        'status',
    ];

    protected $casts = [
        'approved_at'   => 'datetime',
        'employee_type' => EmployeeType::class,
        'status'        => AdvanceStatus::class,
    ];

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Remaining amount to recover from salary.
     */
    public function getRemainingAttribute(): float
    {
        return max(0, $this->amount - $this->recovered_amount);
    }
}
