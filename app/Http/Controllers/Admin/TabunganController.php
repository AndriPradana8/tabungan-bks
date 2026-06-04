<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Tabungan;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ActivityLogger;

class TabunganController extends Controller
{
    public function index(Request $request)
    {
        $roleNasabah = Role::where('nama_role', 'nasabah')->first();

        $query = User::where('role_id', $roleNasabah->id)
            ->where('status_akun', 'aktif')
            ->with(['nasabah', 'tabungan']);

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nik', 'like', '%' . $searchTerm . '%');
            });
        }

        $nasabahs = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.tabungan', compact('nasabahs'));
    }

    public function setor(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nominal' => 'required|numeric|min:1000',
        ]);

        DB::beginTransaction();
        try {
            $user = User::with('tabungan')->findOrFail($request->user_id);
            $tabungan = $user->tabungan;

            // If somehow tabungan doesn't exist, create it
            if (!$tabungan) {
                $tabungan = Tabungan::create(['user_id' => $user->id, 'saldo' => 0]);
            }

            $saldoSebelum = $tabungan->saldo;
            $saldoSesudah = $saldoSebelum + $request->nominal;

            $tabungan->update(['saldo' => $saldoSesudah]);

            Transaksi::create([
                'tabungan_id' => $tabungan->id,
                'admin_id' => Auth::id(),
                'jenis_transaksi' => 'setor',
                'nominal' => $request->nominal,
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $saldoSesudah,
                'tanggal_transaksi' => now()->toDateString(),
            ]);

            DB::commit();

            ActivityLogger::log('Menyetorkan saldo Rp ' . number_format($request->nominal, 0, ',', '.') . ' ke ' . $user->nama);

            return redirect()->back()->with('success', 'Setoran berhasil diproses!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses setoran.');
        }
    }

    public function tarik(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nominal' => 'required|numeric|min:1000',
        ]);

        DB::beginTransaction();
        try {
            $user = User::with('tabungan')->findOrFail($request->user_id);
            $tabungan = $user->tabungan;

            if (!$tabungan) {
                return redirect()->back()->with('error', 'Data tabungan tidak ditemukan.');
            }

            if ($tabungan->saldo < $request->nominal) {
                return redirect()->back()->with('error', 'Saldo tidak mencukupi untuk penarikan.');
            }

            $saldoSebelum = $tabungan->saldo;
            $saldoSesudah = $saldoSebelum - $request->nominal;

            $tabungan->update(['saldo' => $saldoSesudah]);

            Transaksi::create([
                'tabungan_id' => $tabungan->id,
                'admin_id' => Auth::id(),
                'jenis_transaksi' => 'tarik',
                'nominal' => $request->nominal,
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $saldoSesudah,
                'tanggal_transaksi' => now()->toDateString(),
            ]);

            DB::commit();

            ActivityLogger::log('Menarik saldo Rp ' . number_format($request->nominal, 0, ',', '.') . ' dari ' . $user->nama);

            return redirect()->back()->with('success', 'Penarikan berhasil diproses!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses penarikan.');
        }
    }

    public function riwayat(User $user)
    {
        $user->load(['nasabah', 'tabungan.transaksis' => function($query) {
            $query->orderBy('created_at', 'desc');
        }, 'tabungan.transaksis.admin']);

        $transaksis = $user->tabungan
            ? $user->tabungan->transaksis()->with('admin')->orderBy('created_at', 'desc')->paginate(10)
            : collect();

        return view('admin.riwayat-tabungan', compact('user', 'transaksis'));
    }

    public function semuaRiwayat(Request $request)
    {
        $query = Transaksi::with(['admin', 'tabungan.user']);

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->whereHas('tabungan.user', function($q) use ($searchTerm) {
                $q->where('nama', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nik', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->has('date') && $request->date != '') {
            $query->whereDate('created_at', $request->date);
        }

        $transaksis = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.semua-riwayat', compact('transaksis'));
    }
}
