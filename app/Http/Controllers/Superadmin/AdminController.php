<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Helpers\ActivityLogger;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Get role admin
        $roleAdmin = Role::where('nama_role', 'admin')->first();

        $query = User::where('role_id', $roleAdmin->id);

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama', 'like', '%' . $searchTerm . '%')
                  ->orWhere('username', 'like', '%' . $searchTerm . '%');
            });
        }

        // Get users with role admin
        $admins = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('superadmin.admin', compact('admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
        ]);

        $roleAdmin = Role::where('nama_role', 'admin')->first();

        // NIK can be a placeholder for admin or same as username since it's required and unique
        // We will generate a unique NIK placeholder since users table requires nik.
        $dummyNik = 'ADM-' . time() . rand(10, 99);

        User::create([
            'role_id' => $roleAdmin->id,
            'nama' => $request->nama,
            'nik' => $dummyNik,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'status_akun' => 'aktif',
        ]);

        ActivityLogger::log('Menambahkan data admin baru: ' . $request->nama);

        return redirect()->route('superadmin.admin')->with('success', 'Data admin berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:6',
        ]);

        $updateData = [
            'nama' => $request->nama,
            'username' => $request->username,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        ActivityLogger::log('Mengubah data admin: ' . $request->nama);

        return redirect()->route('superadmin.admin')->with('success', 'Data admin berhasil diperbarui!');
    }

    public function toggleStatus(User $user)
    {
        $roleAdmin = Role::where('nama_role', 'admin')->first();

        if ($user->role_id === $roleAdmin->id) {
            $newStatus = $user->status_akun === 'aktif' ? 'nonaktif' : 'aktif';
            $user->update(['status_akun' => $newStatus]);
            
            $statusText = $newStatus === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';

            ActivityLogger::log('Mengubah status admin ' . $user->nama . ' menjadi ' . $newStatus);

            return redirect()->route('superadmin.admin')->with('success', "Akun admin berhasil {$statusText}!");
        }

        return redirect()->route('superadmin.admin')->with('error', 'Gagal mengubah status admin!');
    }

    public function destroy(User $user)
    {
        $roleAdmin = Role::where('nama_role', 'admin')->first();
        
        if ($user->role_id === $roleAdmin->id) {
            $nama = $user->nama;
            $user->delete();

            ActivityLogger::log('Menghapus data admin: ' . $nama);

            return redirect()->route('superadmin.admin')->with('success', 'Data admin berhasil dihapus!');
        }

        return redirect()->route('superadmin.admin')->with('error', 'Gagal menghapus admin!');
    }
}
