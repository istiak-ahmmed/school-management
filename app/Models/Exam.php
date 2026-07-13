<?php

namespace App\Models;

use App\Enums\ExamStatus;
use App\Enums\ExamType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'exam_type',
        'academic_year_id',
        'start_date',
        'end_date',
        'result_publish_date',
        'status',
        'created_by',
    ];

    protected $casts = [
        'exam_type' => ExamType::class,
        'status' => ExamStatus::class,
        'start_date' => 'date',
        'end_date' => 'date',
        'result_publish_date' => 'date',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function routines(): HasMany
    {
        return $this->hasMany(ExamRoutine::class);
    }

    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }
}
