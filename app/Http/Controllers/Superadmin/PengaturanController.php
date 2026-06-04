<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Helpers\ActivityLogger;

class PengaturanController extends Controller
{
    public function index()
    {
        return view('superadmin.pengaturan');
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
        ]);

        $user->update([
            'nama' => $request->nama,
            'username' => $request->username,
        ]);

        ActivityLogger::log('Memperbarui profil akun');

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        ActivityLogger::log('Mengubah password akun');

        return back()->with('success_password', 'Password berhasil diperbarui!');
    }
}
