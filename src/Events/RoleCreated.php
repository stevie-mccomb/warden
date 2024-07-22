<?php

namespace Stevie\Warden\Events;

use Stevie\Warden\Models\Role;

class RoleCreated extends WardenEvent
{
    /**
     * The role that was created.
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
