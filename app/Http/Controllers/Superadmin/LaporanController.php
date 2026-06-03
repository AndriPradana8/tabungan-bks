<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Tabungan;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function transaksi(Request $request)
    {
        $periode = $request->get('periode', 'harian');

        $query = Transaksi::with(['tabungan.user', 'admin'])
            ->orderBy('tanggal_transaksi', 'desc');

        $tanggal = null;
        $bulan   = null;
        $tahun   = null;

        if ($periode === 'harian') {
            $tanggal = $request->get('tanggal', Carbon::today()->toDateString());
            $query->whereDate('tanggal_transaksi', $tanggal);
        } elseif ($periode === 'bulanan') {
            $bulan = $request->get('bulan', Carbon::now()->month);
            $tahun = $request->get('tahun', Carbon::now()->year);
            $query->whereMonth('tanggal_transaksi', $bulan)
                  ->whereYear('tanggal_transaksi', $tahun);
        } elseif ($periode === 'tahunan') {
            $tahun = $request->get('tahun', Carbon::now()->year);
            $query->whereYear('tanggal_transaksi', $tahun);
        }

        $transaksis   = $query->paginate(15)->withQueryString();
        $totalSetor   = (clone $query->getQuery())->where('jenis_transaksi', 'setor')->sum('nominal');
        $totalTarik   = (clone $query->getQuery())->where('jenis_transaksi', 'tarik')->sum('nominal');

        // Recalculate totals from the base filtered query (not paginated)
        $baseQuery    = Transaksi::orderBy('tanggal_transaksi', 'desc');
        if ($periode === 'harian') {
            $baseQuery->whereDate('tanggal_transaksi', $tanggal);
        } elseif ($periode === 'bulanan') {
            $baseQuery->whereMonth('tanggal_transaksi', $bulan)->whereYear('tanggal_transaksi', $tahun);
        } elseif ($periode === 'tahunan') {
            $baseQuery->whereYear('tanggal_transaksi', $tahun);
        }

        $totalSetor = (clone $baseQuery)->where('jenis_transaksi', 'setor')->sum('nominal');
        $totalTarik = (clone $baseQuery)->where('jenis_transaksi', 'tarik')->sum('nominal');
        $totalCount = $baseQuery->count();

        return view('superadmin.laporan.transaksi', compact(
            'transaksis', 'periode', 'tanggal', 'bulan', 'tahun',
            'totalSetor', 'totalTarik', 'totalCount'
        ));
    }

    public function saldo(Request $request)
    {
        $roleNasabah = Role::where('nama_role', 'nasabah')->first();
        $search = $request->get('search', '');

        $query = User::with('tabungan')
            ->where('role_id', $roleNasabah->id)
            ->where('status_akun', 'aktif');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('nik', 'like', "%$search%");
            });
        }

        // We can't directly order by a relationship column easily without a join,
        // so we'll join tabungans table just for ordering.
        $query->leftJoin('tabungans', 'users.id', '=', 'tabungans.user_id')
              ->select('users.*', 'tabungans.saldo as tabungan_saldo')
              ->orderByRaw('COALESCE(tabungans.saldo, 0) DESC');

        $users = $query->paginate(15)->withQueryString();
        $totalSaldo  = Tabungan::whereHas('user', fn($q) => $q->where('role_id', $roleNasabah->id)->where('status_akun', 'aktif'))->sum('saldo');
        $totalNasabah = User::where('role_id', $roleNasabah->id)->where('status_akun', 'aktif')->count();

        // Get all balances for Alpine calculation
        $allBalances = User::where('role_id', $roleNasabah->id)
            ->where('status_akun', 'aktif')
            ->leftJoin('tabungans', 'users.id', '=', 'tabungans.user_id')
            ->pluck('tabungans.saldo', 'users.id')
            ->map(fn($val) => $val ?? 0)
            ->toArray();

        return view('superadmin.laporan.saldo', [
            'tabungans' => $users,
            'totalSaldo' => $totalSaldo,
            'totalNasabah' => $totalNasabah,
            'search' => $search,
            'allBalances' => $allBalances
        ]);
    }

    public function exportPdf(Request $request)
    {
        $type = $request->get('type', 'transaksi');

        if ($type === 'saldo') {
            $roleNasabah = Role::where('nama_role', 'nasabah')->first();
            $query = User::with('tabungan')
                ->where('role_id', $roleNasabah->id)
                ->where('status_akun', 'aktif');

            if ($request->filled('selected_ids')) {
                $ids = array_filter(explode(',', $request->get('selected_ids')));
                if (count($ids) > 0) {
                    $query->whereIn('users.id', $ids);
                }
            }

            $users = $query->leftJoin('tabungans', 'users.id', '=', 'tabungans.user_id')
                ->select('users.*', 'tabungans.saldo as tabungan_saldo')
                ->orderByRaw('COALESCE(tabungans.saldo, 0) DESC')
                ->get();

            $totalSaldo  = $users->sum('tabungan_saldo');

            $pdf = Pdf::loadView('superadmin.laporan.pdf.saldo', ['tabungans' => $users, 'totalSaldo' => $totalSaldo])
                ->setPaper('a4', 'portrait');

            return $pdf->download('laporan-saldo-nasabah-' . Carbon::now()->format('Ymd') . '.pdf');
        }

        // Default: transaksi
        $periode = $request->get('periode', 'harian');
        $tanggal = $request->get('tanggal', Carbon::today()->toDateString());
        $bulan   = $request->get('bulan', Carbon::now()->month);
        $tahun   = $request->get('tahun', Carbon::now()->year);

        $query = Transaksi::with(['tabungan.user', 'admin'])->orderBy('tanggal_transaksi', 'desc');

        if ($periode === 'harian') {
            $query->whereDate('tanggal_transaksi', $tanggal);
        } elseif ($periode === 'bulanan') {
            $query->whereMonth('tanggal_transaksi', $bulan)->whereYear('tanggal_transaksi', $tahun);
        } elseif ($periode === 'tahunan') {
            $query->whereYear('tanggal_transaksi', $tahun);
        }

        $transaksis = $query->get();
        $totalSetor = $transaksis->where('jenis_transaksi', 'setor')->sum('nominal');
        $totalTarik = $transaksis->where('jenis_transaksi', 'tarik')->sum('nominal');

        $pdf = Pdf::loadView('superadmin.laporan.pdf.transaksi', compact(
            'transaksis', 'periode', 'tanggal', 'bulan', 'tahun', 'totalSetor', 'totalTarik'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-transaksi-' . $periode . '-' . Carbon::now()->format('Ymd') . '.pdf');
    }
}
