<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SmsSettings extends Settings
{
    public string $api_url;
    public string $api_token;
    public string $sender_id;
    public bool $is_active;
    public bool $due_fee_reminder_active;

    public static function group(): string
    {
        return 'sms';
    }
}
