<?php

namespace Admin\AdminRolePermissions\Database\Seeders;

use Illuminate\Database\Seeder;
use admin\admin_role_permissions\Models\Role;
use admin\admin_auth\Models\Admin;
use admin\admin_role_permissions\Models\Permission;
use Illuminate\Support\Facades\DB;

class AssignAdminRoleSeeder extends Seeder
{
    /**
     * Assign the Super Admin role to the first admin user.
     */
    public function run(): void
    {
        $admin = DB::table('admins')->orderBy('id')->first();
        $role = DB::table('roles')->where('name', 'Super Admin')->first();

        if (!$admin) {
            $this->command?->warn('No admin found. Please seed the admin table first.');
            return;
        }
        if (!$role) {
            $this->command?->warn('Super Admin role not found. Please seed roles first.');
            return;
        }

        // Assign role to admin (assuming pivot table 'role_admin')
        DB::table('role_admin')->updateOrInsert(
            ['admin_id' => $admin->id, 'role_id' => $role->id],
            [
            'created_at' => now(),
            'updated_at' => now(),
            'status' => 1
            ]
        );

        // Assign all permissions to Super Admin role (assuming pivot table 'permission_role')
        $allPermissions = DB::table('permissions')->pluck('id')->all();
        foreach ($allPermissions as $permissionId) {
            DB::table('permission_role')->updateOrInsert(
            ['role_id' => $role->id, 'permission_id' => $permissionId],
            [
                'created_at' => now(),
                'updated_at' => now(),
                'status' => 1
            ]
            );
        }

        $this->command?->info("Assigned '{$role->name}' role to admin: {$admin->email}");
        $this->command?->info("Assigned all permissions to role: {$role->name}");
    }
}
