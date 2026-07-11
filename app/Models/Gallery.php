<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'path',
        'is_published',
        'order_column',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}
