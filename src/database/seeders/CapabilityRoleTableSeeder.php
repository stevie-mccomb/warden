<?php

namespace Stevie\Warden\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Stevie\Warden\Models\Capability;
use Stevie\Warden\Models\Role;

class CapabilityRoleTableSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inserts = [];
        foreach (config('warden.capability_role_map') as $role => $capabilities) {
            foreach ($capabilities as $capability) {
                $inserts[] = [
                    'capability_id' => Capability::where('slug', $capability)->value('id'),
                    'role_id' => Role::where('slug', $role)->value('id'),
                ];
            }
        }

        Schema::disableForeignKeyConstraints();
        DB::table('capability_role')->truncate();
        DB::table('capability_role')->insert($inserts);
        Schema::enableForeignKeyConstraints();
    }
}
