<?php

namespace Stevie\Warden\Events;

use Stevie\Warden\Models\Capability;

class CapabilityUpdating extends WardenEvent
{
    /**
     * The capability that is being updated.
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
