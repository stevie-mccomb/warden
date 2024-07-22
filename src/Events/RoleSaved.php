<?php

namespace Stevie\Warden\Events;

use Stevie\Warden\Models\Role;

class RoleSaved extends WardenEvent
{
    /**
     * The role that was saved.
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
