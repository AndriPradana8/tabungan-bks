@extends('layouts.nasabah.app')

@section('content')

  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">
        Selamat Datang, {{ Auth::user()->nama ?? 'Nasabah' }}
      </h2>
    </div>
  </div>

  <div class="grid grid-cols-12 gap-4 md:gap-6">

    {{-- Saldo Card --}}
    <div class="col-span-12 lg:col-span-6">
      <div class="rounded-2xl border border-gray-200 bg-gradient-to-br from-brand-500 to-brand-600 p-6 dark:border-gray-800 md:p-8">
        <div class="flex items-center gap-3 mb-4">
          <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl">
            <i class="fa-solid fa-wallet text-white text-xl"></i>
          </div>
          <span class="text-sm font-medium text-white/80">Saldo Tabungan</span>
        </div>
        <h3 class="text-3xl font-bold text-white md:text-4xl">Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
      </div>
    </div>

    {{-- Monthly Summary Cards --}}
    <div class="col-span-12 lg:col-span-6">
      <div class="grid grid-cols-2 gap-1 md:gap-2 h-full">
        {{-- Setoran Bulan Ini --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-4 md:p-6 dark:border-gray-800 dark:bg-white/[0.03] flex flex-row items-center gap-3 md:flex-col md:items-start md:justify-between">
          <div class="flex items-center justify-center w-12 h-12 bg-green-50 rounded-xl shrink-0 dark:bg-green-500/10">
            <i class="fa-solid fa-arrow-down text-green-600 text-lg"></i>
          </div>
          <div class="flex flex-col gap-0.5 md:contents">
            <span class="text-xs text-gray-500 dark:text-gray-400 md:mt-3 md:text-sm">Setoran Bulan Ini</span>
            <h4 class="font-bold text-gray-800 text-base md:text-xl dark:text-white/90">Rp {{ number_format($totalSetorBulanIni, 0, ',', '.') }}</h4>
          </div>
        </div>

        {{-- Penarikan Bulan Ini --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-4 md:p-6 dark:border-gray-800 dark:bg-white/[0.03] flex flex-row items-center gap-3 md:flex-col md:items-start md:justify-between">
          <div class="flex items-center justify-center w-12 h-12 bg-red-50 rounded-xl shrink-0 dark:bg-red-500/10">
            <i class="fa-solid fa-arrow-up text-red-600 text-lg"></i>
          </div>
          <div class="flex flex-col gap-0.5 md:contents">
            <span class="text-xs text-gray-500 dark:text-gray-400 md:mt-3 md:text-sm">Penarikan Bulan Ini</span>
            <h4 class="font-bold text-gray-800 text-base md:text-xl dark:text-white/90">Rp {{ number_format($totalTarikBulanIni, 0, ',', '.') }}</h4>
          </div>
        </div>
      </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="col-span-12">
      <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800 sm:px-6">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Transaksi Terakhir</h3>
            <a href="/nasabah/riwayat" class="text-sm font-medium text-brand-500 hover:text-brand-600 transition-colors">
              Lihat Semua <i class="fa-solid fa-arrow-right text-brand-500 text-sm"></i>
            </a>
          </div>
        </div>

        <div class="p-5 sm:p-6">
          @if($recentTransactions->isEmpty())
            <div class="text-center py-8">
              <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full dark:bg-gray-800">
                <i class="fa-solid fa-receipt text-gray-400 text-2xl"></i>
              </div>
              <p class="text-gray-500 dark:text-gray-400">Belum ada transaksi</p>
            </div>
          @else
            <div class="space-y-4">
              @foreach($recentTransactions as $trx)
                <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-gray-800/50 transition-colors hover:bg-gray-100 dark:hover:bg-gray-800">
                  <div class="flex items-center gap-4">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $trx->jenis_transaksi === 'setor' ? 'bg-green-100 dark:bg-green-500/10' : 'bg-red-100 dark:bg-red-500/10' }}">
                      @if($trx->jenis_transaksi === 'setor')
                        <i class="fa-solid fa-arrow-down text-green-600 text-md"></i>
                      @else
                        <i class="fa-solid fa-arrow-up text-red-600 text-md"></i>
                      @endif
                    </div>
                    <div>
                      <p class="font-medium text-gray-800 dark:text-white/90 capitalize">{{ $trx->jenis_transaksi }}</p>
                      <p class="text-xs text-gray-500 dark:text-gray-400">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                    </div>
                  </div>
                  <div class="text-right">
                    <p class="font-semibold {{ $trx->jenis_transaksi === 'setor' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                      {{ $trx->jenis_transaksi === 'setor' ? '+' : '-' }} Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Saldo: Rp {{ number_format($trx->saldo_sesudah, 0, ',', '.') }}</p>
                  </div>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>

  </div>
@endsection
