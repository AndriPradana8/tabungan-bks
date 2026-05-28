<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superadminRole = Role::where('nama_role', 'superadmin')->first();
        $adminRole = Role::where('nama_role', 'admin')->first();

        User::create([
            'role_id' => $superadminRole->id,
            'nama' => 'Super Administrator',
            'nik' => '0000000000000001',
            'username' => 'superadmin',
            'password' => Hash::make('password'),
            'status_akun' => 'aktif',
        ]);

        User::create([
            'role_id' => $adminRole->id,
            'nama' => 'Administrator',
            'nik' => '0000000000000002',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'status_akun' => 'aktif',
        ]);
    }
}
