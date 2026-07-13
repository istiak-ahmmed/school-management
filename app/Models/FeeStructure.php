<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeeStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'fee_type_id',
        'class_id',
        'academic_year_id',
        'amount',
        'due_day',
        'late_fine',
    ];

    public function feeType(): BelongsTo
    {
        return $this->belongsTo(FeeType::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
