<?php

namespace App\Livewire\Admin\Student;

use App\Models\AcademicYear;
use App\Models\Guardian;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Enums\Gender;
use App\Enums\BloodGroup;
use App\Enums\Religion;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('admin.layouts.app')]
#[Title('নতুন শিক্ষার্থী ভর্তি')]
class StudentAdmissionWizard extends Component
{
    public $currentStep = 1;
    public $totalSteps = 3;

    // Step 1: Academic Info
    public $academic_year_id;
    public $class_id;
    public $section_id;
    public $admission_no;
    public $roll_no;

    // Step 2: Personal Info
    public $name;
    public $date_birth;
    public $gender = 1; // Default male
    public $blood_group;
    public $religion = 1; // Default Islam
    public $nationality = 'Bangladeshi';
    public $student_phone;
    public $birth_certificate_no;
    public $medical_info;
    public $address_present;
    public $address_permanent;

    // Step 3: Guardian Info
    public $guardian_name;
    public $guardian_phone;
    public $guardian_email;
    public $guardian_occupation;
    public $guardian_relation = 'father';
    public $mother_name;
    public $mother_occupation;

    // Lists
    public $academicYears = [];
    public $classes = [];
    public $sections = [];

    public function mount()
    {
        $this->academicYears = AcademicYear::orderBy('id', 'desc')->get();
        $this->classes = SchoolClass::all();
        
        $activeYear = $this->academicYears->where('is_current', 1)->first();
        if ($activeYear) {
            $this->academic_year_id = $activeYear->id;
        }

        $this->generateAdmissionNo();
    }

    public function generateAdmissionNo()
    {
        // ADM-YEAR-XXXX
        $year = date('Y');
        $lastStudent = Student::where('admission_no', 'like', "ADM-{$year}-%")->orderBy('id', 'desc')->first();
        if ($lastStudent) {
            $lastNumber = intval(substr($lastStudent->admission_no, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        $this->admission_no = "ADM-{$year}-{$newNumber}";
    }

    public function updatedClassId()
    {
        $this->section_id = '';
        $this->sections = Section::where('class_id', $this->class_id)->get();
        $this->suggestRollNo();
    }

    public function updatedSectionId()
    {
        $this->suggestRollNo();
    }

    public function suggestRollNo()
    {
        if ($this->class_id && $this->section_id && $this->academic_year_id) {
            $lastStudent = Student::where('class_id', $this->class_id)
                ->where('section_id', $this->section_id)
                ->where('academic_year_id', $this->academic_year_id)
                ->orderByRaw('CAST(roll_no AS UNSIGNED) DESC')
                ->first();
                
            $this->roll_no = $lastStudent && is_numeric($lastStudent->roll_no) ? intval($lastStudent->roll_no) + 1 : 1;
        } else {
            $this->roll_no = '';
        }
    }

    public function nextStep()
    {
        $this->validateStep();
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function validateStep()
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'academic_year_id' => 'required|exists:academic_years,id',
                'class_id' => 'required|exists:classes,id',
                'section_id' => 'required|exists:sections,id',
                'admission_no' => 'required|string|unique:students,admission_no',
                'roll_no' => 'required|string',
            ]);
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'name' => 'required|string|max:150',
                'date_birth' => 'nullable|date',
                'gender' => ['required', Rule::enum(Gender::class)],
                'blood_group' => ['nullable', Rule::enum(BloodGroup::class)],
                'religion' => ['required', Rule::enum(Religion::class)],
                'student_phone' => 'nullable|string|max:15|unique:users,phone',
                'address_present' => 'required|string',
            ]);
        }
    }

    public function submit()
    {
        $this->validateStep();
        
        $this->validate([
            'guardian_name' => 'required|string|max:150',
            'guardian_phone' => 'required|string|max:15',
        ]);

        try {
            DB::beginTransaction();

            // 1. Create User for Student
            $studentEmail = strtolower($this->admission_no) . '@darulhikmah.com';
            
            $user = User::create([
                'name' => $this->name,
                'email' => $studentEmail,
                'phone' => $this->student_phone ?: null,
                'password' => Hash::make('12345678'),
                'user_type' => 'student',
                'is_active' => 1,
            ]);
            $user->assignRole('student');

            // 2. Create Student Profile
            $student = Student::create([
                'user_id' => $user->id,
                'admission_no' => $this->admission_no,
                'roll_no' => $this->roll_no,
                'class_id' => $this->class_id,
                'section_id' => $this->section_id,
                'academic_year_id' => $this->academic_year_id,
                'date_birth' => $this->date_birth ?: null,
                'gender' => $this->gender,
                'blood_group' => $this->blood_group ?: null,
                'religion' => $this->religion,
                'nationality' => $this->nationality,
                'birth_certificate_no' => $this->birth_certificate_no,
                'address_present' => $this->address_present,
                'address_permanent' => $this->address_permanent ?: $this->address_present,
                'medical_info' => $this->medical_info,
                'status' => 1, // active
            ]);

            // 3. Find or Create Guardian
            $guardian = Guardian::where('phone', $this->guardian_phone)->first();
            
            if (!$guardian) {
                $guardian = Guardian::create([
                    'name' => $this->guardian_name,
                    'phone' => $this->guardian_phone,
                    'email' => $this->guardian_email,
                    'occupation' => $this->guardian_occupation,
                    'relation' => $this->guardian_relation,
                    'mother_name' => $this->mother_name,
                    'mother_occupation' => $this->mother_occupation,
                    'address' => $this->address_present,
                ]);
            }

            // 4. Link Guardian to Student
            DB::table('guardian_student')->updateOrInsert(
                [
                    'guardian_id' => $guardian->id,
                    'student_id' => $student->id,
                ],
                [
                    'relation' => $this->guardian_relation,
                    'is_primary' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            DB::commit();

            session()->flash('success', 'শিক্ষার্থী সফলভাবে ভর্তি করা হয়েছে!');
            return $this->redirect(route('admin.students'), navigate: true);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'ভুল হয়েছে: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.student.student-admission-wizard');
    }
}
