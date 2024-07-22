<?php

namespace Stevie\Warden\Events;

use Stevie\Warden\Models\Capability;

class CapabilityRestored extends WardenEvent
{
    /**
     * The capability that has been restored.
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
