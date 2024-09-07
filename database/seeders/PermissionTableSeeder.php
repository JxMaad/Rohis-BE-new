<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat izin untuk pengguna
        Permission::create(['name' => 'users.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'users.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'users.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'users.delete', 'guard_name' => 'api']);

        // Membuat izin untuk kegiatan
        Permission::create(['name' => 'kegiatan.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'kegiatan.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'kegiatan.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'kegiatan.delete', 'guard_name' => 'api']);

        // Membuat izin untuk dokumentasi
        Permission::create(['name' => 'dokumentasi.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'dokumentasi.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'dokumentasi.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'dokumentasi.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'pendaftaran.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'pendaftaran.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'pendaftaran.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'pendaftaran.delete', 'guard_name' => 'api']);

        Permission::create(['name' => 'mentoring.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'mentoring.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'mentoring.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'mentoring.delete', 'guard_name' => 'api']);

        // Menetapkan izin ke peran
        $roles = Role::all();

        foreach ($roles as $role) {
            // Cek peran dan tetapkan izin
            if ($role->name === 'superadmin') {
                $role->syncPermissions(['users.index','users.create','users.edit','users.delete','kegiatan.index','kegiatan.create','kegiatan.edit','kegiatan.delete','dokumentasi.index','dokumentasi.create','dokumentasi.edit','dokumentasi.delete','pendaftaran.index','pendaftaran.create','pendaftaran.edit','pendaftaran.delete','mentoring.index','mentoring.create','mentoring.edit','mentoring.delete']);
            } elseif ($role->name === 'pembina') {
                $role->syncPermissions(['users.index','users.edit','kegiatan.index','dokumentasi.index','pendaftaran.index','mentoring.index','mentoring.create','mentoring.edit','mentoring.delete']);
            } elseif ($role->name === 'mentor') {
                $role->syncPermissions(['users.index','users.edit','kegiatan.index','kegiatan.create','kegiatan.edit','kegiatan.delete','dokumentasi.index','dokumentasi.create','dokumentasi.edit','dokumentasi.delete','pendaftaran.index','mentoring.index','mentoring.create','mentoring.edit','mentoring.delete']);
            } elseif ($role->name === 'alumni') {
                $role->syncPermissions(['users.index','users.edit','kegiatan.index','dokumentasi.index','pendaftaran.index']);
            } elseif ($role->name === 'bph') {
                $role->syncPermissions(['users.index','users.create','users.edit','users.delete','kegiatan.index','kegiatan.create','kegiatan.edit','kegiatan.delete','dokumentasi.index','dokumentasi.create','dokumentasi.edit','dokumentasi.delete','pendaftaran.index','pendaftaran.create','pendaftaran.edit','pendaftaran.delete','mentoring.index','mentoring.create','mentoring.edit','mentoring.delete']);
            } elseif ($role->name === 'pengurus_kegiatan') {
                $role->syncPermissions(['users.index','users.edit','kegiatan.index','kegiatan.create','kegiatan.edit','kegiatan.delete','pendaftaran.index']);
            } elseif ($role->name === 'pengurus_dokumentasi') {
                $role->syncPermissions(['users.index','users.edit','kegiatan.index','pendaftaran.index','dokumentasi.index','dokumentasi.create','dokumentasi.edit','dokumentasi.delete']);
            } elseif ($role->name === 'pengurus_rohis') {
                $role->syncPermissions(['users.index','users.edit','kegiatan.index','dokumentasi.index','pendaftaran.index']);
            }
        }
    }
}
