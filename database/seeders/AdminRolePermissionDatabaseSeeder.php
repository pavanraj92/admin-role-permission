<?php

namespace Packages\Admin\AdminRolePermissions\database\seeders;

use Illuminate\Database\Seeder;
use Packages\Admin\AdminRolePermissions\database\seeders\AssignAdminRoleSeeder;
use Packages\Admin\AdminRolePermissions\database\seeders\AdminPermissionSeeder;
use Packages\Admin\AdminRolePermissions\database\seeders\AdminRoleSeeder;

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
