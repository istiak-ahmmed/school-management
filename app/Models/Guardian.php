<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guardian extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'occupation',
        'relation',
        'mother_name',
        'mother_occupation',
        'address',
        'photo_path',
    ];

    /**
     * The user account for this guardian (optional login).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Students linked to this guardian.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'guardian_student')
            ->withPivot('relation', 'is_primary')
            ->withTimestamps();
    }
}
