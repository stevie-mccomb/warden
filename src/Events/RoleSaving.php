<?php

namespace Stevie\Warden\Events;

use Stevie\Warden\Models\Role;

class RoleSaving extends WardenEvent
{
    /**
     * The role that is being saved.
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
