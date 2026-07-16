<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\Gender;
use App\Enums\BloodGroup;
use App\Enums\Religion;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'admission_no',
        'roll_no',
        'class_id',
        'section_id',
        'academic_year_id',
        'date_birth',
        'gender',
        'blood_group',
        'religion',
        'nationality',
        'birth_certificate_no',
        'address_present',
        'address_permanent',
        'photo_path',
        'medical_info',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_birth' => 'date',
            'status'     => 'integer',
            'gender'     => Gender::class,
            'blood_group'=> BloodGroup::class,
            'religion'   => Religion::class,
        ];
    }

    /**
     * The user account for this student.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The class this student is enrolled in.
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * The section this student belongs to.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * The academic year for this enrollment.
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * The guardians linked to this student.
     */
    public function guardians(): BelongsToMany
    {
        return $this->belongsToMany(Guardian::class, 'guardian_student')
            ->withPivot('relation', 'is_primary')
            ->withTimestamps();
    }

    /**
     * Get the primary guardian.
     */
    public function primaryGuardian(): BelongsToMany
    {
        return $this->guardians()->wherePivot('is_primary', true);
    }

    /**
     * Get human-readable status label in Bengali.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            0 => 'নিষ্ক্রিয়',
            1 => 'সক্রিয়',
            2 => 'পাশ',
            3 => 'বহিষ্কৃত',
            default => 'অজানা',
        };
    }

    /**
     * Get status badge CSS class.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            0 => 'bg-gray-100 text-gray-800',
            1 => 'bg-green-100 text-green-800',
            2 => 'bg-blue-100 text-blue-800',
            3 => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
