@extends('layouts.admin.app')

@section('content')
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-semibold text-black dark:text-white">
      Riwayat Transaksi
    </h2>
  </div>

  <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
    <!-- Header -->
    <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between" x-data="{
        search: '{{ request('search') }}',
        date: '{{ request('date') }}',
        performSearch() {
            let url = new URL(window.location.href);
            if (this.search) {
                url.searchParams.set('search', this.search);
            } else {
                url.searchParams.delete('search');
            }
            if (this.date) {
                url.searchParams.set('date', this.date);
            } else {
                url.searchParams.delete('date');
            }
            window.history.pushState({}, '', url);

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                let parser = new DOMParser();
                let doc = parser.parseFromString(html, 'text/html');
                document.getElementById('data-container').innerHTML = doc.getElementById('data-container').innerHTML;
            });
        }
    }">
      <div class="flex items-center justify-between gap-4 w-full">
        {{-- search --}}
        <div class="relative flex-1 max-w-[350px]">
            <button type="button" class="absolute -translate-y-1/2 left-4 top-1/2">
                <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" fill=""/>
                </svg>
            </button>
            <input type="text" x-model="search" @input.debounce.500ms="performSearch()" placeholder="Cari nama atau NIK nasabah..." class="h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-[42px] pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-blue-800 xl:w-[350px]"/>
        </div>

        {{-- date filter --}}
        <div class="flex items-center gap-3">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden sm:block">Filter</label>
            <div class="flex items-center gap-2">
                <div class="w-[180px]">
                    <x-form.date-picker 
                        id="filter-date" 
                        placeholder="Pilih Tanggal"
                        :defaultDate="request('date')"
                        :isStatic="false"
                        altFormat="d-m-Y"
                        @date-change="date = $event.detail.dateStr; performSearch()" 
                    />
                </div>
                <button x-show="date" x-cloak 
                        @click="date = ''; if(document.getElementById('filter-date') && document.getElementById('filter-date')._flatpickr) document.getElementById('filter-date')._flatpickr.clear(); performSearch()" 
                        type="button" 
                        class="text-sm font-medium text-red-500 hover:text-red-700 dark:hover:text-red-400 transition-colors">
                    Reset
                </button>
            </div>
        </div>
      </div>
    </div>

    <!-- Table Riwayat -->
    <div id="data-container">
      <div class="max-w-full overflow-x-auto">
        <table class="w-full">
          <thead class="px-6 py-3.5 border-t border-gray-100 border-y bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
            <tr>
              <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start w-[50px]">No</th>
              <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Tanggal</th>
              <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Nasabah</th>
              <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Jenis</th>
              <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Nominal</th>
              <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Saldo Sesudah</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($transaksis as $index => $trx)
                <tr class="border-b border-gray-100 dark:border-white/[0.05] hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                  <td class="px-4 sm:px-6 py-3.5">
                    <span class="block font-medium text-gray-700 text-theme-sm dark:text-gray-400">{{ $transaksis->firstItem() + $index }}</span>
                  </td>
                  <td class="px-4 sm:px-6 py-3.5">
                    <span class="block text-theme-sm text-gray-700 dark:text-gray-400">{{ \Carbon\Carbon::parse($trx->created_at)->translatedFormat('d M Y, H:i') }}</span>
                  </td>
                  <td class="px-4 sm:px-6 py-3.5">
                    <span class="block text-theme-sm font-medium text-gray-700 dark:text-gray-400">{{ $trx->tabungan->user->nama ?? '-' }}</span>
                    <span class="block text-xs text-gray-500 dark:text-gray-500">{{ $trx->tabungan->user->nik ?? '-' }}</span>
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
                    <span class="block text-theme-sm text-gray-700 dark:text-gray-400 font-medium">Rp {{ number_format($trx->saldo_sesudah, 0, ',', '.') }}</span>
                  </td>
                </tr>
            @empty
              <tr>
                <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                  <div class="flex flex-col items-center justify-center gap-2">
                    <i class="fa-regular fa-folder-open text-4xl mb-2 text-gray-300 dark:text-gray-600"></i>
                    <p class="text-base font-medium text-gray-700 dark:text-gray-300">Belum ada transaksi</p>
                    <p class="text-sm">Belum ada satupun transaksi tabungan yang tercatat di sistem.</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{ $transaksis->links('vendor.pagination.custom') }}
    </div>
  </div>
@endsection
