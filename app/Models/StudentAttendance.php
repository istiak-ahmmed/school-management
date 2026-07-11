<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAttendance extends Model
{
    protected $fillable = [
        'student_id',
        'class_id',
        'section_id',
        'date',
        'status',
        'note',
        'recorded_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'present' => 'উপস্থিত',
            'absent' => 'অনুপস্থিত',
            'late' => 'বিলম্বে',
            'excused' => 'ছুটি',
            default => 'অজানা',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'present' => 'bg-green-100 text-green-800',
            'absent' => 'bg-red-100 text-red-800',
            'late' => 'bg-amber-100 text-amber-800',
            'excused' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
