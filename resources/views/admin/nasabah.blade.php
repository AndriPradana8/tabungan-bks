@extends('layouts.admin.app')

@section('content')
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-semibold text-black dark:text-white">
      Data Nasabah
    </h2>
  </div>

  @if (session('success'))
    <div x-data="{ show: true }" 
         x-init="setTimeout(() => show = false, 3000)" 
         x-show="show" 
         x-transition.duration.500ms
         class="fixed top-24 right-5 z-[9999] flex w-full max-w-[400px] items-start gap-4 rounded-xl border border-emerald-500 bg-emerald-50 px-5 py-4 shadow-sm dark:bg-gray-800 dark:border-emerald-500">
      <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full border-[1.5px] border-emerald-500 text-emerald-500 mt-0.5">
        <i class="fa-solid fa-check text-[12px] font-bold"></i>
      </div>
      <div class="w-full">
        <h5 class="mb-1 text-[15px] font-semibold text-slate-800 dark:text-white">Berhasil!</h5>
        <p class="text-[14px] leading-relaxed text-slate-500 dark:text-gray-400">{{ session('success') }}</p>
      </div>
      <button @click="show = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-gray-300 transition-colors">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
  @endif

  <div x-data>
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
        <!-- Header -->
        <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                {{-- search --}}
                <form @submit.prevent>
                    <div class="relative" x-data="{
                        search: '{{ request('search') }}',
                        performSearch() {
                            let url = new URL(window.location.href);
                            url.searchParams.set('search', this.search);
                            // Set search param in the URL without reloading the page
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
                        <button type="submit" class="absolute -translate-y-1/2 left-4 top-1/2">
                            <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" fill=""/>
                            </svg>
                        </button>
                        <input type="text" x-model="search" @input.debounce.500ms="performSearch()" placeholder="Cari nama atau NIK nasabah..." class="h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-[42px] pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-blue-800 xl:w-[300px]"/>
                    </div>
                </form>
            </div>
            <div class="flex items-center gap-3">
                <button @click="$dispatch('open-modal-tambah-nasabah')" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 dark:bg-brand-500 dark:hover:bg-brand-600">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Nasabah
                </button>
            </div>
        </div>

        <!-- Table -->
        <div id="data-container">
            <div class="max-w-full overflow-x-auto">
            <table class="w-full">
                <thead class="px-6 py-3.5 border-t border-gray-100 border-y bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">No</th>
                        <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Nama Nasabah</th>
                        <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">NIK</th>
                        <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Status</th>
                        <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($nasabahs as $index => $user)
                        <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                            <td class="px-4 sm:px-6 py-3.5">
                                <span class="block font-medium text-gray-700 text-theme-sm dark:text-gray-400">{{ $nasabahs->firstItem() + $index }}</span>
                            </td>
                            <td class="px-4 sm:px-6 py-3.5">
                                <span class="block text-theme-sm font-medium text-gray-700 dark:text-gray-400">{{ $user->nama }}</span>
                            </td>
                            <td class="px-4 sm:px-6 py-3.5">
                                <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $user->nik }}</p>
                            </td>
                            <td class="px-4 sm:px-6 py-3.5">
                                @if ($user->status_akun === 'aktif')
                                    <span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500">
                                        Aktif
                                    </span>
                                @else
                                    <span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium bg-orange-50 text-orange-700 dark:bg-orange-500/15 dark:text-orange-500">
                                          Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <button @click="$dispatch('open-modal-detail-nasabah', {
                                        nama: '{{ addslashes($user->nama) }}',
                                        nik: '{{ addslashes($user->nik) }}',
                                        no_hp: '{{ addslashes($user->nasabah->no_hp ?? '') }}',
                                        tanggal_lahir: '{{ !empty($user->nasabah->tanggal_lahir) ? \Carbon\Carbon::parse($user->nasabah->tanggal_lahir)->translatedFormat('d F Y') : '' }}',
                                        alamat: '{{ addslashes($user->nasabah->alamat ?? '') }}',
                                        status_akun: '{{ addslashes($user->status_akun) }}'
                                    })" class="hover:text-brand-500 text-gray-500 transition-colors">
                                        <i class="fa-solid fa-eye text-lg"></i>
                                    </button>
                                    <button @click="$dispatch('open-modal-edit-nasabah', {
                                        id: {{ $user->id }},
                                        nama: '{{ addslashes($user->nama) }}',
                                        nik: '{{ addslashes($user->nik) }}',
                                        no_hp: '{{ addslashes($user->nasabah->no_hp ?? '') }}',
                                        tanggal_lahir: '{{ addslashes($user->nasabah->tanggal_lahir ?? '') }}',
                                        alamat: '{{ addslashes($user->nasabah->alamat ?? '') }}'
                                    })" class="hover:text-yellow-500 text-gray-500 transition-colors">
                                        <i class="fa-solid fa-pen-to-square text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fa-regular fa-folder-open text-4xl mb-2 text-gray-300 dark:text-gray-600"></i>
                                    <p class="text-base font-medium text-gray-700 dark:text-gray-300">Data tidak ditemukan</p>
                                    <p class="text-sm">Tidak ada nasabah yang cocok dengan pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $nasabahs->links('vendor.pagination.custom') }}
        </div>


    </div>
</div>

@include('admin.modal.modal-tambah-data')
@include('admin.modal.modal-edit-data')
@include('admin.modal.modal-detail-data')

@endsection
