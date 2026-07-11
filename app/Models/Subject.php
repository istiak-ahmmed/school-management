<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'code',
        'class_id',
        'subject_type',
        'full_marks',
        'pass_marks',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    protected function casts(): array
    {
        return [
            'subject_type' => 'integer',
            'is_active' => 'integer',
        ];
    }

    public function getSubjectTypeLabelAttribute(): string
    {
        return match ($this->subject_type) {
            1 => 'Core (আবশ্যিক)',
            2 => 'Optional (ঐচ্ছিক)',
            3 => 'Extra (অতিরিক্ত)',
            default => 'Unknown',
        };
    }
}
