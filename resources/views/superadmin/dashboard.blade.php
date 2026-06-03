@extends('layouts.superadmin.app')

@section('content')

  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-semibold text-black dark:text-white">
      Dashboard
    </h2>
  </div>

  <div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
          <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800">
            <i class="fa-solid fa-users text-brand-500 text-[18px]"></i>
          </div>
          <span class="text-sm text-gray-500 dark:text-gray-400">Total Nasabah</span>
          <div>
            <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ number_format($totalNasabah, 0, ',', '.') }}</h4>
              </div>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
          <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800">
            <i class="fa-solid fa-money-bill-wave text-green-500 text-[18px]"></i>
          </div>
          <span class="text-sm text-gray-500 dark:text-gray-400">Total Tabungan</span>
          <div>
            <h4 class="mt-2 font-bold text-gray-800 text-xl dark:text-white/90">Rp {{ number_format($totalTabungan, 0, ',', '.') }}</h4>
              </div>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
          <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800">
            <i class="fa-solid fa-arrow-down text-green-500 text-[18px]"></i>
          </div>
          <span class="text-sm text-gray-500 dark:text-gray-400">Setoran hari ini</span>
          <div>
            <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">Rp {{ number_format($setoranHariIni, 0, ',', '.') }}</h4>
              </div>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
          <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800">
            <i class="fa-solid fa-arrow-up text-red-500 text-[18px]"></i>
          </div>
          <span class="text-sm text-gray-500 dark:text-gray-400">Penarikan hari ini</span>
          <div>
            <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">Rp {{ number_format($penarikanHariIni, 0, ',', '.') }}</h4>
              </div>
        </div>
      </div>
    </div>

    <div class="col-span-12">
      <x-ecommerce.statistics-chart 
        :setoran="json_encode($monthlySetor)" 
        :tarikan="json_encode($monthlyTarik)" 
      />
    </div>

  </div>
@endsection
