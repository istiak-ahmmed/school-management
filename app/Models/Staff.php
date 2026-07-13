<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'designation',
        'department',
        'joining_date',
        'basic_salary',
        'photo_path',
        'nid',
        'contract_type',
        'qualification',
        'bank_account',
        'mfs_account',
        'status',
    ];

    protected $casts = [
        'qualification' => 'array',
        'bank_account' => 'array',
        'mfs_account' => 'array',
        'joining_date' => 'date',
    ];

    /**
     * Get the user that owns the staff profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
