<?php

namespace admin\admin_role_permissions\Traits;

trait CommandHelpers
{
    /**
     * Validate a name (non-empty, alphanumeric, dashes/underscores allowed).
     */
    public function isValidName(string $name): bool
    {
        return (bool) preg_match('/^[a-zA-Z0-9_-]+$/', $name);
    }

    /**
     * Check if a model exists by name.
     */
    public function modelExists($modelClass, string $name): bool
    {
        return $modelClass::where('name', $name)->exists();
    }

    /**
     * Create a model by name.
     */
    public function createModel($modelClass, string $name)
    {
        return $modelClass::create(['name' => $name]);
    }
} 