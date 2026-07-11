<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'manage system',
            'manage users',
            'manage academics',
            'manage finance',
            'manage attendance',
            'view attendance',
            'manage results',
            'view results',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // create roles and assign created permissions
        $roleSuperAdmin = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $roleSuperAdmin->givePermissionTo(\Spatie\Permission\Models\Permission::all());

        $roleAdmin = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $roleAdmin->givePermissionTo(['manage users', 'manage academics', 'manage attendance', 'manage results']);

        $roleAccountant = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'accountant', 'guard_name' => 'web']);
        $roleAccountant->givePermissionTo(['manage finance']);

        $roleTeacher = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
        $roleTeacher->givePermissionTo(['manage attendance', 'manage results']);

        $roleStudent = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
        $roleStudent->givePermissionTo(['view attendance', 'view results']);

        $rolePublic = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'public', 'guard_name' => 'web']);

        // Create a super admin user
        $user = \App\Models\User::firstOrCreate(
            ['phone' => '01700000000'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@school.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'user_type' => 'super-admin',
                'is_active' => 1,
            ]
        );
        $user->assignRole($roleSuperAdmin);
    }
}
