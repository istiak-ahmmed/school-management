<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'category',
        'attachment_path',
        'is_published',
        'publish_from',
        'publish_to',
        'views',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'publish_from' => 'datetime',
        'publish_to'   => 'datetime',
    ];
}
