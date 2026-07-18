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
        'status' => 'integer',
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
            1 => 'উপস্থিত',
            2 => 'অনুপস্থিত',
            3 => 'বিলম্বে',
            4 => 'ছুটি',
            default => 'অজানা',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            1 => 'bg-green-100 text-green-800',
            2 => 'bg-red-100 text-red-800',
            3 => 'bg-amber-100 text-amber-800',
            4 => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
