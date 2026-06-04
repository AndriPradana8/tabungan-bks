<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
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

        return view('superadmin.nasabah', compact('nasabahs'));
    }

    public function toggleStatus(User $user)
    {
        $roleNasabah = Role::where('nama_role', 'nasabah')->first();

        if ($user->role_id === $roleNasabah->id) {
            $newStatus = $user->status_akun === 'aktif' ? 'nonaktif' : 'aktif';
            $user->update(['status_akun' => $newStatus]);
            
            $statusText = $newStatus === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';

            ActivityLogger::log('Mengubah status nasabah ' . $user->nama . ' menjadi ' . $newStatus);

            return redirect()->route('superadmin.nasabah')->with('success', "Akun nasabah berhasil {$statusText}!");
        }

        return redirect()->route('superadmin.nasabah')->with('error', 'Gagal mengubah status nasabah!');
    }

    public function destroy(User $user)
    {
        $roleNasabah = Role::where('nama_role', 'nasabah')->first();
        
        if ($user->role_id === $roleNasabah->id) {
            $nama = $user->nama;
            $user->delete();

            ActivityLogger::log('Menghapus data nasabah: ' . $nama);

            return redirect()->route('superadmin.nasabah')->with('success', 'Data nasabah berhasil dihapus!');
        }

        return redirect()->route('superadmin.nasabah')->with('error', 'Gagal menghapus nasabah!');
    }
}
