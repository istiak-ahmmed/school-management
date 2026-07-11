<?php

namespace App\Livewire\Website;

use App\Actions\Admission\CreateAdmissionApplicationAction;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdmissionForm extends Component
{
    use WithFileUploads;

    public int $currentStep = 1;
    public int $totalSteps  = 4;

    // ── Step 1: Student Info ──────────────────────────────────────────────
    public string $applicant_name = '';
    public ?string $dob = null;
    public ?string $gender = null;
    public $photo = null;

    // ── Step 2: Guardian Info ─────────────────────────────────────────────
    public ?string $guardian_name = null;
    public ?string $guardian_phone = null;
    public ?string $guardian_email = null;
    public ?string $address = null;

    // ── Step 3: Academic Info ─────────────────────────────────────────────
    public ?string $previous_school = null;
    public ?int $applying_for_class_id = null;
    public ?int $academic_year_id = null;

    // ── State ─────────────────────────────────────────────────────────────
    public bool $submitted       = false;
    public string $applicationNo = '';

    public function render()
    {
        return view('livewire.website.admission-form', [
            'classes'       => SchoolClass::orderBy('numeric_order')->get(),
            'academicYears' => AcademicYear::orderByDesc('is_current')->orderByDesc('start_date')->get(),
        ])->layout('layouts.public');
    }

    public function nextStep(): void
    {
        try {
            $this->validateStep($this->currentStep);

            if ($this->currentStep < $this->totalSteps) {
                $this->currentStep++;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'অনুগ্রহ করে সকল সঠিক তথ্য প্রদান করুন।'
            ]);
            throw $e;
        }
    }

    /**
     * Go to previous step.
     */
    public function prevStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function submit(CreateAdmissionApplicationAction $action): void
    {
        try {
            $this->validateStep(1);
            $this->validateStep(2);
            $this->validateStep(3);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'কিছু তথ্য বাদ পড়েছে বা ভুল আছে।'
            ]);
            throw $e;
        }

        $documentsPath = null;

        // Handle photo upload
        if ($this->photo) {
            $path          = $this->photo->store('admission-photos', 'public');
            $documentsPath = [$path];
        }

        $application = $action->execute([
            'applicant_name'         => $this->applicant_name,
            'dob'                    => $this->dob,
            'gender'                 => $this->gender,
            'applying_for_class_id'  => $this->applying_for_class_id,
            'academic_year_id'       => $this->academic_year_id,
            'guardian_name'          => $this->guardian_name,
            'guardian_phone'         => $this->guardian_phone,
            'guardian_email'         => $this->guardian_email,
            'address'                => $this->address,
            'previous_school'        => $this->previous_school,
            'documents_path'         => $documentsPath,
        ]);

        $this->applicationNo = $application->application_no;
        $this->submitted     = true;
    }

    private function validateStep(int $step): void
    {
        match ($step) {
            1 => $this->validate([
                'applicant_name' => 'required|string|max:150',
                'dob'            => 'required|date|before:today',
                'gender'         => 'required|in:1,2,3',
                'photo'          => 'required|image|max:2048',
            ], [
                'applicant_name.required' => 'শিক্ষার্থীর নাম আবশ্যক।',
                'dob.required'            => 'জন্ম তারিখ আবশ্যক।',
                'dob.before'              => 'জন্ম তারিখ আজকের আগে হতে হবে।',
                'gender.required'         => 'লিঙ্গ নির্বাচন করা আবশ্যক।',
                'photo.required'          => 'শিক্ষার্থীর ছবি দেওয়া আবশ্যক।',
                'photo.image'             => 'ছবি অবশ্যই একটি ইমেজ ফাইল হতে হবে।',
                'photo.max'               => 'ছবির সাইজ সর্বোচ্চ ২ MB হতে পারবে।',
            ]),

            2 => $this->validate([
                'guardian_name'  => 'required|string|max:150',
                'guardian_phone' => 'required|string|max:15',
                'guardian_email' => 'nullable|email|max:191',
                'address'        => 'required|string|max:500',
            ], [
                'guardian_name.required'  => 'অভিভাবকের নাম আবশ্যক।',
                'guardian_phone.required' => 'অভিভাবকের ফোন নম্বর আবশ্যক।',
                'address.required'        => 'ঠিকানা আবশ্যক।',
                'guardian_email.email'    => 'ইমেইল ঠিকানা সঠিক নয়।',
            ]),

            3 => $this->validate([
                'previous_school'       => 'nullable|string|max:200',
                'applying_for_class_id' => 'required|exists:classes,id',
                'academic_year_id'      => 'required|exists:academic_years,id',
            ], [
                'applying_for_class_id.required' => 'ভর্তি ইচ্ছুক শ্রেণী নির্বাচন করুন।',
                'academic_year_id.required'      => 'শিক্ষাবর্ষ নির্বাচন করুন।',
            ]),

            default => null,
        };
    }
}
