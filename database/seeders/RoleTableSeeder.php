<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'superadmin',
            'guard_name' => 'api'
        ]);

        Role::create([
            'name' => 'pembina',
            'guard_name' => 'api'
        ]);

        Role::create([
            'name' => 'mentor',
            'guard_name' => 'api'
        ]);

        Role::create([
            'name' => 'alumni',
            'guard_name' => 'api'
        ]);

        Role::create([
            'name' => 'bph',
            'guard_name' => 'api'
        ]);

        Role::create([
            'name' => 'pengurus_kegiatan',
            'guard_name' => 'api'
        ]);

        Role::create([
            'name' => 'pengurus_dokumentasi',
            'guard_name' => 'api'
        ]);

        Role::create([
            'name' => 'pengurus_rohis',
            'guard_name' => 'api'
        ]);
    }
}
