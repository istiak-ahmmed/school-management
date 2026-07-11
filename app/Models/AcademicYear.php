<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_current',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_current' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (AcademicYear $academicYear) {
            if ($academicYear->is_current) {
                // Unset is_current for all other records
                static::where('id', '!=', $academicYear->id)->update(['is_current' => false]);
            }
        });
    }
}
