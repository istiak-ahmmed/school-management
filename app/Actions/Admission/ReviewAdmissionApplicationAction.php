<?php

namespace App\Actions\Admission;

use App\Models\AdmissionApplication;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class ReviewAdmissionApplicationAction
{
    public function __construct(private readonly SmsService $smsService)
    {
    }

    /**
     * Accept an application: create user, student, guardian records.
     *
     * @param AdmissionApplication $application
     * @param int $reviewedBy Admin user ID
     * @return Student
     */
    public function accept(AdmissionApplication $application, int $reviewedBy): Student
    {
        return DB::transaction(function () use ($application, $reviewedBy) {
            // Generate a temporary password
            $password = Str::random(8);

            // 1. Create User account
            $user = User::create([
                'name'              => $application->applicant_name,
                'email'             => $application->guardian_email
                    ? $this->generateStudentEmail($application->applicant_name)
                    : $this->generateStudentEmail($application->applicant_name),
                'password'          => Hash::make($password),
                'email_verified_at' => now(),
            ]);

            // Assign student role (Spatie)
            $studentRole = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
            $user->assignRole($studentRole);

            // 2. Generate admission number
            $admissionNo = $this->generateAdmissionNo();

            // 3. Create Student record
            $student = Student::create([
                'user_id'          => $user->id,
                'admission_no'     => $admissionNo,
                'class_id'         => $application->applying_for_class_id,
                'academic_year_id' => $application->academic_year_id,
                'date_birth'       => $application->dob,
                'gender'           => $application->gender,
                'address_present'  => $application->address,
                'status'           => 1, // active
            ]);

            // 4. Find or create Guardian record (matched by phone)
            if ($application->guardian_phone) {
                $guardian = Guardian::firstOrCreate(
                    ['phone' => $application->guardian_phone],
                    [
                        'name'  => $application->guardian_name ?? 'অভিভাবক',
                        'email' => $application->guardian_email,
                    ]
                );

                // Attach guardian to student
                $student->guardians()->syncWithoutDetaching([
                    $guardian->id => [
                        'relation'   => 'father',
                        'is_primary' => true,
                    ],
                ]);
            }

            // 5. Update application status
            $application->update([
                'status'      => 5,
                'reviewed_by' => $reviewedBy,
                'reviewed_at' => Carbon::now(),
                'review_note' => 'আবেদন গৃহীত হয়েছে এবং ভর্তি সম্পন্ন হয়েছে।',
            ]);

            // 6. Send welcome SMS
            if ($application->guardian_phone) {
                $this->smsService->sendWelcomeSms(
                    $application->guardian_phone,
                    $application->applicant_name,
                    $admissionNo,
                    $password
                );
            }

            return $student;
        });
    }

    /**
     * Reject an application with a reason/note.
     *
     * @param AdmissionApplication $application
     * @param int $reviewedBy Admin user ID
     * @param string $note Reason for rejection
     */
    public function reject(AdmissionApplication $application, int $reviewedBy, string $note): void
    {
        $application->update([
            'status'      => 4,
            'reviewed_by' => $reviewedBy,
            'reviewed_at' => Carbon::now(),
            'review_note' => $note,
        ]);

        // Send rejection SMS
        if ($application->guardian_phone) {
            $this->smsService->sendRejectionSms(
                $application->guardian_phone,
                $application->applicant_name,
                $note
            );
        }
    }

    /**
     * Auto-generate a unique admission number: MDS-YYYY-XXXX
     */
    private function generateAdmissionNo(): string
    {
        $year   = now()->year;
        $prefix = "MDS-{$year}-";

        $last = Student::withTrashed()
            ->where('admission_no', 'like', "{$prefix}%")
            ->orderByDesc('id')
            ->lockForUpdate()
            ->first();

        if ($last) {
            $lastSeq = (int) substr($last->admission_no, strlen($prefix));
            $seq     = $lastSeq + 1;
        } else {
            $seq = 1;
        }

        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate a unique student email from their name.
     */
    private function generateStudentEmail(string $name): string
    {
        $base  = strtolower(Str::slug($name, '.'));
        $email = $base . '@student.school.edu.bd';

        // Make sure it's unique
        $count = 1;
        while (User::where('email', $email)->exists()) {
            $email = $base . $count . '@student.school.edu.bd';
            $count++;
        }

        return $email;
    }
}
