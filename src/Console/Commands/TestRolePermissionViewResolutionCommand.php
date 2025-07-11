<?php

namespace admin\admin_role_permissions\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\View;

class TestRolePermissionViewResolutionCommand extends Command
{
    protected $signature = 'admin_role_permissions:test-views';
    protected $description = 'Test view resolution for AdminRolePermissions module';

    public function handle()
    {
        $this->info('🔍 Testing View Resolution for AdminRolePermissions Module...');
        
        // Test views to check
        $testViews = [
            'admin.role.index',
            'admin.role.createOrEdit',
            'admin.role.show',
            'admin.role.assign-permissions',
            'admin.role.assign-admins',
            'admin.permission.index',
            'admin.permission.createOrEdit',
            'admin.permission.show',
        ];
        
        foreach ($testViews as $viewName) {
            $this->info("\n📄 Testing view: {$viewName}");
            
            // Test different namespaces
            $namespaces = [
                'admin_role_permissions-module::' . $viewName => 'Module View',
                'admin_role_permissions::' . $viewName => 'Package View',
            ];
            
            foreach ($namespaces as $fullPath => $description) {
                try {
                    if (View::exists($fullPath)) {
                        $this->info("  ✅ {$description}: EXISTS - {$fullPath}");
                        
                        // Get the actual file path
                        try {
                            $finder = app('view')->getFinder();
                            $path = $finder->find($fullPath);
                            $this->line("     File: {$path}");
                            $this->line("     Modified: " . date('Y-m-d H:i:s', filemtime($path)));
                        } catch (\Exception $e) {
                            $this->line("     Path resolution failed: {$e->getMessage()}");
                        }
                    } else {
                        $this->warn("  ❌ {$description}: NOT FOUND - {$fullPath}");
                    }
                } catch (\Exception $e) {
                    $this->error("  ❌ {$description}: ERROR - {$e->getMessage()}");
                }
            }
        }
        
        // Test the dynamic resolution method
        $this->info("\n🎯 Testing Dynamic View Resolution:");
        $controllerClass = '\\Modules\\AdminRolePermissions\\app\\Http\\Controllers\\Admin\\RolePermissionManagerController';
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            foreach ($testViews as $viewName) {
                try {
                    $reflection = new \ReflectionClass($controller);
                    $method = $reflection->getMethod('getViewPath');
                    $method->setAccessible(true);
                    
                    $resolvedPath = $method->invoke($controller, $viewName);
                    $this->info("  📍 {$viewName} → {$resolvedPath}");
                    
                    if (View::exists($resolvedPath)) {
                        $this->info("    ✅ Resolved view exists");
                    } else {
                        $this->error("    ❌ Resolved view does not exist");
                    }
                } catch (\Exception $e) {
                    $this->error("  ❌ Error testing {$viewName}: {$e->getMessage()}");
                }
            }
        } else {
            $this->error("  ❌ Controller class not found: {$controllerClass}");
        }
        
        $this->info("\n📋 View Loading Order:");
        $this->info("1. admin_role_permissions-module:: (Module views - highest priority)");
        $this->info("2. admin_role_permissions:: (Package views - fallback)");
        
        $this->info("\n💡 Tips:");
        $this->info("- Edit views in Modules/AdminRolePermissions/resources/views/ to use module views");
        $this->info("- Module views will automatically take precedence over package views");
        $this->info("- If module view doesn't exist, it will fallback to package view");
    }
}
