<?php

namespace admin\admin_role_permissions;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AdminRolePermissionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes, views, migrations from the package
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'admin_role_permissions');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->mergeConfigFrom(__DIR__.'/../config/admin.php', 'admin_role_permissions');
        

        $this->publishes([  
            __DIR__ . '/../config/admin.php' => config_path('admin_role_permissions.php'),
            __DIR__.'/../resources/views' => resource_path('views/admin/role_permissions'),
            __DIR__ . '/../src/Controllers' => app_path('Http/Controllers/Admin/RolePermissions'),
            __DIR__ . '/../src/Models' => app_path('Models/Admin/RolePermissions'),
            __DIR__ . '/routes/web.php' => base_path('routes/admin/role_permissions.php'),
        ], 'admin_role_permissions');

        $this->registerAdminRoutes();

    }

    protected function registerAdminRoutes()
    {
        if (!Schema::hasTable('admins')) {
            return; // Avoid errors before migration
        }

        $admin = DB::table('admins')
            ->orderBy('created_at', 'asc')
            ->first();
            
        $slug = $admin->website_slug ?? 'admin';

        Route::middleware('web')
            ->prefix("{$slug}/admin") // dynamic prefix
            ->group(function () {
                $this->loadRoutesFrom(__DIR__.'/routes/web.php');
            });
    }

    public function register()
    {
        // You can bind classes or configs here
    }
}
