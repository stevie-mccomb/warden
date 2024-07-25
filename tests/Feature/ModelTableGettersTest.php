<?php

use Stevie\Warden\Models\Capability;
use Stevie\Warden\Models\Role;

test('model table getters', function () {
    config([
        'warden.tables.capabilities' => 'custom_capabilities',
        'warden.tables.roles' => 'custom_roles',
    ]);

    expect((new Capability)->table)->toBe('custom_capabilities');
    expect((new Role)->table)->toBe('custom_roles');
});
