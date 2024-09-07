<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nama gambar dummy yang digunakan
        $dummyImage = 'Dummy.png';

        User::create([
            'nama' => 'SuperAdmin',
            'kelas' => '-',
            'jurusan' => '-',
            'divisi' => '-',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345'),
            'image' => $dummyImage,
            'status' => 'Offline',
        ]);
        
        // Anda juga perlu mengatur status online dan offline berdasarkan aktivitas user
        // Misalnya, Anda bisa menggunakan middleware atau event listener untuk mengatur status online dan offline        

        //assign permission to role
        $role = Role::whereName('superadmin')->first();
        $permissions = Permission::all();

        $role->syncPermissions($permissions);

        //assign role with permission to user
        $user = User::find(1);
        $user->assignRole($role->name);
    }
}
