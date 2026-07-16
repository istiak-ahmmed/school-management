<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'to_number',
        'message',
        'status',
        'gateway_response',
        'sent_at',
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'sent_at' => 'datetime',
    ];
}
