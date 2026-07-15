<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;
use App\Livewire\Traits\Sortable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\Attributes\Layout;

#[Layout('admin.layouts.app')]
class RoleManager extends Component
{
    use Sortable;

    public $showModal = false;
    public $roleId = null;
    public $name = '';
    
    // Array to hold selected permissions [permission_name => true/false]
    public $rolePermissions = [];

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['roleId', 'name', 'rolePermissions']);
        
        // Initialize all permissions to false
        foreach(Permission::all() as $perm) {
            $this->rolePermissions[$perm->name] = false;
        }
        
        $this->showModal = true;
    }

    public function edit(Role $role)
    {
        if ($role->name === 'super-admin') {
            session()->flash('error', 'সুপার এডমিন রোল এডিট করা সম্ভব নয়। (Super Admin role cannot be modified.)');
            return;
        }

        $this->resetValidation();
        $this->roleId = $role->id;
        $this->name = $role->name;
        
        // Reset permissions to false first
        foreach(Permission::all() as $perm) {
            $this->rolePermissions[$perm->name] = false;
        }
        
        // Check assigned permissions
        foreach($role->permissions as $perm) {
            $this->rolePermissions[$perm->name] = true;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|unique:roles,name,' . $this->roleId
        ]);

        if ($this->roleId) {
            $role = Role::findOrFail($this->roleId);
            if ($role->name === 'super-admin') {
                return;
            }
            $role->update(['name' => $this->name]);
        } else {
            $role = Role::create(['name' => $this->name, 'guard_name' => 'web']);
        }

        // Filter out false values and get permission names
        $assignedPermissions = array_keys(array_filter($this->rolePermissions));
        $role->syncPermissions($assignedPermissions);

        session()->flash('message', 'রোল সফলভাবে সংরক্ষিত হয়েছে।');
        $this->showModal = false;
    }

    public function delete(Role $role)
    {
        if (in_array($role->name, ['super-admin', 'student', 'teacher', 'parent'])) {
            session()->flash('error', 'সিস্টেম ডিফল্ট রোল ডিলিট করা যাবে না।');
            return;
        }
        
        $role->delete();
        session()->flash('message', 'রোল সফলভাবে ডিলিট হয়েছে।');
    }

    public function render()
    {
        $roles = Role::with('permissions')->orderBy($this->sortField, $this->sortDirection)->get();
        $allPermissions = Permission::orderBy('name')->get();
        
        // Group permissions by module prefix
        $groupedPermissions = $allPermissions->groupBy(function($perm) {
            return explode('.', $perm->name)[0];
        });

        return view('livewire.admin.settings.role-manager', compact('roles', 'groupedPermissions'));
    }
}
