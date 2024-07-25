<?php

use Illuminate\Support\Facades\DB;
use Stevie\Warden\Models\Capability;
use Stevie\Warden\Models\Role;

test('capability dependency sync helper', function () {
    $capabilities = Capability::factory()->count(3)->create();

    $capabilities[0]->dependencies()->sync([
        $capabilities[1]->id,
        $capabilities[2]->id,
    ]);

    $role = Role::factory()->create();
    $role->capabilities()->syncWithDependencies([ $capabilities[0]->id ]);

    expect($role->capabilities()->count())->toBe(3);
});
