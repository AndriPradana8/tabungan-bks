@extends('layouts.admin.app')

@section('content')
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h2 class="text-2xl font-semibold text-black dark:text-white">
        Riwayat Tabungan
      </h2>
    </div>
    <a href="{{ route('admin.tabungan') }}"
       class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
      <i class="fa-solid fa-arrow-left"></i>
      Kembali
    </a>
  </div>

  {{-- Info Card --}}
  <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-white/[0.05] dark:bg-white/[0.03]">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-500 dark:bg-brand-500/10">
          <i class="fa-solid fa-user text-lg"></i>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400">Nama Nasabah</p>
          <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ $user->nama }}</p>
        </div>
      </div>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-white/[0.05] dark:bg-white/[0.03]">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-500 dark:bg-blue-500/10">
          <i class="fa-solid fa-id-card text-lg"></i>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400">NIK</p>
          <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ $user->nik }}</p>
        </div>
      </div>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-white/[0.05] dark:bg-white/[0.03]">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-50 text-green-500 dark:bg-green-500/10">
          <i class="fa-solid fa-wallet text-lg"></i>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400">Saldo Saat Ini</p>
          <p class="text-sm font-semibold text-gray-800 dark:text-white">Rp {{ number_format($user->tabungan ? $user->tabungan->saldo : 0, 0, ',', '.') }}</p>
        </div>
      </div>
    </div>
  </div>

  {{-- Table Riwayat --}}
  <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
    <div class="px-6 mb-4">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Daftar Transaksi</h3>
    </div>

    <div class="max-w-full overflow-x-auto">
      <table class="w-full">
        <thead class="px-6 py-3.5 border-t border-gray-100 border-y bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
          <tr>
            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Tanggal</th>
            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Jenis</th>
            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Nominal</th>
            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Saldo Sebelum</th>
            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Saldo Sesudah</th>
          </tr>
        </thead>
        <tbody>
          @if($transaksis instanceof \Illuminate\Pagination\LengthAwarePaginator && $transaksis->count() > 0)
            @foreach ($transaksis as $index => $trx)
              <tr class="border-b border-gray-100 dark:border-white/[0.05] hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                <td class="px-4 sm:px-6 py-3.5">
                  <span class="block text-theme-sm text-gray-700 dark:text-gray-400">{{ \Carbon\Carbon::parse($trx->created_at)->translatedFormat('d M Y, H:i') }}</span>
                </td>
                <td class="px-4 sm:px-6 py-3.5">
                  @if ($trx->jenis_transaksi === 'setor')
                    <span class="text-theme-xs inline-flex items-center gap-1 rounded-full px-2.5 py-1 font-medium bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500">
                      <i class="fa-solid fa-arrow-down text-[10px]"></i>
                      Setor
                    </span>
                  @else
                    <span class="text-theme-xs inline-flex items-center gap-1 rounded-full px-2.5 py-1 font-medium bg-orange-50 text-orange-700 dark:bg-orange-500/15 dark:text-orange-500">
                      <i class="fa-solid fa-arrow-up text-[10px]"></i>
                      Tarik
                    </span>
                  @endif
                </td>
                <td class="px-4 sm:px-6 py-3.5">
                  <span class="block text-theme-sm font-semibold {{ $trx->jenis_transaksi === 'setor' ? 'text-green-600 dark:text-green-500' : 'text-orange-600 dark:text-orange-500' }}">
                    {{ $trx->jenis_transaksi === 'setor' ? '+' : '-' }} Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                  </span>
                </td>
                <td class="px-4 sm:px-6 py-3.5">
                  <span class="block text-theme-sm text-gray-700 dark:text-gray-400">Rp {{ number_format($trx->saldo_sebelum, 0, ',', '.') }}</span>
                </td>
                <td class="px-4 sm:px-6 py-3.5">
                  <span class="block text-theme-sm text-gray-700 dark:text-gray-400 font-medium">Rp {{ number_format($trx->saldo_sesudah, 0, ',', '.') }}</span>
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                <div class="flex flex-col items-center justify-center gap-2">
                  <i class="fa-regular fa-folder-open text-4xl mb-2 text-gray-300 dark:text-gray-600"></i>
                  <p class="text-base font-medium text-gray-700 dark:text-gray-300">Belum ada transaksi</p>
                  <p class="text-sm">Nasabah ini belum memiliki riwayat transaksi.</p>
                </div>
              </td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>

    @if($transaksis instanceof \Illuminate\Pagination\LengthAwarePaginator)
      {{ $transaksis->links('vendor.pagination.custom') }}
    @endif
  </div>

@endsection
