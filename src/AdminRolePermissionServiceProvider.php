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
        // Load views from published module, then fallback to package
        $this->loadViewsFrom([
            base_path('Modules/AdminRolePermissions/resources/views'),
            resource_path('views/admin/role_permissions'),
            __DIR__ . '/../resources/views'
        ], 'admin_role_permissions');

        // Also register module views with a specific namespace for explicit usage
        if (is_dir(base_path('Modules/AdminRolePermissions/resources/views'))) {
            $this->loadViewsFrom(base_path('Modules/AdminRolePermissions/resources/views'), 'admin-role-permissions-module');
        }

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        if (is_dir(base_path('Modules/AdminRolePermissions/database/migrations'))) {
            $this->loadMigrationsFrom(base_path('Modules/AdminRolePermissions/database/migrations'));
        }

        $this->mergeConfigFrom(__DIR__ . '/../config/admin.php', 'admin_role_permissions');
        if (file_exists(base_path('Modules/AdminRolePermissions/config/admin.php'))) {
            $this->mergeConfigFrom(base_path('Modules/AdminRolePermissions/config/admin.php'), 'admin_role_permissions');
        }

        // Standard publishing for non-PHP files
        $this->publishes([
            __DIR__ . '/../config/' => base_path('Modules/AdminRolePermissions/config/'),
            __DIR__ . '/../database/migrations' => base_path('Modules/AdminRolePermissions/database/migrations'),
            __DIR__ . '/../resources/views' => base_path('Modules/AdminRolePermissions/resources/views/'),
        ], 'admin_role_permissions');

        $this->registerAdminRoutes();
    }

    protected function registerAdminRoutes()
    {
        if (!Schema::hasTable('admins')) {
            return; // Avoid errors before migration
        }

        $slug = DB::table('admins')->latest()->value('website_slug') ?? 'admin';

        Route::middleware('web')
            ->prefix("{$slug}/admin")
            ->group(function () {
                // Load routes from published module first, then fallback to package
                if (file_exists(base_path('Modules/AdminRolePermissions/routes/web.php'))) {
                    $this->loadRoutesFrom(base_path('Modules/AdminRolePermissions/routes/web.php'));
                } else {
                    $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
                }
            });
    }

    public function register()
    {
        // Register the publish command
        if ($this->app->runningInConsole()) {
            $this->commands([
                \admin\admin_role_permissions\Console\Commands\PublishAdminRolePermissionsModuleCommand::class,
                \admin\admin_role_permissions\Console\Commands\CheckModuleStatusCommand::class,
                \admin\admin_role_permissions\Console\Commands\DebugRolePermissionsCommand::class,
                \admin\admin_role_permissions\Console\Commands\TestRolePermissionViewResolutionCommand::class,
            ]);
        }
    }
}
