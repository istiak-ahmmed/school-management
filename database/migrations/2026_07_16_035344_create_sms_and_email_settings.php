<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // SMS Settings
        $this->migrator->add('sms.api_url', '');
        $this->migrator->add('sms.api_token', '');
        $this->migrator->add('sms.sender_id', '');
        $this->migrator->add('sms.is_active', false);

        // Email Settings
        $this->migrator->add('email.mailer', 'smtp');
        $this->migrator->add('email.host', 'smtp.mailtrap.io');
        $this->migrator->add('email.port', 2525);
        $this->migrator->add('email.username', '');
        $this->migrator->add('email.password', '');
        $this->migrator->add('email.encryption', 'tls');
        $this->migrator->add('email.from_address', 'hello@example.com');
        $this->migrator->add('email.from_name', 'School App');
        $this->migrator->add('email.is_active', false);
    }

    public function down(): void
    {
        // SMS Settings
        $this->migrator->delete('sms.api_url');
        $this->migrator->delete('sms.api_token');
        $this->migrator->delete('sms.sender_id');
        $this->migrator->delete('sms.is_active');

        // Email Settings
        $this->migrator->delete('email.mailer');
        $this->migrator->delete('email.host');
        $this->migrator->delete('email.port');
        $this->migrator->delete('email.username');
        $this->migrator->delete('email.password');
        $this->migrator->delete('email.encryption');
        $this->migrator->delete('email.from_address');
        $this->migrator->delete('email.from_name');
        $this->migrator->delete('email.is_active');
    }
};
