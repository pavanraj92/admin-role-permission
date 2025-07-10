<?php

namespace Admin\AdminRolePermissions\Database\Seeders;

use Illuminate\Database\Seeder;
use Admin\AdminRolePermissions\Database\Seeders\AssignAdminRoleSeeder;
use Admin\AdminRolePermissions\Database\Seeders\AdminPermissionSeeder;
use Admin\AdminRolePermissions\Database\Seeders\AdminRoleSeeder;

class AdminRolePermissionDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            AdminPermissionSeeder::class,
            AdminRoleSeeder::class,
            AssignAdminRoleSeeder::class,
        ]);
    }
}
