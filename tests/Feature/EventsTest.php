<?php

use Illuminate\Support\Facades\Event;
use Stevie\Warden\Events\CapabilityCreated;
use Stevie\Warden\Events\CapabilityCreating;
use Stevie\Warden\Events\CapabilityDeleted;
use Stevie\Warden\Events\CapabilityDeleting;
use Stevie\Warden\Events\CapabilityPurged;
use Stevie\Warden\Events\CapabilityPurging;
use Stevie\Warden\Events\CapabilityReplicating;
use Stevie\Warden\Events\CapabilityRestored;
use Stevie\Warden\Events\CapabilityRestoring;
use Stevie\Warden\Events\CapabilityRetrieved;
use Stevie\Warden\Events\CapabilitySaved;
use Stevie\Warden\Events\CapabilitySaving;
use Stevie\Warden\Events\CapabilityTrashed;
use Stevie\Warden\Events\CapabilityUpdated;
use Stevie\Warden\Events\CapabilityUpdating;
use Stevie\Warden\Events\RoleCreated;
use Stevie\Warden\Events\RoleCreating;
use Stevie\Warden\Events\RoleDeleted;
use Stevie\Warden\Events\RoleDeleting;
use Stevie\Warden\Events\RolePurged;
use Stevie\Warden\Events\RolePurging;
use Stevie\Warden\Events\RoleReplicating;
use Stevie\Warden\Events\RoleRestored;
use Stevie\Warden\Events\RoleRestoring;
use Stevie\Warden\Events\RoleRetrieved;
use Stevie\Warden\Events\RoleSaved;
use Stevie\Warden\Events\RoleSaving;
use Stevie\Warden\Events\RoleTrashed;
use Stevie\Warden\Events\RoleUpdated;
use Stevie\Warden\Events\RoleUpdating;
use Stevie\Warden\Models\Capability;
use Stevie\Warden\Models\Role;

test('events', function () {
    class DummyListener {
        public static $eventsHeard = [];
        public function handle(object $event): void {
            self::$eventsHeard[] = $event::class;
            expect($event->broadcastOn())->toBe(config('warden.broadcasting.channels'));
        }
    }

    $events = [
        /**
         * Capabilities
         */
        CapabilityCreated::class,
        CapabilityCreating::class,
        CapabilityDeleted::class,
        CapabilityDeleting::class,
        CapabilityPurged::class,
        CapabilityPurging::class,
        CapabilityReplicating::class,
        CapabilityRestored::class,
        CapabilityRestoring::class,
        CapabilityRetrieved::class,
        CapabilitySaved::class,
        CapabilitySaving::class,
        CapabilityTrashed::class,
        CapabilityUpdated::class,
        CapabilityUpdating::class,

        /**
         * Roles
         */
        RoleCreated::class,
        RoleCreating::class,
        RoleDeleted::class,
        RoleDeleting::class,
        RolePurged::class,
        RolePurging::class,
        RoleReplicating::class,
        RoleRestored::class,
        RoleRestoring::class,
        RoleRetrieved::class,
        RoleSaved::class,
        RoleSaving::class,
        RoleTrashed::class,
        RoleUpdated::class,
        RoleUpdating::class,
    ];

    foreach ($events as $event) {
        Event::listen($event, DummyListener::class);
    }

    Capability::create([ 'name' => 'Example', 'slug' => 'example' ]);
    $capability = Capability::where('slug', 'example')->first();
    $capability->replicate();
    $capability->delete();
    $capability->restore();
    $capability->forceDelete();

    Role::create([ 'name' => 'Example', 'slug' => 'example' ]);
    $role = Role::where('slug', 'example')->first();
    $role->replicate();
    $role->delete();
    $role->restore();
    $role->forceDelete();
});
