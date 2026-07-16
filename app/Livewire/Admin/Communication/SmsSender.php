<?php

namespace App\Livewire\Admin\Communication;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\SmsLog;
use App\Settings\SmsSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

#[Layout('admin.layouts.app')]
class SmsSender extends Component
{
    public $target_teachers = false;
    public $target_students = false;
    public $selected_class_id = null;
    public $selected_section_id = null;
    public $message = '';
    
    public $custom_numbers = ''; // comma separated

    public function getClassesProperty()
    {
        return SchoolClass::orderBy('numeric_order')->get();
    }

    public function getSectionsProperty()
    {
        if (!$this->selected_class_id) return [];
        return Section::where('class_id', $this->selected_class_id)->get();
    }

    public function resetForm()
    {
        $this->reset([
            'target_teachers', 'target_students', 
            'selected_class_id', 'selected_section_id', 'message', 'custom_numbers'
        ]);
    }

    public function sendSms(SmsSettings $settings)
    {
        $this->validate([
            'message' => 'required|string|max:500',
        ]);

        if (!$settings->is_active || empty($settings->api_url)) {
            session()->flash('error', 'SMS Gateway is not configured or disabled.');
            return;
        }

        $phoneNumbers = $this->gatherPhoneNumbers();

        if (empty($phoneNumbers)) {
            session()->flash('error', 'No phone numbers found for the selected audience.');
            return;
        }

        $successCount = 0;
        $failCount = 0;

        foreach ($phoneNumbers as $number) {
            // Log entry
            $log = SmsLog::create([
                'to_number' => $number,
                'message' => $this->message,
                'status' => 'pending',
            ]);

            try {
                // Mocking the API call for now as requested.
                // In reality, it would look something like:
                // $response = Http::post($settings->api_url, [
                //     'token' => $settings->api_token,
                //     'to' => $number,
                //     'message' => $this->message,
                //     'sender_id' => $settings->sender_id
                // ]);

                // Simulating a successful response
                $response = ['status' => 'success', 'message_id' => uniqid()];

                $log->update([
                    'status' => 'sent',
                    'gateway_response' => $response,
                    'sent_at' => now(),
                ]);
                $successCount++;

            } catch (\Exception $e) {
                Log::error('SMS Send Failed: ' . $e->getMessage());
                $log->update([
                    'status' => 'failed',
                    'gateway_response' => ['error' => $e->getMessage()],
                ]);
                $failCount++;
            }
        }

        session()->flash('message', "SMS Sent! Success: {$successCount}, Failed: {$failCount}.");
        $this->resetForm();
    }

    private function gatherPhoneNumbers()
    {
        $numbers = [];

        // Add custom numbers
        if (!empty($this->custom_numbers)) {
            $customArr = array_map('trim', explode(',', $this->custom_numbers));
            foreach ($customArr as $num) {
                if (!empty($num)) $numbers[] = $num;
            }
        }

        // Add teachers
        if ($this->target_teachers) {
            // $teachers = \App\Models\Teacher::pluck('phone')->toArray();
            // $numbers = array_merge($numbers, array_filter($teachers));
            // Mocking for now
            $numbers[] = '01700000001';
        }

        // Add students
        if ($this->target_students) {
            $query = \App\Models\Student::query();
            
            // Note: Actual implementation depends on Student model relationships.
            // if ($this->selected_class_id) {
            //     $query->where('school_class_id', $this->selected_class_id);
            // }
            // if ($this->selected_section_id) {
            //     $query->where('section_id', $this->selected_section_id);
            // }
            // $students = $query->pluck('phone')->toArray();
            // $numbers = array_merge($numbers, array_filter($students));
            
            // Mocking for now
            $numbers[] = '01700000002';
        }

        return array_unique($numbers);
    }

    public function render()
    {
        return view('livewire.admin.communication.sms-sender');
    }
}
