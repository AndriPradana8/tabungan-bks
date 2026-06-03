<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; padding: 0; }
        .header p { margin: 5px 0 0 0; color: #555; }
        .summary { margin-bottom: 20px; width: 100%; border-collapse: collapse; }
        .summary td { padding: 8px; border: 1px solid #ddd; width: 33.33%; text-align: center; }
        .summary-title { font-size: 10px; color: #666; text-transform: uppercase; }
        .summary-value { font-size: 16px; font-weight: bold; margin-top: 5px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 8px; border: 1px solid #ddd; text-align: left; }
        .table th { background-color: #f8f9fa; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-success { color: #10b981; }
        .text-danger { color: #ef4444; }
        .badge { padding: 3px 8px; border-radius: 12px; font-size: 10px; font-weight: bold; }
        .badge-success { background-color: #d1fae5; color: #047857; }
        .badge-danger { background-color: #fee2e2; color: #b91c1c; }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN TRANSAKSI {{ strtoupper($periode) }}</h2>
        <p>
            @if($periode === 'harian')
                Tanggal: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
            @elseif($periode === 'bulanan')
                Bulan: {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}
            @elseif($periode === 'tahunan')
                Tahun: {{ $tahun }}
            @endif
        </p>
    </div>

    <table class="summary">
        <tr>
            <td>
                <div class="summary-title">Total Transaksi</div>
                <div class="summary-value">{{ number_format($transaksis->count()) }}</div>
            </td>
            <td>
                <div class="summary-title">Total Setor</div>
                <div class="summary-value text-success">Rp {{ number_format($totalSetor, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-title">Total Tarik</div>
                <div class="summary-value text-danger">Rp {{ number_format($totalTarik, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="20%">Nama Nasabah</th>
                <th width="15%">Admin</th>
                <th width="10%">Jenis</th>
                <th class="text-right" width="15%">Nominal</th>
                <th class="text-right" width="20%">Saldo Sesudah</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksis as $index => $t)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->translatedFormat('d/m/Y') }}</td>
                    <td>{{ $t->tabungan->user->nama ?? '-' }}</td>
                    <td>{{ $t->admin->nama ?? '-' }}</td>
                    <td>
                        @if ($t->jenis_transaksi === 'setor')
                            <span class="badge badge-success">Setor</span>
                        @else
                            <span class="badge badge-danger">Tarik</span>
                        @endif
                    </td>
                    <td class="text-right {{ $t->jenis_transaksi === 'setor' ? 'text-success' : 'text-danger' }}">
                        {{ $t->jenis_transaksi === 'setor' ? '+' : '-' }} Rp {{ number_format($t->nominal, 0, ',', '.') }}
                    </td>
                    <td class="text-right">Rp {{ number_format($t->saldo_sesudah, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
