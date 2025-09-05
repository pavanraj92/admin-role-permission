<?php

namespace admin\admin_role_permissions;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdminRolePermissionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerViewNamespaces();
        $this->registerMigrations();
        $this->registerConfigs();
        $this->registerPublishables();
        $this->registerAdminRoutes();
    }

    protected function registerViewNamespaces()
    {
        // // Permission views
        // $this->loadViewsFrom([
        //     base_path('Modules/AdminRolePermissions/resources/views'),
        //     resource_path('views/admin/permission'),
        //     __DIR__ . '/../resources/views'
        // ], 'admin_role_permissions');

        $this->loadViewsFrom([
            base_path('Modules/AdminRolePermissions/resources/views'), // Published module views first
            resource_path('views/admin/admin_role_permission'), // Published views second
            __DIR__ . '/../resources/views'      // Package views as fallback
        ], 'admin_role_permissions');
        // // Role views
        // $this->loadViewsFrom([
        //     base_path('Modules/AdminRolePermissions/resources/views'),
        //     resource_path('views/admin/role'),
        //     __DIR__ . '/../resources/views'
        // ], 'admin_role_permissions');

        // // Component views
        // $this->loadViewsFrom([
        //     base_path('Modules/AdminRolePermissions/resources/views'),
        //     resource_path('views/admin/components/global'),
        //     __DIR__ . '/../resources/views'
        // ], 'admin_role_permissions');

        // Extra namespace for explicit usage
        if (is_dir(base_path('Modules/AdminRolePermissions/resources/views'))) {
            $this->loadViewsFrom(base_path('Modules/AdminRolePermissions/resources/views'), 'admin-role-permission-module');
        }
    }

    protected function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $publishedMigrations = base_path('Modules/AdminRolePermissions/database/migrations');
        if (is_dir($publishedMigrations)) {
            $this->loadMigrationsFrom($publishedMigrations);
        }
    }

    protected function registerConfigs()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/admin.php', 'admin.constants');
        // Merge so defaults work without publishing
        $this->mergeConfigFrom(__DIR__ . '/../config/admin.php', 'admin');
        $publishedConfig = base_path('Modules/AdminRolePermissions/config/admin.php');
        if (file_exists($publishedConfig)) {
            $this->mergeConfigFrom($publishedConfig, 'admin.config');
        }



        // Publish to Laravel's config folder
        // $this->publishes([
        //     __DIR__ . '/../config/admin.php' => config_path('admin.php'),
        // ], 'admin_role_permissions-config');
    }

    protected function registerPublishables()
    {
        $this->publishes([
            __DIR__ . '/../database/migrations' => base_path('Modules/AdminRolePermissions/database/migrations'),
            __DIR__ . '/../database/seeders'    => base_path('Modules/AdminRolePermissions/database/seeders'),
            __DIR__ . '/../resources/views'     => base_path('Modules/AdminRolePermissions/resources/views/'),
            __DIR__ . '/../config/'             => base_path('Modules/AdminRolePermissions/config/'),
        ], 'admin_role_permissions');

        // $this->publishWithNamespaceTransformation();
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
            ->prefix("{$slug}/admin")
            ->group(function () {
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

    protected function publishWithNamespaceTransformation()
    {
        $moduleBase = base_path('Modules/AdminRolePermissions');
        $srcBase = __DIR__ . '/../src';

        // Define the files that need namespace transformation
        $filesWithNamespaces = [

            // Controllers
            "$srcBase/Controllers/AdminPermissionController.php"   => "$moduleBase/app/Http/Controllers/Admin/AdminPermissionController.php",
            "$srcBase/Controllers/AdminRoleController.php"         => "$moduleBase/app/Http/Controllers/Admin/AdminRoleController.php",

            // Models
            "$srcBase/Models/Permission.php"            => "$moduleBase/app/Models/Permission.php",
            "$srcBase/Models/Role.php"    => "$moduleBase/app/Models/Role.php",

            // Requests
            "$srcBase/Requests/Permission/StorePermissionRequest.php"  => "$moduleBase/app/Http/Requests/Permission/StorePermissionRequest.php",
            "$srcBase/Requests/Permission/UpdatePermissionRequest.php" => "$moduleBase/app/Http/Requests/Permission/UpdatePermissionRequest.php",
            "$srcBase/Requests/Role/StoreRoleRequest.php"              => "$moduleBase/app/Http/Requests/Role/StoreRoleRequest.php",
            "$srcBase/Requests/Role/UpdateRoleRequest.php"             => "$moduleBase/app/Http/Requests/Role/UpdateRoleRequest.php",


            // Routes
            "$srcBase/routes/web.php" => "$moduleBase/routes/web.php",

            // Traits
            "$srcBase/Traits/HasRoles.php" => "$moduleBase/app/Traits/HasRoles.php",
        ];

        // foreach ($filesWithNamespaces as $from => $to) {
        //     if (File::exists($from)) {
        //         // Ensure the destination directory exists
        //         $destinationDir = dirname($to);
        //         if (!File::isDirectory($destinationDir)) {
        //             File::makeDirectory($destinationDir, 0755, true);
        //         }

        //         // Read the source file
        //         $content = File::get($from);

        //         // Transform namespaces based on file type
        //         if (str_contains($to, '/Controllers/')) {
        //             $content = str_replace('namespace admin\admin_role_permissions\Controllers;', 'namespace Modules\AdminRolePermissions\app\Http\Controllers\Admin;', $content);
        //             $content = str_replace('use admin\admin_role_permissions\Requests\\Permission\\', 'use Modules\AdminRolePermissions\app\Http\Requests\\Permission\\', $content);
        //             $content = str_replace('use admin\admin_role_permissions\Requests\\Role\\', 'use Modules\AdminRolePermissions\app\Http\Requests\\Role\\', $content);
        //             $content = str_replace('use admin\admin_role_permissions\Models\\', 'use Modules\AdminRolePermissions\app\Models\\', $content);
        //         } elseif (str_contains($to, '/Models/')) {
        //             $content = str_replace('namespace admin\admin_role_permissions\Models;', 'namespace Modules\AdminRolePermissions\app\Models;', $content);
        //         } elseif (str_contains($to, '/Requests/')) {
        //             $content = str_replace('namespace admin\admin_role_permissions\Requests\Permission;', 'namespace Modules\AdminRolePermissions\app\Http\Requests\Permission;', $content);
        //             $content = str_replace('namespace admin\admin_role_permissions\Requests\Role;', 'namespace Modules\AdminRolePermissions\app\Http\Requests\Role;', $content);
        //         } elseif (str_contains($to, '/routes/')) {
        //             $content = str_replace('use admin\admin_role_permissions\Controllers\\', 'use Modules\AdminRolePermissions\app\Http\Controllers\Admin\\', $content);
        //         }

        //         // Write the transformed content
        //         File::put($to, $content);
        //     }
        // }

           foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                // Create destination directory if it doesn't exist
                File::ensureDirectoryExists(dirname($destination));

                // Read the source file
                $content = File::get($source);

                // Transform namespaces based on file type
                $content = $this->transformNamespaces($content, $source);

                // Write the transformed content to destination
                File::put($destination, $content);
            }
        }
    }

    protected function transformNamespaces($content, $sourceFile)
    {
        // Define namespace mappings
        $namespaceTransforms = [
            // Main namespace transformations
            'namespace admin\\admin_role_permissions\\Controllers;'    => 'namespace Modules\\AdminRolePermissions\\app\\Http\\Controllers\\Admin;',
            'namespace admin\\admin_role_permissions\\Models;'         => 'namespace Modules\\AdminRolePermissions\\app\\Models;',
            'namespace admin\\admin_role_permissions\\Requests\\Permission;'       => 'namespace Modules\\AdminRolePermissions\\app\\Http\\Requests\\Permission;',
            'namespace admin\\admin_role_permissions\\Requests\\Role;'       => 'namespace Modules\\AdminRolePermissions\\app\\Http\\Requests\\Role;',
            'namespace admin\\admin_role_permissions\\Traits;'       => 'namespace Modules\\AdminRolePermissions\\app\\Http\\Traits;',

            // Use statements transformations
            'use admin\\admin_role_permissions\\Controllers\\'         => 'use Modules\\AdminRolePermissions\\app\\Http\\Controllers\\Admin\\',
            'use admin\\admin_role_permissions\\Models\\'              => 'use Modules\\AdminRolePermissions\\app\\Models\\',
            'use admin\\admin_role_permissions\\Requests\\Permission\\'            => 'use Modules\\AdminRolePermissions\\app\\Http\\Requests\\Permission\\',
            'use admin\\admin_role_permissions\\Requests\\Role\\'            => 'use Modules\\AdminRolePermissions\\app\\Http\\Requests\\Role\\',
            'use admin\\admin_role_permissions\\Traits\\'            => 'use Modules\\AdminRolePermissions\\app\\Http\\Traits\\',

            // Class references in routes
            'admin\\admin_role_permissions\\Controllers\\AdminPermissionController' => 'Modules\\AdminRolePermissions\\app\\Http\\Controllers\\Admin\\AdminPermissionController',
            'admin\\admin_role_permissions\\Controllers\\AdminRoleController' => 'Modules\\AdminRolePermissions\\app\\Http\\Controllers\\Admin\\AdminRoleController',
        ];

        // Apply transformations
        foreach ($namespaceTransforms as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        // Handle specific file types
        if (str_contains($sourceFile, 'Controllers')) {
            $content = $this->transformControllerNamespaces($content);
        } elseif (str_contains($sourceFile, 'Models')) {
            $content = $this->transformModelNamespaces($content);
        } elseif (str_contains($sourceFile, 'Requests')) {
            $content = $this->transformRequestNamespaces($content);
        } elseif (str_contains($sourceFile, 'routes')) {
            $content = $this->transformRouteNamespaces($content);
        }

        return $content;
    }

    protected function transformControllerNamespaces($content)
    {
        // Update use statements for models and requests
        $content = str_replace(
            'use admin\\admin_role_permissions\\Models\\Permission;',
            'use Modules\\AdminRolePermissions\\app\\Models\\Permission;',
            $content
        );
        $content = str_replace(
            'use admin\\admin_role_permissions\\Models\\Role;',
            'use Modules\\AdminRoleRoles\\app\\Models\\Permission;',
            $content
        );

        $content = str_replace(
            'use admin\\admin_role_permissions\\Requests\\Permission\\StorePermissionRequest;',
            'use Modules\\AdminRolePermissions\\app\\Http\\Requests\\Permission\\StorePermissionRequest;',
            $content
        );

        $content = str_replace(
            'use admin\\admin_role_permissions\\Requests\\Permission\\UpdatePermissionRequest;',
            'use Modules\\AdminRolePermissions\\app\\Http\\Requests\\Permission\\UpdatePermissionRequest;',
            $content
        );
        $content = str_replace(
            'use admin\\admin_role_permissions\\Requests\\Role\\StoreRoleRequest;',
            'use Modules\\AdminRolePermissions\\app\\Http\\Requests\\Role\\StoreRoleRequest;',
            $content
        );

        $content = str_replace(
            'use admin\\admin_role_permissions\\Requests\\Role\\UpdateRoleRequest;',
            'use Modules\\AdminRolePermissions\\app\\Http\\Requests\\Role\\UpdateRoleRequest;',
            $content
        );
        $content = str_replace(
            'use admin\\admin_role_permissions\\Traits\\HasRoles;',
            'use Modules\\AdminRolePermissions\\app\\Http\\Traits\\HasRoles;',
            $content
        );

        return $content;
    }

    protected function transformModelNamespaces($content)
    {
        return str_replace(
            'namespace admin\\admin_role_permissions\\Models;',
            'namespace Modules\\AdminRolePermissions\\app\\Models;',
            $content
        );
    }

    protected function transformRequestNamespaces($content)
    {
        // $content = str_replace(
        //     'namespace admin\\admin_role_permissions\\Requests\\Permission;',
        //     'namespace Modules\\AdminRolePermissions\\app\\Http\\Requests\\Permission;',
        //     $content
        // );
        // $content = str_replace(
        //     'namespace admin\\admin_role_permissions\\Requests\\Role;',
        //     'namespace Modules\\AdminRolePermissions\\app\\Http\\Requests\\Role;',
        //     $content
        // );

        return $content;
    }

    protected function transformRouteNamespaces($content)
    {
        // Update controller references in routes
        $content = str_replace(
            'admin\\admin_role_permissions\\Controllers\\AdminPermissionController',
            'Modules\\admin_role_permissions\\app\\Http\\Controllers\\Admin\\AdminPermissionController',
            $content
        );
        $content = str_replace(
            'admin\\admin_role_permissions\\Controllers\\AdminRoleController',
            'Modules\\admin_role_permissions\\app\\Http\\Controllers\\Admin\\AdminRoleController',
            $content
        );

        return $content;
    }
}