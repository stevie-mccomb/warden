<?php

namespace Stevie\Warden\Events;

use Stevie\Warden\Models\Capability;

class CapabilityRetrieved extends WardenEvent
{
    /**
     * The capability that was retrieved.
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
