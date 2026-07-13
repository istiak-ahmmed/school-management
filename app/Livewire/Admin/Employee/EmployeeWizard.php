<?php

namespace App\Livewire\Admin\Employee;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Staff;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

#[Layout('admin.layouts.app')]
#[Title('কর্মচারী ও শিক্ষক নিয়োগ')]
class EmployeeWizard extends Component
{
    use WithFileUploads;

    public $currentStep = 1;

    // Step 1: Role & Official Info
    public $role = 'teacher'; // teacher or staff
    public $employee_id;
    public $designation;
    public $contract_type = 1; // 1=permanent, 2=contractual, 3=part_time
    public $department; // Only for staff
    public $specialization; // Only for teacher
    public $joining_date;
    public $status = 1;

    // Step 2: Personal Info
    public $name;
    public $email;
    public $phone;
    public $nid;
    public $photo;

    // Step 3: Academic Qualification (JSON array)
    public $qualifications = [];

    // Step 4: Salary & Compensation
    public $basic_salary = 0;
    
    // Bank
    public $bank_name;
    public $bank_ac_name;
    public $bank_ac_no;
    public $bank_routing_no;

    // MFS
    public $mfs_name; // bKash, Nagad, Rocket
    public $mfs_ac_no;

    public function mount()
    {
        // Add one empty qualification to start with
        $this->addQualification();
    }

    public function addQualification()
    {
        $this->qualifications[] = [
            'degree_name' => '',
            'institution' => '',
            'passing_year' => '',
            'result' => ''
        ];
    }

    public function removeQualification($index)
    {
        unset($this->qualifications[$index]);
        $this->qualifications = array_values($this->qualifications);
    }

    public function nextStep()
    {
        if ($this->currentStep == 1) {
            $this->validate([
                'role' => 'required|in:teacher,staff',
                'employee_id' => 'required|string|max:20',
                'designation' => 'required|string|max:100',
                'contract_type' => 'required|integer',
                'joining_date' => 'required|date',
                'status' => 'required|integer',
                'department' => 'required_if:role,staff',
                'specialization' => 'required_if:role,teacher',
            ]);
        } elseif ($this->currentStep == 2) {
            $this->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20|unique:users,phone',
                'email' => 'nullable|email|unique:users,email',
                'nid' => 'required|string|max:20',
                'photo' => 'nullable|image|max:1024', // 1MB Max
            ]);
        } elseif ($this->currentStep == 3) {
            $this->validate([
                'qualifications.*.degree_name' => 'required|string',
                'qualifications.*.institution' => 'required|string',
                'qualifications.*.passing_year' => 'required|integer',
                'qualifications.*.result' => 'required|string',
            ]);
        }

        $this->currentStep++;
    }

    public function prevStep()
    {
        $this->currentStep--;
    }

    public function submit()
    {
        $this->validate([
            'basic_salary' => 'required|numeric',
            'bank_name' => 'nullable|string',
            'bank_ac_name' => 'nullable|string',
            'bank_ac_no' => 'nullable|string',
            'bank_routing_no' => 'nullable|string',
            'mfs_name' => 'nullable|string',
            'mfs_ac_no' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Create User
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'password' => Hash::make($this->phone),
                'user_type' => $this->role,
                'is_active' => $this->status == 1,
            ]);
            
            $user->assignRole($this->role);

            $photoPath = null;
            if ($this->photo) {
                $photoPath = $this->photo->store('employees', 'public');
            }

            $bankAccount = null;
            if ($this->bank_name || $this->bank_ac_no) {
                $bankAccount = [
                    'bank_name' => $this->bank_name,
                    'account_name' => $this->bank_ac_name,
                    'account_no' => $this->bank_ac_no,
                    'routing_no' => $this->bank_routing_no,
                ];
            }

            $mfsAccount = null;
            if ($this->mfs_name || $this->mfs_ac_no) {
                $mfsAccount = [
                    'mfs_name' => $this->mfs_name,
                    'account_no' => $this->mfs_ac_no,
                ];
            }

            $commonData = [
                'user_id' => $user->id,
                'employee_id' => $this->employee_id,
                'designation' => $this->designation,
                'contract_type' => $this->contract_type,
                'joining_date' => $this->joining_date,
                'basic_salary' => $this->basic_salary,
                'photo_path' => $photoPath,
                'nid' => $this->nid,
                'qualification' => json_encode($this->qualifications),
                'bank_account' => json_encode($bankAccount),
                'mfs_account' => json_encode($mfsAccount),
                'status' => $this->status,
            ];

            if ($this->role === 'teacher') {
                $commonData['specialization'] = $this->specialization;
                Teacher::create($commonData);
            } else {
                $commonData['department'] = $this->department;
                Staff::create($commonData);
            }

            DB::commit();

            session()->flash('success', 'সফলভাবে নিয়োগ সম্পন্ন হয়েছে!');
            return redirect()->route('admin.dashboard'); 

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'কোথাও সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.employee.employee-wizard');
    }
}
