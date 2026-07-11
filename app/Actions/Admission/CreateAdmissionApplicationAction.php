<?php

namespace App\Actions\Admission;

use App\Models\AdmissionApplication;
use App\Services\SmsService;
use Illuminate\Support\Carbon;

class CreateAdmissionApplicationAction
{
    public function __construct(private readonly SmsService $smsService)
    {
    }

    /**
     * Execute the action: save the admission application and send confirmation SMS.
     *
     * @param array $data Validated form data
     * @return AdmissionApplication
     */
    public function execute(array $data): AdmissionApplication
    {
        $applicationNo = $this->generateApplicationNo();

        $application = AdmissionApplication::create([
            'application_no'      => $applicationNo,
            'applicant_name'      => $data['applicant_name'],
            'dob'                 => $data['dob'] ?? null,
            'gender'              => $data['gender'] ?? null,
            'applying_for_class_id' => $data['applying_for_class_id'] ?? null,
            'academic_year_id'    => $data['academic_year_id'] ?? null,
            'guardian_name'       => $data['guardian_name'] ?? null,
            'guardian_phone'      => $data['guardian_phone'] ?? null,
            'guardian_email'      => $data['guardian_email'] ?? null,
            'address'             => $data['address'] ?? null,
            'previous_school'     => $data['previous_school'] ?? null,
            'documents_path'      => $data['documents_path'] ?? null,
            'status'              => 1,
            'submitted_at'        => Carbon::now(),
        ]);

        // Send confirmation SMS to guardian
        if (! empty($data['guardian_phone'])) {
            $this->smsService->sendApplicationConfirmation(
                $data['guardian_phone'],
                $applicationNo,
                $data['applicant_name']
            );
        }

        return $application;
    }

    /**
     * Auto-generate a unique application number: APP-YYYY-XXXX
     */
    private function generateApplicationNo(): string
    {
        $year   = now()->year;
        $prefix = "APP-{$year}-";

        $last = AdmissionApplication::withTrashed()
            ->where('application_no', 'like', "{$prefix}%")
            ->orderByDesc('id')
            ->lockForUpdate()
            ->first();

        if ($last) {
            $lastSeq = (int) substr($last->application_no, strlen($prefix));
            $seq     = $lastSeq + 1;
        } else {
            $seq = 1;
        }

        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
