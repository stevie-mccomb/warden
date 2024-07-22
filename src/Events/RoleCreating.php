<?php

namespace Stevie\Warden\Events;

use Stevie\Warden\Models\Role;

class RoleCreating extends WardenEvent
{
    /**
     * The role that is being created.
     */
    public Role $role;

    /**
     * Create a new event instance.
     */
    public function __construct(Role $role)
    {
        $this->role = $role;
    }
}
