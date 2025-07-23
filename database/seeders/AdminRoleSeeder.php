<?php

namespace Admin\AdminRolePermissions\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            DB::table('roles')->updateOrInsert(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
