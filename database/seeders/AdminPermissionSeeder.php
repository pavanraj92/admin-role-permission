<?php

namespace Admin\AdminRolePermissions\Database\Seeders;

use Illuminate\Database\Seeder;
use admin\admin_role_permissions\Models\Permission;

class AdminPermissionSeeder extends Seeder
{
    /**
     * Seed the permissions table with default admin permissions.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'Dashboard', 'slug' => 'dashboard'],
            ['name' => 'Admin Manager List', 'slug' => 'admin_manager_list'],
            ['name' => 'Roles Manager List', 'slug' => 'roles_manager_list'],
            ['name' => 'Permission Manager List', 'slug' => 'permission_manager_list'],
            ['name' => 'Users Manager List', 'slug' => 'users_manager_list'],
            ['name' => 'Categories Manager List', 'slug' => 'categories_manager_list'],
            ['name' => 'Pages Manager List', 'slug' => 'pages_manager_list'],
            ['name' => 'Emails Manager List', 'slug' => 'emails_manager_list'],
            ['name' => 'Faqs Manager List', 'slug' => 'faqs_manager_list'],
            ['name' => 'Banners Manager List', 'slug' => 'banners_manager_list'],
            ['name' => 'Settings Manager List', 'slug' => 'settings_manager_list'],
        ];

        collect($permissions)->each(function ($perm) {
            $permission = Permission::updateOrCreate(
                ['slug' => $perm['slug']],
                [
                    'name' => $perm['name'],
                    'status' => 1,
                ]
            );
            $this->command?->info("Permission seeded: {$permission->name} ({$permission->slug})");
        });
    }
}
