<?php

namespace Stevie\Warden\Events;

use Stevie\Warden\Models\Capability;

class CapabilitySaving extends WardenEvent
{
    /**
     * The capability that is being saved.
     */
    public Capability $capability;

    /**
     * Create a new event instance.
     */
    public function __construct(Capability $capability)
    {
        $this->capability = $capability;
    }
}
