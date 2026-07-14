<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define Granular Modules
        // These are the core features of the ERP.
        $modules = [
            'dashboard' => ['view'], // Dashboard only needs view
            'academic_class' => ['view', 'create', 'edit', 'delete'],
            'academic_section' => ['view', 'create', 'edit', 'delete'],
            'academic_subject' => ['view', 'create', 'edit', 'delete'],
            'academic_routine' => ['view', 'create', 'edit', 'delete'],
            'student_admission' => ['view', 'create', 'edit', 'delete'],
            'student_list' => ['view', 'create', 'edit', 'delete'],
            'attendance_student' => ['view', 'create', 'edit', 'delete'],
            'attendance_staff' => ['view', 'create', 'edit', 'delete'],
            'exam_list' => ['view', 'create', 'edit', 'delete'],
            'exam_marks' => ['view', 'create', 'edit', 'delete'],
            'exam_results' => ['view', 'create', 'edit', 'delete'],
            'hr_staff' => ['view', 'create', 'edit', 'delete'],
            'finance_fees' => ['view', 'create', 'edit', 'delete'],
            'finance_invoices' => ['view', 'create', 'edit', 'delete'],
            'finance_salary' => ['view', 'create', 'edit', 'delete'],
            'finance_expense_category' => ['view', 'create', 'edit', 'delete'],
            'finance_expenses' => ['view', 'create', 'edit', 'delete'],
            'finance_report' => ['view'], // Report is view only
            'communication_sms' => ['view', 'create'],
            'communication_notice' => ['view', 'create', 'edit', 'delete'],
            'settings_general' => ['view', 'edit'],
            'settings_role' => ['view', 'create', 'edit', 'delete'],
        ];

        // 2. Create Permissions
        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$module}.{$action}", 'guard_name' => 'web']);
            }
        }

        // 3. Create Super Admin Role
        // We DO NOT assign permissions here. We will use Gate::before in AppServiceProvider.
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);

        // 4. Create Standard Admin Roles with Granular Permissions
        $roleAccountant = Role::firstOrCreate(['name' => 'accountant', 'guard_name' => 'web']);
        $roleAccountant->givePermissionTo([
            'dashboard.view',
            'finance_fees.view', 'finance_fees.create', 'finance_fees.edit',
            'finance_invoices.view', 'finance_invoices.create', 'finance_invoices.edit',
            'finance_salary.view', 'finance_salary.create',
            'finance_expense_category.view', 'finance_expense_category.create', 'finance_expense_category.edit',
            'finance_expenses.view', 'finance_expenses.create', 'finance_expenses.edit',
            'finance_report.view'
        ]);

        $roleTeacherAdmin = Role::firstOrCreate(['name' => 'academic-admin', 'guard_name' => 'web']);
        $roleTeacherAdmin->givePermissionTo([
            'dashboard.view',
            'academic_class.view', 'academic_section.view', 'academic_subject.view', 'academic_routine.view',
            'attendance_student.view', 'attendance_student.create', 'attendance_student.edit',
            'exam_list.view', 'exam_marks.view', 'exam_marks.create', 'exam_marks.edit',
            'exam_results.view'
        ]);

        // 5. Create Entity Roles (Student, Teacher, Parent)
        // These roles should NOT have granular system permissions. They rely on Laravel Policies 
        // to view their own data based on their user_type and ID.
        Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'parent', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'public', 'guard_name' => 'web']);

        // 6. Create Super Admin User
        $user = User::firstOrCreate(
            ['phone' => '01700000000'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@school.com',
                'password' => Hash::make('password'),
                'user_type' => 'super-admin',
                'is_active' => 1,
            ]
        );
        $user->assignRole($roleSuperAdmin);
    }
}
