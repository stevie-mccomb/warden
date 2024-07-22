<?php

use Illuminate\Support\Facades\Artisan;
use Stevie\Warden\Console\Commands\UpdateCommand;

test('update command', function () {
    class DummySeeder {
        public static $countSeedersRun = 0;
        public function run() { ++self::$countSeedersRun; }
    };

    config([
        'warden.class_map.seeders' => [
            'capabilities' => DummySeeder::class,
            'roles' => DummySeeder::class,
            'capability_capability' => DummySeeder::class,
            'capability_role' => DummySeeder::class,
        ],
    ]);

    $this->artisan('warden:update');

    expect(DummySeeder::$countSeedersRun)->toBe(count(config('warden.class_map.seeders')));
});
