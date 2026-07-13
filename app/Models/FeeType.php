<?php

namespace App\Models;

use App\Enums\FeeFrequency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeeType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'is_recurring',
        'frequency',
        'is_active',
    ];

    protected $casts = [
        'is_recurring' => 'boolean',
        'is_active'    => 'boolean',
        'frequency'    => FeeFrequency::class,
    ];

    public function feeStructures(): HasMany
    {
        return $this->hasMany(FeeStructure::class);
    }
}
