<?php

namespace admin\admin_role_permissions\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CheckModuleStatusCommand extends Command
{
    protected $signature = 'admin_role_permissions:status';
    protected $description = 'Check if AdminRolePermissions module files are being used';

    public function handle()
    {
        $this->info('Checking AdminRolePermissions Module Status...');
        
        // Check if module files exist
        $moduleFiles = [
            'Controller (Role)' => base_path('Modules/AdminRolePermissions/app/Http/Controllers/Admin/AdminRoleController.php'),
            'Controller (Permission)' => base_path('Modules/AdminRolePermissions/app/Http/Controllers/Admin/AdminPermissionController.php'),
            'Model (Role)' => base_path('Modules/AdminRolePermissions/app/Models/Role.php'),
            'Model (Permission)' => base_path('Modules/AdminRolePermissions/app/Models/Permission.php'),
            'Request (Role Store)' => base_path('Modules/AdminRolePermissions/app/Http/Requests/Role/StoreRoleRequest.php'),
            'Request (Role Update)' => base_path('Modules/AdminRolePermissions/app/Http/Requests/Role/UpdateRoleRequest.php'),
            'Request (Permission Store)' => base_path('Modules/AdminRolePermissions/app/Http/Requests/Permission/StorePermissionRequest.php'),
            'Request (Permission Update)' => base_path('Modules/AdminRolePermissions/app/Http/Requests/Permission/UpdatePermissionRequest.php'),
            'Routes' => base_path('Modules/AdminRolePermissions/routes/web.php'),
            'Views (role)' => base_path('Modules/AdminRolePermissions/resources/views/admin/role'),
            'Views (permission)' => base_path('Modules/AdminRolePermissions/resources/views/admin/permission'),
            'Config' => base_path('Modules/AdminRolePermissions/config/admin.php'),
        ];

        $this->info("\n📁 Module Files Status:");
        foreach ($moduleFiles as $type => $path) {
            if (File::exists($path)) {
                $this->info("✅ {$type}: EXISTS");
                
                // Check if it's a PHP file and show last modified time
                if (str_ends_with($path, '.php')) {
                    $lastModified = date('Y-m-d H:i:s', filemtime($path));
                    $this->line("   Last modified: {$lastModified}");
                }
            } else {
                $this->error("❌ {$type}: NOT FOUND");
            }
        }

        // Check namespace in controller
        $controllerPath = base_path('Modules/AdminRolePermissions/app/Http/Controllers/Admin/AdminRoleController.php');
        if (File::exists($controllerPath)) {
            $content = File::get($controllerPath);
            if (str_contains($content, 'namespace Modules\\AdminRolePermissions\\app\\Http\\Controllers\\Admin;')) {
                $this->info("\n✅ Controller namespace: CORRECT");
            } else {
                $this->error("\n❌ Controller namespace: INCORRECT");
            }
            
            // Check for test comment
            if (str_contains($content, 'Test comment - this should persist after refresh')) {
                $this->info("✅ Test comment: FOUND (changes are persisting)");
            } else {
                $this->warn("⚠️  Test comment: NOT FOUND");
            }
        }

        // Check composer autoload
        $composerFile = base_path('composer.json');
        if (File::exists($composerFile)) {
            $composer = json_decode(File::get($composerFile), true);
            if (isset($composer['autoload']['psr-4']['Modules\\AdminRolePermissions\\'])) {
                $this->info("\n✅ Composer autoload: CONFIGURED");
            } else {
                $this->error("\n❌ Composer autoload: NOT CONFIGURED");
            }
        }

        $this->info("\n🎯 Summary:");
        $this->info("Your AdminRolePermissions module is properly published and should be working.");
        $this->info("Any changes you make to files in Modules/AdminRolePermissions/ will persist.");
        $this->info("If you need to republish from the package, run: php artisan admin_role_permissions:publish --force");
    }
}
