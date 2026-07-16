<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'category',
        'audience',
        'attachment_path',
        'is_pinned',
        'is_published',
        'publish_from',
        'publish_to',
        'is_sms_sent',
        'is_email_sent',
        'created_by',
        'views',
    ];

    protected $casts = [
        'audience' => 'array',
        'is_pinned' => 'boolean',
        'is_published' => 'boolean',
        'is_sms_sent' => 'boolean',
        'is_email_sent' => 'boolean',
        'publish_from' => 'datetime',
        'publish_to'   => 'datetime',
    ];
}
