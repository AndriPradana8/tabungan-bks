<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Nasabah;
use Illuminate\Support\Facades\Hash;

class NasabahSeeder extends Seeder
{
    public function run(): void
    {
        $nasabahRole = Role::where('nama_role', 'nasabah')->first();
        
        $tanggalLahir = '2003-12-20';
        // Format 20122003
        $password = \Carbon\Carbon::parse($tanggalLahir)->format('dmY');

        $user = User::create([
            'role_id' => $nasabahRole->id,
            'nama' => 'Nasabah Contoh',
            'nik' => '1234567890123456',
            'username' => 'nasabah1',
            'password' => Hash::make($password),
            'status_akun' => 'aktif',
        ]);

        Nasabah::create([
            'user_id' => $user->id,
            'tanggal_lahir' => $tanggalLahir,
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Contoh Alamat No. 1',
        ]);
    }
}
