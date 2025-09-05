<?php

namespace admin\admin_role_permissions\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishAdminRolePermissionsModuleCommand extends Command
{
    protected $signature = 'admin_role_permissions:publish {--force : Force overwrite existing files}';
    protected $description = 'Publish admin_role_permissions module files with proper namespace transformation';

    public function handle()
    {
        $this->info('Publishing AdminRolePermissions module files...');

        $moduleDir = base_path('Modules/AdminRolePermissions');
        if (!File::exists($moduleDir)) {
            File::makeDirectory($moduleDir, 0755, true);
        }

        $this->publishWithNamespaceTransformation();

        // Publish other files
        $this->call('vendor:publish', [
            '--tag' => 'admin_role_permissions',
            '--force' => $this->option('force')
        ]);

        // Update composer autoload
        $this->updateComposerAutoload();

        $this->info('AdminRolePermissions module published successfully!');
        $this->info('Please run: composer dump-autoload');
    }

    protected function publishWithNamespaceTransformation()
    {
        $basePath = dirname(dirname(__DIR__));
        $targets = [
            // Controllers
            $basePath . '/Controllers/AdminRoleController.php' => base_path('Modules/AdminRolePermissions/app/Http/Controllers/Admin/AdminRoleController.php'),
            $basePath . '/Controllers/AdminPermissionController.php' => base_path('Modules/AdminRolePermissions/app/Http/Controllers/Admin/AdminPermissionController.php'),

            // Models
            $basePath . '/Models/Role.php' => base_path('Modules/AdminRolePermissions/app/Models/Role.php'),
            $basePath . '/Models/Permission.php' => base_path('Modules/AdminRolePermissions/app/Models/Permission.php'),

            $basePath . '/Traits/HasRoles.php' => base_path('Modules/AdminRolePermissions/app/Traits/HasRoles.php'),

            // Requests - Role
            $basePath . '/Requests/Role/StoreRoleRequest.php' => base_path('Modules/AdminRolePermissions/app/Http/Requests/Role/StoreRoleRequest.php'),
            $basePath . '/Requests/Role/UpdateRoleRequest.php' => base_path('Modules/AdminRolePermissions/app/Http/Requests/Role/UpdateRoleRequest.php'),
            // Requests - Permission
            $basePath . '/Requests/Permission/StorePermissionRequest.php' => base_path('Modules/AdminRolePermissions/app/Http/Requests/Permission/StorePermissionRequest.php'),
            $basePath . '/Requests/Permission/UpdatePermissionRequest.php' => base_path('Modules/AdminRolePermissions/app/Http/Requests/Permission/UpdatePermissionRequest.php'),

            // Routes
            $basePath . '/routes/web.php' => base_path('Modules/AdminRolePermissions/routes/web.php'),

            // Config
            dirname($basePath) . '/config/admin.php' => base_path('Modules/AdminRolePermissions/config/admin.php'),
        ];
        
        
        foreach ($targets as $source => $destination) {
            if (File::exists($source)) {
                File::ensureDirectoryExists(dirname($destination));
                
                $content = File::get($source);
                $content = $this->transformNamespaces($content, $source);
                
                File::put($destination, $content);
                $this->info("Published: " . basename($destination));
            } else {
                $this->warn("Source file not found: " . $source);
            }
        }

    }

    protected function transformNamespaces($content, $sourceFile)
    {
        $namespaceTransforms = [
            // Main namespace transformations
            'namespace admin\\admin_role_permissions\\Controllers;' => 'namespace Modules\\AdminRolePermissions\\app\\Http\\Controllers\\Admin;',
            'namespace admin\\admin_role_permissions\\Models;' => 'namespace Modules\\AdminRolePermissions\\app\\Models;',
            'namespace admin\\admin_role_permissions\\Requests\\Permission;' => 'namespace Modules\\AdminRolePermissions\\app\\Http\\Requests\\Permission;',
            'namespace admin\\admin_role_permissions\\Requests\\Role;' => 'namespace Modules\\AdminRolePermissions\\app\\Http\\Requests\\Role;',
            'namespace admin\\admin_role_permissions\\Traits;' => 'namespace Modules\\AdminRolePermissions\\app\\Traits;',
            // Use statements transformations
            'use admin\\admin_role_permissions\\Controllers\\' => 'use Modules\\AdminRolePermissions\\app\\Http\\Controllers\\Admin\\',
            'use admin\\admin_role_permissions\\Models\\' => 'use Modules\\AdminRolePermissions\\app\\Models\\',
            'use admin\\admin_role_permissions\\Requests\\Permission\\' => 'use Modules\\AdminRolePermissions\\app\\Http\\Requests\\Permission\\',
            'use admin\\admin_role_permissions\\Requests\\Role\\' => 'use Modules\\AdminRolePermissions\\app\\Http\\Requests\\Role\\',
            'use admin\\admin_role_permissions\\Traits\\' => 'use Modules\\AdminRolePermissions\\app\\Traits\\',

            // Class references in routes
            'admin\\admin_role_permissions\\Controllers\\AdminPermissionController' => 'Modules\\AdminRolePermissions\\app\\Http\\Controllers\\Admin\\AdminPermissionController',
            'admin\\admin_role_permissions\\Controllers\\AdminRoleController' => 'Modules\\AdminRolePermissions\\app\\Http\\Controllers\\Admin\\AdminRoleController',
        ];
        foreach ($namespaceTransforms as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }
        // Handle specific file types
        if (str_contains($sourceFile, 'Controllers')) {
            $content = str_replace(
                'use admin\\admin_role_permissions\\Models\\Product;',
                'use Modules\\AdminRolePermissions\\app\\Models\\Product;',
                $content
            );
            $content = str_replace(
                'use admin\\admin_role_permissions\\Models\\Order;',
                'use Modules\\AdminRolePermissions\\app\\Models\\Order;',
                $content
            );

            $content = str_replace(
                'use admin\\admin_role_permissions\\Traits\\HasRoles;',
                'use Modules\\AdminRolePermissions\\app\\Traits\\HasRoles;',
                $content
            );
           
            $content = str_replace('use admin\\admin_role_permissions\\Requests\\Permission\\StorePermissionRequest;', 'use Modules\\AdminRolePermissions\\app\\Http\\Requests\\Permission\\StorePermissionRequest;', $content);
            $content = str_replace('use admin\\admin_role_permissions\\Requests\\Permission\\UpdatePermissionRequest;', 'use Modules\\AdminRolePermissions\\app\\Http\\Requests\\Permission\\UpdatePermissionRequest;', $content);
            $content = str_replace('use admin\\admin_role_permissions\\Requests\\Role\\StoreRoleRequest;', 'use Modules\\AdminRolePermissions\\app\\Http\\Requests\\Role\\StoreRoleRequest;', $content);
            $content = str_replace('use admin\\admin_role_permissions\\Requests\\Role\\UpdateRoleRequest;', 'use Modules\\AdminRolePermissions\\app\\Http\\Requests\\Role\\UpdateRoleRequest;', $content);
        }
        return $content;
    }

    protected function updateComposerAutoload()
    {
        $composerFile = base_path('composer.json');
        $composer = json_decode(File::get($composerFile), true);
        if (!isset($composer['autoload']['psr-4']['Modules\\AdminRolePermissions\\'])) {
            $composer['autoload']['psr-4']['Modules\\AdminRolePermissions\\'] = 'Modules/AdminRolePermissions/app/';
            File::put($composerFile, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->info('Updated composer.json autoload');
        }
    }
}
