<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('sms.due_fee_reminder_active', false);
    }
    
    public function down(): void
    {
        $this->migrator->delete('sms.due_fee_reminder_active');
    }
};
