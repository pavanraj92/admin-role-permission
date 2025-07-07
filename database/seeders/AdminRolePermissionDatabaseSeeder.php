<?php

namespace admin\admin_role_permissions\database\seeders;

use Illuminate\Database\Seeder;

class AdminRolePermissionDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            AssignAdminRoleSeeder::class,
        ]);
    }
}
