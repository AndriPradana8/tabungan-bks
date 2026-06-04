<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Nasabah;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Helpers\ActivityLogger;

class NasabahController extends Controller
{
    public function index(Request $request)
    {
        // Get role nasabah
        $roleNasabah = Role::where('nama_role', 'nasabah')->first();

        $query = User::where('role_id', $roleNasabah->id)->with('nasabah');

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nik', 'like', '%' . $searchTerm . '%');
            });
        }

        // Get users with role nasabah, including their nasabah profile
        $nasabahs = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.nasabah', compact('nasabahs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|unique:users,nik',
            'no_hp' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
        ]);

        $roleNasabah = Role::where('nama_role', 'nasabah')->first();

        // Password is date of birth (DDMMYYYY)
        $passwordDate = Carbon::parse($request->tanggal_lahir)->format('dmY');

        $user = User::create([
            'role_id' => $roleNasabah->id,
            'nama' => $request->nama,
            'nik' => $request->nik,
            'username' => $request->nik,
            'password' => Hash::make($passwordDate),
            'status_akun' => 'aktif',
        ]);

        Nasabah::create([
            'user_id' => $user->id,
            'tanggal_lahir' => $request->tanggal_lahir,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        \App\Models\Tabungan::create([
            'user_id' => $user->id,
            'saldo' => 0,
        ]);

        ActivityLogger::log('Menambahkan data nasabah baru: ' . $request->nama);

        return redirect()->route('admin.nasabah')->with('success', 'Data nasabah berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|unique:users,nik,' . $user->id,
            'no_hp' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
        ]);

        $user->update([
            'nama' => $request->nama,
            'nik' => $request->nik,
            // If username needs to be updated with NIK:
            'username' => $request->nik,
        ]);

        if ($user->nasabah) {
            $user->nasabah->update([
                'tanggal_lahir' => $request->tanggal_lahir,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);
        }

        ActivityLogger::log('Mengubah data nasabah: ' . $request->nama);

        return redirect()->route('admin.nasabah')->with('success', 'Data nasabah berhasil diperbarui!');
    }
}
