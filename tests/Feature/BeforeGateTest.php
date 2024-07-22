<?php

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Gate;
use Tests\User;

describe('before gate', function () {
    test('with capability', function () {
        $user = Mockery::mock(User::class)->makePartial();
        $user->shouldReceive('capabilities')->andReturn($relation = Mockery::mock(BelongsToMany::class));
        $relation->shouldReceive('where')->andReturn($relation);
        $relation->shouldReceive('exists')->andReturn(true);

        expect(Gate::forUser($user)->allows('example'))->toBeTrue();
    });

    test('without capability', function () {
        $user = Mockery::mock(User::class)->makePartial();
        $user->shouldReceive('capabilities')->andReturn($relation = Mockery::mock(BelongsToMany::class));
        $relation->shouldReceive('where')->andReturn($relation);
        $relation->shouldReceive('exists')->andReturn(false);

        expect(Gate::forUser($user)->allows('example'))->toBe(false);
    });
});
