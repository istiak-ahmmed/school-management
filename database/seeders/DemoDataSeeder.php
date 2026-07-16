<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Guardian;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use App\Enums\Gender;
use App\Enums\BloodGroup;
use App\Enums\Religion;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting Demo Data Seeder...');

        // 1. Setup Academic Year
        $academicYear = AcademicYear::firstOrCreate(
            ['is_current' => 1],
            [
                'name' => date('Y') . '-' . (date('Y') + 1),
                'start_date' => date('Y') . '-01-01',
                'end_date' => date('Y') . '-12-31'
            ]
        );
        $this->command->info('Academic Year ensured.');

        // 2. Setup Classes, Sections, Subjects
        $classesData = [
            ['name' => 'Class 6', 'numeric_order' => 6],
            ['name' => 'Class 7', 'numeric_order' => 7],
            ['name' => 'Class 8', 'numeric_order' => 8],
        ];

        $classIds = [];
        foreach ($classesData as $class) {
            $class['academic_year_id'] = $academicYear->id;
            $class['is_active'] = 1;
            $schoolClass = SchoolClass::firstOrCreate(['numeric_order' => $class['numeric_order']], $class);
            $classIds[] = $schoolClass->id;

            // Sections
            Section::firstOrCreate(['class_id' => $schoolClass->id, 'name' => 'A'], ['capacity' => 40, 'is_active' => 1]);
            Section::firstOrCreate(['class_id' => $schoolClass->id, 'name' => 'B'], ['capacity' => 40, 'is_active' => 1]);

            // Subjects
            $subjects = ['Bangla', 'English', 'Mathematics', 'Science'];
            foreach ($subjects as $idx => $sub) {
                Subject::firstOrCreate([
                    'class_id' => $schoolClass->id,
                    'name' => $sub
                ], [
                    'code' => strtoupper(substr($sub, 0, 3)) . '-' . $schoolClass->numeric_order,
                    'subject_type' => 1,
                    'full_marks' => 100,
                    'pass_marks' => 33,
                    'is_active' => 1
                ]);
            }
        }
        $this->command->info('Classes, Sections, and Subjects seeded.');

        // Roles setup (ensure they exist)
        $teacherRole = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
        $studentRole = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        // 3. Setup 5 Teachers
        $teacherIds = [];
        $teacherUsers = [];
        for ($i = 1; $i <= 5; $i++) {
            $user = User::firstOrCreate(
                ['email' => "teacher{$i}@darulhikmah.com"],
                [
                    'name' => "Demo Teacher {$i}",
                    'password' => Hash::make('12345678'),
                    'user_type' => 'teacher',
                    'is_active' => 1,
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole($teacherRole);

            $teacher = Teacher::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'employee_id' => "EMP-T-100{$i}",
                    'designation' => 'Senior Teacher',
                    'qualification' => ['MA', 'B.Ed'],
                    'joining_date' => Carbon::now()->subYears(rand(1, 5)),
                    'basic_salary' => rand(25000, 40000),
                    'status' => 1,
                ]
            );

            $teacherIds[] = $teacher->id;
            $teacherUsers[] = $user->id;
        }

        // Assign Form Teachers & Subjects
        $allSections = Section::all();
        foreach ($allSections as $index => $section) {
            // Assign random teacher as form teacher
            $teacherUserId = $teacherUsers[$index % count($teacherUsers)];
            $section->update(['teacher_id' => $teacherUserId]);
        }

        $allSubjects = Subject::all();
        foreach ($allSubjects as $subject) {
            $teacherId = $teacherIds[$subject->id % count($teacherIds)];
            DB::table('teacher_subjects')->updateOrInsert(
                ['teacher_id' => $teacherId, 'class_id' => $subject->class_id, 'subject_id' => $subject->id],
                ['section_id' => null] // For all sections of that class
            );
        }
        $this->command->info('5 Teachers and their assignments seeded.');

        // 4. Setup 20 Students
        $bloodGroups = [1, 2, 3, 4, 5, 6, 7, 8]; // Enums or IDs based on logic
        $sectionsForStudents = Section::all();
        $lastStudent = Student::where('admission_no', 'like', "ADM-" . date('Y') . "-%")->orderBy('id', 'desc')->first();
        $studentCounter = $lastStudent ? intval(substr($lastStudent->admission_no, -4)) + 1 : 1;

        for ($i = 1; $i <= 20; $i++) {
            $section = $sectionsForStudents->random();
            $classId = $section->class_id;
            
            $user = User::firstOrCreate(
                ['email' => "student{$i}@darulhikmah.com"],
                [
                    'name' => "Demo Student {$i}",
                    'password' => Hash::make('12345678'),
                    'user_type' => 'student',
                    'is_active' => 1,
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole($studentRole);

            $student = Student::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'admission_no' => "ADM-" . date('Y') . "-" . str_pad($studentCounter++, 4, '0', STR_PAD_LEFT),
                    'roll_no' => $i,
                    'class_id' => $classId,
                    'section_id' => $section->id,
                    'academic_year_id' => $academicYear->id,
                    'date_birth' => Carbon::now()->subYears(12 + ($classId % 3))->format('Y-m-d'),
                    'gender' => Gender::cases()[array_rand(Gender::cases())]->value,
                    'blood_group' => BloodGroup::cases()[array_rand(BloodGroup::cases())]->value,
                    'religion' => Religion::ISLAM->value,
                    'nationality' => 'Bangladeshi',
                    'address_present' => 'Demo Address ' . $i,
                    'status' => 1,
                ]
            );

            // Create Guardian
            $guardian = Guardian::firstOrCreate(
                ['phone' => "017000000" . str_pad($i, 2, '0', STR_PAD_LEFT)],
                [
                    'name' => "Demo Guardian {$i}",
                    'email' => "guardian{$i}@example.com",
                    'occupation' => 'Business',
                    'relation' => 'father',
                    'address' => 'Demo Address ' . $i,
                ]
            );

            // Attach guardian
            DB::table('guardian_student')->updateOrInsert(
                ['guardian_id' => $guardian->id, 'student_id' => $student->id],
                ['relation' => 'father', 'is_primary' => true, 'created_at' => now(), 'updated_at' => now()]
            );
        }
        $this->command->info('20 Students and Guardians seeded.');
        $this->command->info('Demo Data Seeding Completed Successfully! 🎉');
    }
}
