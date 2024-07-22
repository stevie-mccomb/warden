<?php

namespace Stevie\Warden\Events;

use Stevie\Warden\Models\Role;

class RoleReplicating extends WardenEvent
{
    /**
     * The role that is being replicated.
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
