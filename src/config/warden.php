<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Capabilities
    |--------------------------------------------------------------------------
    |
    | This array tracks the capabilities that you would like to store in your
    | Warden authorization system. The format of a capability is:
    |
    | [ {id}, {name}, {slug} ]
    |
    | Be sure not to re-use an existing ID for a new capability, or you may
    | encounter security issues. When a capability has been used before and is
    | removed from the system, it's best to leave a comment in its place to
    | note the ID that should not be used again in the future.
    |
    | @example // 7 intentionally left blank
    |
    */

    'capabilities' => [
        /**
         * User Management
         */
        [ 1, 'View Users', 'view-users' ],
        [ 2, 'Create Users', 'create-users' ],
        [ 3, 'Update Users', 'update-users' ],
        [ 4, 'Delete Users', 'delete-users' ],
        [ 5, 'Restore Users', 'restore-users' ],
        [ 6, 'Purge Users', 'purge-users' ],

        // etc...
    ],

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    |
    | This array tracks the roles that your users may have. The capabilities
    | for these roles are defined below in the "Capability Role Map".
    |
    */

    'roles' => [
        [ 1, 'Administrator', 'admin' ],

        // etc...
    ],

    /*
    |--------------------------------------------------------------------------
    | Capability Role Map
    |--------------------------------------------------------------------------
    |
    | This array tracks the capabilities that are granted by your roles.
    |
    | The key is the slug of the role, and the value is an array of capability
    | slugs that should be granted to that role.
    |
    | @example 'admin' => [ 'view-users', 'create-users', 'update-users', 'delete-users' ],
    |
    | If a role is assigned the "*" wildcard, they will be granted all capabilities.
    |
    | @example 'admin' => '*',
    |
    */

    'capability_role_map' => [
        'admin' => [
            'view-users',
            'create-users',
            'update-users',
            'delete-users',
            'restore-users',
            'purge-users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Capability Dependency Map
    |--------------------------------------------------------------------------
    |
    | This array tracks the relationship between capabilities. Keys are slugs
    | of dependents, and values are arrays of slugs of dependencies.
    |
    | @example 'update-users' => [ 'view-users', 'create-users' ]
    |
    | In the example above, in order to have the 'update-users' capability,
    | one must also have the 'view-users' and 'create-users' capabilities.
    |
    */

    'capability_dependency_map' => [
        /**
         * User Management
         */
        'create-users' => [ 'view-users' ],
        'update-users' => [ 'view-users', 'create-users', 'update-users' ],
        'delete-users' => [ 'view-users', 'create-users', 'update-users' ],
        'restore-users' => [ 'view-users', 'create-users', 'update-users', 'delete-users' ],
        'purge-users' => [ 'view-users', 'create-users', 'update-users', 'restore-users', 'delete-users' ],

        // etc.
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Tables
    |--------------------------------------------------------------------------
    |
    | Here you can change the default database table names. Change the values,
    | not the keys. The keys are what Warden uses to identify which migration
    | is responsible for that table.
    |
    | Note that if you have already run your migrations, you will need to
    | roll back before changing these, and then roll forward after.
    | Alternatively, you can publish new migrations and phase out your old
    | tables after deployment to production.
    |
    */

    'tables' => [
        'capabilities' => 'capabilities',
        'roles' => 'roles',
        'capability_capability' => 'capability_capability',
        'capability_role' => 'capability_role',
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Map
    |--------------------------------------------------------------------------
    |
    | Don't like the way Warden handles something? No worries, just replace
    | Warden's base class here with one of your own classes.
    |
    | You can even extend Warden's base class and change part of its
    | implementation rather than replacing the full functionality, if desired.
    |
    */

    'class_map' => [
        /**
         * Database Seeders
         */
        'seeders' => [
            'capabilities' => \Stevie\Warden\Database\Seeders\CapabilitiesTableSeeder::class,
            'roles' => \Stevie\Warden\Database\Seeders\RolesTableSeeder::class,
            'capability_capability' => \Stevie\Warden\Database\Seeders\CapabilityCapabilityTableSeeder::class,
            'capability_role' => \Stevie\Warden\Database\Seeders\CapabilityRoleTableSeeder::class,
        ],

        /**
         * Security Gates
         */
        'gates' => [
            'before' => \Stevie\Warden\Gates\Before::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Broadcasting Channels
    |--------------------------------------------------------------------------
    |
    | This array tracks the channels on which you would like Warden to
    | broadcast its model events (created, updated, deleted, etc.).
    |
    */

    'broadcasting' => [
        'channels' => [
            new \Illuminate\Broadcasting\PrivateChannel('warden'),
        ],
    ],

];
