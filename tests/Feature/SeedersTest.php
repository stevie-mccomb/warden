<?php

use Illuminate\Support\Facades\DB;
use Stevie\Warden\Database\Seeders\CapabilitiesTableSeeder;
use Stevie\Warden\Database\Seeders\CapabilityCapabilityTableSeeder;
use Stevie\Warden\Database\Seeders\CapabilityRoleTableSeeder;
use Stevie\Warden\Database\Seeders\RolesTableSeeder;
use Stevie\Warden\Models\Capability;
use Stevie\Warden\Models\Role;

describe('seeders', function () {
    test('capabilities', function () {
        config([
            'warden.capabilities' => [
                [ 1, 'View Example', 'view-example' ],
                [ 2, 'Create Example', 'create-example' ],
                [ 3, 'Update Example', 'update-example' ],
                [ 4, 'Delete Example', 'delete-example' ],
            ],
            'warden.roles' => [
                [ 1, 'Example', 'example' ],
            ],
            'warden.capability_role_map' => [
                'example' => [
                    'view-example',
                    'create-example',
                ],
            ],
            'warden.capability_dependency_map' => [
                'create-example' => [
                    'view-example',
                ],
                'update-example' => [
                    'view-example',
                    'create-example',
                ],
            ],
        ]);

        (new CapabilitiesTableSeeder)->run();
        (new RolesTableSeeder)->run();
        (new CapabilityCapabilityTableSeeder)->run();
        (new CapabilityRoleTableSeeder)->run();

        expect(DB::table('capabilities')->orderBy('id')->pluck('slug')->toArray())->toBe([ 'view-example', 'create-example', 'update-example', 'delete-example' ]);
        expect(Capability::where('slug', 'create-example')->first()->dependencies()->first()->id)->toBe(Capability::where('slug', 'view-example')->value('id'));
        expect(Capability::where('slug', 'view-example')->first()->dependents()->orderBy('capabilities.id')->pluck('slug')->toArray())->toBe([ 'create-example', 'update-example' ]);
        expect(Role::where('slug', 'example')->first()->capabilities()->orderBy('capabilities.id')->pluck('slug')->toArray())->toBe([ 'view-example', 'create-example' ]);
    });
});
