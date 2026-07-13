<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'min_marks',
        'max_marks',
        'grade',
        'grade_point',
        'remarks',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
