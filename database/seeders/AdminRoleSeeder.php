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
        $now = now();

        $roles = [
            [
                'name' => 'Super Admin',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
