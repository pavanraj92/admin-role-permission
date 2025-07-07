# Admin Role Permission Manager

This package provides comprehensive role and permission management for admin users in Laravel applications.

## Features

- Role Management: Create, edit, and delete roles
- Permission Management: Create, edit, and delete permissions
- Assign permissions to roles
- Assign roles to admin users
- Role-based access control for admin users
- Enable/disable permissions
- Search and filter roles and permissions

## Need to update `composer.json` file

Add the following to your `composer.json` to use the package from a local path:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/pavanraj92/admin-role-permission.git"
    }
]
```

## Installation

1. **Require the package via Composer:**
    ```bash
    composer require admin/admin_role_permissions:@dev
    ```

2. **Publish assets:**
    ```bash
    php artisan vendor:publish --tag=admin_role_permissions
    ```

3. **Run migrations:**
    ```bash
    php artisan migrate
    ```

4. **Seed initial data:**
    ```bash
    php artisan db:seed --class=admin\\admin_role_permissions\\database\\seeders\\AdminRolePermissionDatabaseSeeder
    ```

2. Access the Admin Role Permission manager from your admin dashboard.

## Usage

### Basic Role and Permission Operations

```php
// Create a role
$role = Role::create(['name' => 'Editor', 'status' => 1]);

// Create a permission
$permission = Permission::create(['name' => 'Edit Posts', 'status' => 1]);

// Assign permission to role
$role->permissions()->attach($permission->id);

// Assign role to admin
$admin->assignRole($role);

// Check if admin has role
if ($admin->hasRole('Editor')) {
    // Admin has Editor role
}

// Check if admin has permission
if ($admin->hasPermission('edit_posts')) {
    // Admin has edit_posts permission
}
```

### Admin Panel Routes

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET    | `/admin/roles` | List all roles |
| GET    | `/admin/roles/create` | Create role form |
| POST   | `/admin/roles` | Store new role |
| GET    | `/admin/roles/{id}` | Show role details |
| GET    | `/admin/roles/{id}/edit` | Edit role form |
| PUT    | `/admin/roles/{id}` | Update role |
| DELETE | `/admin/roles/{id}` | Delete role |
| GET    | `/admin/roles/{id}/assign-permissions` | Assign permissions to role |
| POST   | `/admin/roles/{id}/assign-permissions` | Update role permissions |
| GET    | `/admin/roles/{id}/assign-admins` | Assign admins to role |
| POST   | `/admin/roles/{id}/assign-admins` | Update role admins |
| GET    | `/admin/permissions` | List all permissions |
| GET    | `/admin/permissions/create` | Create permission form |
| POST   | `/admin/permissions` | Store new permission |
| GET    | `/admin/permissions/{id}` | Show permission details |
| GET    | `/admin/permissions/{id}/edit` | Edit permission form |
| PUT    | `/admin/permissions/{id}` | Update permission |
| DELETE | `/admin/permissions/{id}` | Delete permission |
| POST   | `/admin/updateStatus` | Update permission status |

## Database Structure

- `roles` - Stores role information
- `permissions` - Stores permission information  
- `role_admin` - Pivot table linking roles to admins
- `permission_role` - Pivot table linking permissions to roles

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).write code in the readme.md file regarding to the admin/role-permission manager
