<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send an SMS to the given phone number.
     * Currently logs the message for development. Replace with real gateway logic.
     *
     * @param string $phone   BD phone number (e.g., 01XXXXXXXXX)
     * @param string $message SMS message body (max 160 chars per segment)
     */
    public function send(string $phone, string $message): bool
    {
        // ---------------------------------------------------------------
        // TODO: Replace with real SMS gateway (e.g., SSL Commerz, bKash,
        //       Infobip, Twilio, etc.)
        // ---------------------------------------------------------------
        // Example (Infobip REST):
        // Http::post('https://api.infobip.com/sms/2/text/advanced', [...])
        // ---------------------------------------------------------------

        Log::channel('daily')->info('[SMS] To: ' . $phone . ' | Message: ' . $message);

        // Return true so the rest of the workflow continues in development.
        return true;
    }

    /**
     * Send SMS confirmation after admission application is submitted.
     */
    public function sendApplicationConfirmation(string $phone, string $applicationNo, string $applicantName): bool
    {
        $message = "প্রিয় অভিভাবক, {$applicantName}-এর ভর্তির আবেদন সফলভাবে গ্রহণ করা হয়েছে। আবেদন নম্বর: {$applicationNo}। আমরা শীঘ্রই যোগাযোগ করব।";

        return $this->send($phone, $message);
    }

    /**
     * Send welcome SMS after student is accepted and account is created.
     */
    public function sendWelcomeSms(string $phone, string $studentName, string $admissionNo, string $password): bool
    {
        $message = "স্বাগতম! {$studentName}-এর ভর্তি সম্পন্ন হয়েছে। ভর্তি নম্বর: {$admissionNo}। লগইন পাসওয়ার্ড: {$password}। স্কুল পোর্টালে লগইন করুন।";

        return $this->send($phone, $message);
    }

    /**
     * Send rejection SMS with the review note/reason.
     */
    public function sendRejectionSms(string $phone, string $applicantName, string $reason): bool
    {
        $message = "দুঃখিত, {$applicantName}-এর ভর্তির আবেদন গৃহীত হয়নি। কারণ: {$reason}। আরো তথ্যের জন্য স্কুলে যোগাযোগ করুন।";

        return $this->send($phone, $message);
    }
}
