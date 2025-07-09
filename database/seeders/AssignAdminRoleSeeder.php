<?php

namespace Packages\Admin\AdminRolePermissions\database\seeders;

use Illuminate\Database\Seeder;
use admin\admin_role_permissions\Models\Role;
use admin\admin_auth\Models\Admin;
use admin\admin_role_permissions\Models\Permission;

class AssignAdminRoleSeeder extends Seeder
{
    /**
     * Assign the Super Admin role to the first admin user.
     */
    public function run(): void
    {
        $admin = Admin::first();
        $role = Role::where('name', 'Super Admin')->first();

        if (!$admin) {
            $this->command?->warn('No admin found. Please seed the admin table first.');
            return;
        }
        if (!$role) {
            $this->command?->warn('Super Admin role not found. Please seed roles first.');
            return;
        }

        $admin->roles()->syncWithoutDetaching([$role->id]);

        // Assign all permissions to Super Admin
        $allPermissions = Permission::pluck('id')->all();
        $role->permissions()->syncWithoutDetaching($allPermissions);

        $this->command?->info("Assigned '{$role->name}' role to admin: {$admin->email}");
        $this->command?->info("Assigned all permissions to role: {$role->name}");
    }
}
