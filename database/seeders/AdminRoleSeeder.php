<?php

namespace Admin\AdminRolePermissions\Database\Seeders;

use Illuminate\Database\Seeder;
use admin\admin_role_permissions\Models\Role;

class AdminRoleSeeder extends Seeder
{
    /**
     * Seed the roles table with default admin roles.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'status' => config('admin_role_permissions.status.active', 1)],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
