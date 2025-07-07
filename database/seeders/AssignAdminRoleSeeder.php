<?php

namespace admin\admin_role_permissions\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use admin\admin_role_permissions\Models\Role;
use admin\admin_auth\Models\Admin;

class AssignAdminRoleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::first();

        if (!$admin) {
            $this->command->warn('No admin found. Please seed the admin table first.');
            return;
        }

        // Get or create a role
        $role = Role::firstOrCreate(['name' => 'Super Admin', 'status' => config('admin_role_permissions.status.active')]);

        // Attach role to admin (pivot table: role_admin)
        $admin->roles()->syncWithoutDetaching([$role->id]);

        $this->command->info("Assigned '{$role->name}' role to admin: {$admin->email}");
    }
}
