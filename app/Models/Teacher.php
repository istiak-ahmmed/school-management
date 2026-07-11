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
        'bkash_number',
        'status',
    ];

    /**
     * Get the user that owns the teacher profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
