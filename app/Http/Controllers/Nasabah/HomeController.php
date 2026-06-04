<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tabungan = $user->tabungan;
        $saldo = $tabungan ? $tabungan->saldo : 0;

        // Get recent transactions (last 5)
        $recentTransactions = collect();
        if ($tabungan) {
            $recentTransactions = Transaksi::where('tabungan_id', $tabungan->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        // Monthly summary (current month)
        $totalSetorBulanIni = 0;
        $totalTarikBulanIni = 0;
        if ($tabungan) {
            $totalSetorBulanIni = Transaksi::where('tabungan_id', $tabungan->id)
                ->where('jenis_transaksi', 'setor')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('nominal');

            $totalTarikBulanIni = Transaksi::where('tabungan_id', $tabungan->id)
                ->where('jenis_transaksi', 'tarik')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('nominal');
        }

        return view('nasabah.home', compact(
            'user',
            'saldo',
            'recentTransactions',
            'totalSetorBulanIni',
            'totalTarikBulanIni'
        ));
    }

    public function riwayat(Request $request)
    {
        $user = Auth::user();
        $tabungan = $user->tabungan;

        $query = Transaksi::where('tabungan_id', $tabungan ? $tabungan->id : 0)
            ->with('admin');

        if ($request->has('search') && $request->search != '') {
            $searchTerm = strtolower($request->search);
            $query->where(function($q) use ($searchTerm) {
                $q->where('jenis_transaksi', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nominal', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->has('date') && $request->date != '') {
            $query->whereDate('created_at', $request->date);
        }

        $transaksis = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('nasabah.riwayat', compact('transaksis', 'user'));
    }
}
