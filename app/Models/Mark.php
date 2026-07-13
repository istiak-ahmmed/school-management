<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mark extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'subject_id',
        'marks_obtained',
        'full_marks',
        'pass_marks',
        'is_absent',
        'grade',
        'grade_point',
        'remarks',
        'entered_by',
        'entered_at',
    ];

    protected $casts = [
        'entered_at' => 'datetime',
        'is_absent' => 'boolean', // Even though tinyint, cast as boolean for logic
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function enteredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entered_by');
    }
}
