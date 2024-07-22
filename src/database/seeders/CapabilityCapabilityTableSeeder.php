<?php

namespace Stevie\Warden\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Stevie\Warden\Models\Capability;

class CapabilityCapabilityTableSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inserts = [];
        foreach (config('warden.capability_dependency_map') as $dependent => $dependencies) {
            foreach ($dependencies as $dependency) {
                $inserts[] = [
                    'dependency_id' => Capability::where('slug', $dependency)->value('id'),
                    'dependent_id' => Capability::where('slug', $dependent)->value('id'),
                ];
            }
        }

        Schema::disableForeignKeyConstraints();
        DB::table('capability_capability')->truncate();
        DB::table('capability_capability')->insert($inserts);
        Schema::enableForeignKeyConstraints();
    }
}
