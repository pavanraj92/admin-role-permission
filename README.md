# Admin Role Permission Manager

This package provides comprehensive role and permission management for admin users in Laravel applications. During installation, it automatically installs the Admin Manager package.
---

## Features

- Role Management: Create, edit, and delete roles
- Permission Management: Create, edit, and delete permissions
- Assign permissions to roles
- Assign roles to admin users
- Role-based access control for admin users
- Enable/disable permissions
- Role-based access control
- AJAX-based assignment with Select2
- Search and filter support

---

## Requirements

- PHP >=8.2
- Laravel Framework >= 12.x

---

## Installation

### 1. Add Git Repository to `composer.json`

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/pavanraj92/admin-role-permission.git"
    }
]
```

### 2. Require the package via Composer
    ```bash
    composer require admin/admin_role_permissions:@dev
    ```

### 3. Publish assets
    ```bash
    php artisan admin_role_permissions:publish --force
    ```
---

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

---

## Protecting Admin Routes

Protect your routes using the provided middleware:

```php
Route::middleware(['web','admin.auth'])->group(function () {
    // Admin role permissions routes here
});
```
---

## Database Tables

- `roles` - Stores role information
- `permissions` - Stores permission information  
- `role_admin` - Pivot table linking roles to admins
- `permission_role` - Pivot table linking permissions to roles

---

## License

This package is open-sourced software licensed under the MIT license.
