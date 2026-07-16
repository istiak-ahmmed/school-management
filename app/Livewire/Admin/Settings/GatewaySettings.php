<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Settings\SmsSettings;
use App\Settings\EmailSettings;

#[Layout('admin.layouts.app')]
class GatewaySettings extends Component
{
    public $sms_api_url;
    public $sms_api_token;
    public $sms_sender_id;
    public $sms_is_active;

    public $email_mailer;
    public $email_host;
    public $email_port;
    public $email_username;
    public $email_password;
    public $email_encryption;
    public $email_from_address;
    public $email_from_name;
    public $email_is_active;

    public function mount(SmsSettings $smsSettings, EmailSettings $emailSettings)
    {
        $this->sms_api_url = $smsSettings->api_url;
        $this->sms_api_token = $smsSettings->api_token;
        $this->sms_sender_id = $smsSettings->sender_id;
        $this->sms_is_active = $smsSettings->is_active;

        $this->email_mailer = $emailSettings->mailer;
        $this->email_host = $emailSettings->host;
        $this->email_port = $emailSettings->port;
        $this->email_username = $emailSettings->username;
        $this->email_password = $emailSettings->password;
        $this->email_encryption = $emailSettings->encryption;
        $this->email_from_address = $emailSettings->from_address;
        $this->email_from_name = $emailSettings->from_name;
        $this->email_is_active = $emailSettings->is_active;
    }

    public function saveSms(SmsSettings $smsSettings)
    {
        $this->validate([
            'sms_api_url' => 'nullable|url',
            'sms_api_token' => 'nullable|string',
            'sms_sender_id' => 'nullable|string',
            'sms_is_active' => 'boolean',
        ]);

        $smsSettings->api_url = (string) $this->sms_api_url;
        $smsSettings->api_token = (string) $this->sms_api_token;
        $smsSettings->sender_id = (string) $this->sms_sender_id;
        $smsSettings->is_active = (bool) $this->sms_is_active;
        $smsSettings->save();

        session()->flash('message', 'SMS Gateway settings updated successfully.');
    }

    public function saveEmail(EmailSettings $emailSettings)
    {
        $this->validate([
            'email_mailer' => 'required|string',
            'email_host' => 'required|string',
            'email_port' => 'required|numeric',
            'email_username' => 'nullable|string',
            'email_password' => 'nullable|string',
            'email_encryption' => 'nullable|string',
            'email_from_address' => 'required|email',
            'email_from_name' => 'required|string',
            'email_is_active' => 'boolean',
        ]);

        $emailSettings->mailer = $this->email_mailer;
        $emailSettings->host = $this->email_host;
        $emailSettings->port = $this->email_port;
        $emailSettings->username = (string) $this->email_username;
        $emailSettings->password = (string) $this->email_password;
        $emailSettings->encryption = (string) $this->email_encryption;
        $emailSettings->from_address = $this->email_from_address;
        $emailSettings->from_name = $this->email_from_name;
        $emailSettings->is_active = (bool) $this->email_is_active;
        $emailSettings->save();

        session()->flash('message', 'Email Gateway settings updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.settings.gateway-settings');
    }
}
