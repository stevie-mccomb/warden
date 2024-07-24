<?php

namespace Stevie\Warden\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Stevie\Warden\Exceptions\InvalidRoleException;
use Stevie\Warden\Models\Role;

trait HasRoles
{
    /**
     * Return the capabilities that are granted by this authenticatable's active role.
     */
    public function capabilities(): BelongsToMany
    {
        return ($this->role ?? new Role)->capabilities();
    }

    /**
     * Return this authenticatable's first active role.
     */
    public function role(): BelongsToMany
    {
        return $this->roles()->where('is_active', 1)->limit(1);
    }

    /**
     * Return the roles that this authenticatable has been granted.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, config('warden.tables.role_user'))->withPivot('is_active');
    }

    /**
     * Convert this authenticatable's self::roles query result into a single item.
     */
    public function getRoleAttribute(): ?Role
    {
        return $this->roles()->first();
    }

    /**
     * Attempt to make the given role active on this authenticatable.
     * The given role must be one of the user's roles.
     *
     * If the role is already active, the method returns false.
     * If the role is not one of the user's roles, an exception is thrown.
     *
     * @throws InvalidRoleException
     */
    public function setRoleAttribute(Role $role): bool
    {
        $table = config('warden.tables.roles');

        if (!$this->roles()->where("$table.id", $role->id)->exists()) throw new InvalidRoleException;
        if ($this->roles()->where("$table.id", $role->id)->wherePivot('is_active', 1)->exists()) return false;
        $this->roles()->newPivotQuery()->update([ 'is_active' => 0 ]);
        $this->roles()->updateExistingPivot($role->id, [ 'is_active' => 1 ]);
        return true;
    }
}
