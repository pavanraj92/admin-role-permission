<?php

namespace admin\admin_role_permissions\Traits;

use admin\admin_role_permissions\Models\Role;

trait HasRoles
{
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_admin');
    }

    public function permissions()
    {
        return $this->roles->flatMap(function ($role) {
            return $role->permissions()->where('permissions.status', 1)->get();
        })->unique('id');
    }

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }
        return $this->roles->contains('id', $role->id);
    }

    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            return $this->permissions()->contains('slug', $permission);
        }
        return $this->permissions()->contains('id', $permission->id);
    }

    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        }
        $this->roles()->syncWithoutDetaching($role);
    }

    public function removeRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }
        if ($role) {
            $this->roles()->detach($role);
        }
    }
}
