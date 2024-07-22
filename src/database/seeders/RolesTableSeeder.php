<?php

namespace Stevie\Warden\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Stevie\Warden\Models\Role;

class RolesTableSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('roles')->truncate();

        $roles = array_map(function (array $role) {
            return [
                'id' => $role[0],
                'name' => $role[1],
                'slug' => $role[2],
            ];
        }, config('warden.roles'));

        DB::table('roles')->insert($roles);

        Schema::enableForeignKeyConstraints();
    }
}
