<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'employee_id',
        'designation',
        'qualification',
        'specialization',
        'joining_date',
        'contract_type',
        'basic_salary',
        'photo_path',
        'nid',
        'bank_account',
        'mfs_account',
        'status',
    ];

    protected $casts = [
        'qualification' => 'array',
        'bank_account' => 'array',
        'mfs_account' => 'array',
    ];

    /**
     * Get the user that owns the teacher profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subjects assigned to the teacher.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subjects')
            ->withPivot('class_id', 'section_id')
            ->withTimestamps();
    }

    /**
     * Get the sections where the teacher is a form teacher.
     */
    public function formSections()
    {
        return $this->hasMany(Section::class, 'teacher_id', 'user_id');
    }
}
