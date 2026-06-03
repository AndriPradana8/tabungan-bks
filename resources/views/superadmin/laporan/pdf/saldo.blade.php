<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Saldo Nasabah</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; padding: 0; }
        .header p { margin: 5px 0 0 0; color: #555; }
        .summary { margin-bottom: 20px; width: 100%; border-collapse: collapse; }
        .summary td { padding: 8px; border: 1px solid #ddd; width: 50%; text-align: center; }
        .summary-title { font-size: 10px; color: #666; text-transform: uppercase; }
        .summary-value { font-size: 16px; font-weight: bold; margin-top: 5px; color: #2563eb; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 8px; border: 1px solid #ddd; text-align: left; }
        .table th { background-color: #f8f9fa; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge { padding: 3px 8px; border-radius: 12px; font-size: 10px; font-weight: bold; }
        .badge-success { background-color: #d1fae5; color: #047857; }
        .badge-warning { background-color: #fef3c7; color: #b45309; }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN SALDO NASABAH</h2>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</p>
    </div>

    <table class="summary">
        <tr>
            <td>
                <div class="summary-title">Total Nasabah</div>
                <div class="summary-value" style="color: #333;">{{ number_format($tabungans->count()) }}</div>
            </td>
            <td>
                <div class="summary-title">Total Saldo Keseluruhan</div>
                <div class="summary-value">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="30%">Nama Nasabah</th>
                <th width="25%">NIK</th>
                <th width="15%" class="text-center">Status Akun</th>
                <th class="text-right" width="25%">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tabungans as $index => $tabungan)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $tabungan->nama ?? '-' }}</td>
                    <td>{{ $tabungan->nik ?? '-' }}</td>
                    <td class="text-center">
                        @if (($tabungan->status_akun ?? '') === 'aktif')
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-warning">Tidak Aktif</span>
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($tabungan->tabungan->saldo ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data saldo nasabah.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
