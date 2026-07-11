<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmissionApplication extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'application_no',
        'applicant_name',
        'dob',
        'gender',
        'applying_for_class_id',
        'academic_year_id',
        'guardian_name',
        'guardian_phone',
        'guardian_email',
        'address',
        'previous_school',
        'documents_path',
        'status',
        'reviewed_by',
        'review_note',
        'reviewed_at',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'dob'         => 'date',
            'gender'      => 'integer',
            'status'      => 'integer',
            'reviewed_at' => 'datetime',
            'submitted_at' => 'datetime',
            'documents_path' => 'array',
        ];
    }

    /**
     * The class the applicant is applying for.
     */
    public function applyingForClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'applying_for_class_id');
    }

    /**
     * The academic year of the application.
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Admin who reviewed this application.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get human-readable status label in Bengali.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            1 => 'Pending / মুলতবি',
            2 => 'Under Review / পর্যালোচনাধীন',
            3 => 'Accepted / গৃহীত',
            4 => 'Rejected / প্রত্যাখ্যাত',
            5 => 'Enrolled / ভর্তিকৃত',
            default => 'অজানা',
        };
    }

    /**
     * Get status badge CSS color class.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            1 => 'bg-yellow-100 text-yellow-800',
            2 => 'bg-blue-100 text-blue-800',
            3 => 'bg-green-100 text-green-800',
            4 => 'bg-red-100 text-red-800',
            5 => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
