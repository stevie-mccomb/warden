<?php

namespace Stevie\Warden\Database\Seeders;

use Exception;
use Stevie\Warden\Models\Capability;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class CapabilitiesTableSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('capabilities')->truncate();

        $capabilities = array_map(function (array $capability) {
            return [
                'id' => $capability[0],
                'name' => $capability[1],
                'slug' => $capability[2],
            ];
        }, config('warden.capabilities'));

        DB::table('capabilities')->insert($capabilities);

        Schema::enableForeignKeyConstraints();
    }
}
