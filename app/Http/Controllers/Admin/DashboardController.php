<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tabungan;
use App\Models\Transaksi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalNasabah = User::whereHas('role', function($query) {
            $query->where('nama_role', 'nasabah');
        })->count();
        $totalTabungan = Tabungan::sum('saldo');
        
        $today = Carbon::today();
        
        $setoranHariIni = Transaksi::where('jenis_transaksi', 'setor')
            ->whereDate('created_at', $today)
            ->sum('nominal');
            
        $penarikanHariIni = Transaksi::where('jenis_transaksi', 'tarik')
            ->whereDate('created_at', $today)
            ->sum('nominal');

        // Chart Data (Setor dan Tarik per bulan di tahun ini)
        $monthlySetor = array_fill(0, 12, 0);
        $monthlyTarik = array_fill(0, 12, 0);

        $transaksis = Transaksi::whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) as month, jenis_transaksi, SUM(nominal) as total')
            ->groupBy('month', 'jenis_transaksi')
            ->get();

        foreach ($transaksis as $trx) {
            if ($trx->jenis_transaksi == 'setor') {
                $monthlySetor[$trx->month - 1] = (int) $trx->total;
            } else {
                $monthlyTarik[$trx->month - 1] = (int) $trx->total;
            }
        }

        return view('admin.dashboard', compact(
            'totalNasabah', 
            'totalTabungan', 
            'setoranHariIni', 
            'penarikanHariIni',
            'monthlySetor',
            'monthlyTarik'
        ));
    }
}
