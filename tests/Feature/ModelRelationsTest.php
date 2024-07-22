<?php

use Stevie\Warden\Models\Capability;
use Stevie\Warden\Models\Role;

describe('model relations', function () {
    test('capability role', function () {
        $capability = Capability::factory()->withRole()->create();
        $role = Role::factory()->create();

        $capability->roles()->sync([ $role->id ]);

        expect($capability->roles()->value('roles.id'))->toBe($role->id);
    });

    test('role capabilities', function () {
        $capability = Capability::factory()->create();
        $role = Role::factory()->create();

        $role->capabilities()->sync([ $capability->id ]);

        expect($role->capabilities()->value('capabilities.id'))->toBe($capability->id);
    });

    test('factory relations', function () {
        $capability = Capability::factory()->withRole()->create();
        expect($capability->roles()->count())->toBe(1);

        $role = Role::factory()->withCapabilities(3)->create();
        expect($role->capabilities()->count())->toBe(3);
    });
});
