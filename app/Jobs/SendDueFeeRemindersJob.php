<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Models\SmsLog;
use App\Settings\SmsSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendDueFeeRemindersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(SmsSettings $settings): void
    {
        if (!$settings->due_fee_reminder_active || !$settings->is_active || empty($settings->api_url)) {
            Log::info('SendDueFeeRemindersJob aborted: SMS service or Due Fee Reminder is not active.');
            return;
        }

        // Get all invoices that are unpaid or partially paid
        $invoices = Invoice::with(['student.user'])
            ->whereIn('status', ['unpaid', 'partial'])
            ->get();

        // Group by student to calculate total due
        $studentDues = [];
        foreach ($invoices as $invoice) {
            $studentId = $invoice->student_id;
            if (!isset($studentDues[$studentId])) {
                $studentDues[$studentId] = [
                    'student' => $invoice->student,
                    'total_due' => 0,
                ];
            }
            $studentDues[$studentId]['total_due'] += $invoice->remaining_amount;
        }

        foreach ($studentDues as $dueInfo) {
            $student = $dueInfo['student'];
            $totalDue = $dueInfo['total_due'];

            if ($totalDue <= 0) continue;

            $phone = $student->phone ?? ($student->user->phone ?? null);
            if (empty($phone)) continue;

            $message = "Dear {$student->user->name}, you have a total due fee of {$totalDue} Tk. Please pay your dues as soon as possible.";

            // Log entry
            $log = SmsLog::create([
                'to_number' => $phone,
                'message' => $message,
                'status' => 'pending',
            ]);

            try {
                // Mock API Call
                $response = ['status' => 'success', 'message_id' => uniqid()];

                $log->update([
                    'status' => 'sent',
                    'gateway_response' => $response,
                    'sent_at' => now(),
                ]);
            } catch (\Exception $e) {
                Log::error("Due Fee SMS failed for {$phone}: " . $e->getMessage());
                $log->update([
                    'status' => 'failed',
                    'gateway_response' => ['error' => $e->getMessage()],
                ]);
            }
        }

        Log::info('SendDueFeeRemindersJob completed successfully.');
    }
}
