<?php

namespace Packages\Admin\AdminRolePermissions\database\seeders;

use Illuminate\Database\Seeder;
use Packages\Admin\AdminRolePermissions\database\seeders\AssignAdminRoleSeeder;

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
