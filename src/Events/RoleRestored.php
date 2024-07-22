<?php

namespace Stevie\Warden\Events;

use Stevie\Warden\Models\Role;

class RoleRestored extends WardenEvent
{
    /**
     * The role that was restored.
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
