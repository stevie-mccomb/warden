<?php

namespace Stevie\Warden\Events;

use Stevie\Warden\Models\Role;

class RolePurging extends WardenEvent
{
    /**
     * The role that is being purged.
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
