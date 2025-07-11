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

        foreach ($targets as $src => $dest) {
            if (is_dir($src)) {
                $files = File::allFiles($src);
                foreach ($files as $file) {
                    $relPath = ltrim(str_replace($src, '', $file->getPathname()), '/\\');
                    $destPath = $dest . '/' . $relPath;
                    File::ensureDirectoryExists(dirname($destPath));
                    $content = File::get($file->getPathname());
                    $content = $this->transformNamespaces($content, $file->getPathname());
                    File::put($destPath, $content);
                    $this->info("Published: $destPath");
                }
            } elseif (File::exists($src)) {
                File::ensureDirectoryExists(dirname($dest));
                $content = File::get($src);
                $content = $this->transformNamespaces($content, $src);
                File::put($dest, $content);
                $this->info("Published: $dest");
            }
        }

        // Publish views (copy directory)
        $this->copyFolder('resources/views', 'resources/views');
    }

    protected function transformNamespaces($content, $sourceFile)
    {
        $namespaceTransforms = [
            // Main namespace transformations
            'namespace admin\\admin_role_permissions\\Controllers;' => 'namespace Modules\\AdminRolePermissions\\app\\Http\\Controllers\\Admin;',
            'namespace admin\\admin_role_permissions\\Models;' => 'namespace Modules\\AdminRolePermissions\\app\\Models;',
            'namespace admin\\admin_role_permissions\\Requests;' => 'namespace Modules\\AdminRolePermissions\\app\\Http\\Requests;',
            'namespace admin\\admin_role_permissions\\Traits;' => 'namespace Modules\\AdminRolePermissions\\app\\Traits;',
            // Use statements transformations
            'use admin\\admin_role_permissions\\Controllers\\' => 'use Modules\\AdminRolePermissions\\app\\Http\\Controllers\\Admin\\',
            'use admin\\admin_role_permissions\\Models\\' => 'use Modules\\AdminRolePermissions\\app\\Models\\',
            'use admin\\admin_role_permissions\\Requests\\' => 'use Modules\\AdminRolePermissions\\app\\Http\\Requests\\',
            'use admin\\admin_role_permissions\\Traits\\' => 'use Modules\\AdminRolePermissions\\app\\Traits\\',
        ];
        foreach ($namespaceTransforms as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }
        return $content;
    }

    protected function copyFolder($src, $dest)
    {
        $sourceBase = base_path('packages/admin/admin_role_permissions');
        $destBase = base_path('Modules/AdminRolePermissions');
        $srcPath = $sourceBase . '/' . $src;
        $destPath = $destBase . '/' . $dest;
        if (File::exists($srcPath)) {
            File::ensureDirectoryExists($destPath);
            File::copyDirectory($srcPath, $destPath);
            $this->info("Published: $srcPath → $destPath");
        }
    }

    protected function copyRootFile($file)
    {
        $sourceBase = base_path('packages/admin/admin_role_permissions');
        $destBase = base_path('Modules/AdminRolePermissions');
        $srcFile = $sourceBase . '/' . $file;
        $destFile = $destBase . '/' . $file;
        if (File::exists($srcFile)) {
            File::copy($srcFile, $destFile);
            $this->info("Published: $srcFile → $destFile");
        }
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
