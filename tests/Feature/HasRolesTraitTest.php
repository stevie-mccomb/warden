<?php

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Stevie\Warden\Models\Capability;
use Stevie\Warden\Models\Role;
use Tests\User;

test('has roles trait', function () {
    $user = new User;
    $activeRole = Role::factory()->withCapabilities()->create();
    $inactiveRole = Role::factory()->withCapabilities()->create();

    $user->roles()->syncWithPivotValues([ $activeRole->id, $inactiveRole->id ], [ 'is_active' => false ]);
    $user->role = $activeRole;

    expect($user->roles()->count())->toBe(2); // has both roles.
    expect($user->role()->count())->toBe(1); // only one role is active.
    expect($user->role()->first()->id)->toBe($activeRole->id); // the active role is active.
    expect($user->capabilities()->count())->toBe($activeRole->capabilities()->count()); // the active role's capabilities can be fetched.
});
