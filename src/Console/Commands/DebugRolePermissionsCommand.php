<?php

namespace admin\admin_role_permissions\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class DebugRolePermissionsCommand extends Command
{
    protected $signature = 'admin_role_permissions:debug';
    protected $description = 'Debug AdminRolePermissions module loading';

    public function handle()
    {
        $this->info('🔍 Debugging AdminRolePermissions Module...');
        
        // Check which route file is being loaded
        $this->info("\n📍 Route Loading Priority:");
        $moduleRoutes = base_path('Modules/AdminRolePermissions/routes/web.php');
        $packageRoutes = base_path('packages/admin/admin_role_permissions/src/routes/web.php');
        
        if (File::exists($moduleRoutes)) {
            $this->info("✅ Module routes found: {$moduleRoutes}");
            $this->info("   Last modified: " . date('Y-m-d H:i:s', filemtime($moduleRoutes)));
        } else {
            $this->error("❌ Module routes not found");
        }
        
        if (File::exists($packageRoutes)) {
            $this->info("✅ Package routes found: {$packageRoutes}");
            $this->info("   Last modified: " . date('Y-m-d H:i:s', filemtime($packageRoutes)));
        } else {
            $this->error("❌ Package routes not found");
        }
        
        // Check view loading priority
        $this->info("\n👀 View Loading Priority:");
        $viewPaths = [
            'Module views' => base_path('Modules/AdminRolePermissions/resources/views'),
            'Published views' => resource_path('views/admin/role_permissions'),
            'Package views' => base_path('packages/admin/admin_role_permissions/resources/views'),
        ];
        
        foreach ($viewPaths as $name => $path) {
            if (File::exists($path)) {
                $this->info("✅ {$name}: {$path}");
            } else {
                $this->warn("⚠️  {$name}: NOT FOUND - {$path}");
            }
        }
        
        // Check controller resolution
        $this->info("\n🎯 Controller Resolution:");
        $controllerClasses = [
            'Role' => 'Modules\\AdminRolePermissions\\app\\Http\\Controllers\\Admin\\AdminRoleController',
            'Permission' => 'Modules\\AdminRolePermissions\\app\\Http\\Controllers\\Admin\\AdminPermissionController',
        ];
        foreach ($controllerClasses as $type => $controllerClass) {
            if (class_exists($controllerClass)) {
                $this->info("✅ {$type} Controller class found: {$controllerClass}");
                $reflection = new \ReflectionClass($controllerClass);
                $this->info("   File: " . $reflection->getFileName());
                $this->info("   Last modified: " . date('Y-m-d H:i:s', filemtime($reflection->getFileName())));
            } else {
                $this->error("❌ {$type} Controller class not found: {$controllerClass}");
            }
        }
        
        // Show current routes
        $this->info("\n🛣️  Current Routes:");
        $routes = Route::getRoutes();
        $roleRoutes = [];
        foreach ($routes as $route) {
            $action = $route->getAction();
            if (isset($action['controller']) && (
                str_contains($action['controller'], 'AdminRoleController') ||
                str_contains($action['controller'], 'AdminPermissionController')
            )) {
                $roleRoutes[] = [
                    'uri' => $route->uri(),
                    'methods' => implode('|', $route->methods()),
                    'controller' => $action['controller'],
                    'name' => $route->getName(),
                ];
            }
        }
        if (!empty($roleRoutes)) {
            $this->table(['URI', 'Methods', 'Controller', 'Name'], $roleRoutes);
        } else {
            $this->warn("No role/permission routes found.");
        }
    }
}
