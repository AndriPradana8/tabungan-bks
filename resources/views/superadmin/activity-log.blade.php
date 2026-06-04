@extends('layouts.superadmin.app')

@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-2xl font-semibold text-black dark:text-white">Log Aktivitas</h2>
    </div>
</div>

<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
    {{-- Search --}}
    <div class="px-6 mb-4">
        <form @submit.prevent x-data="{
            search: '{{ request('search') }}',
            performSearch() {
                let url = new URL(window.location.href);
                if (this.search) {
                    url.searchParams.set('search', this.search);
                } else {
                    url.searchParams.delete('search');
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
            <div class="relative">
                <button type="submit" class="absolute -translate-y-1/2 left-4 top-1/2">
                    <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" fill=""/>
                    </svg>
                </button>
                <input type="text" name="search" x-model="search" @input.debounce.500ms="performSearch()" placeholder="Cari aktivitas atau nama admin..."
                    class="h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-[42px] pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 xl:w-[350px]"/>
            </div>
        </form>
    </div>

    <div id="data-container">
        {{-- Table --}}
        <div class="max-w-full overflow-x-auto">
        <table class="w-full">
            <thead class="border-y border-gray-100 bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 w-[50px]">No</th>
                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400">Admin</th>
                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400">Aktivitas</th>
                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $i => $log)
                    <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                        <td class="px-6 py-3.5 text-sm text-gray-700 dark:text-gray-400">{{ $logs->firstItem() + $i }}</td>
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $log->user->nama ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3.5 text-sm text-gray-700 dark:text-gray-400 max-w-[350px]">
                            {{ $log->aktivitas }}
                        </td>
                        <td class="px-6 py-3.5 text-sm text-gray-700 dark:text-gray-400 whitespace-nowrap">
                            <div>
                                <p class="text-sm">{{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('d M Y') }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }} WIB</p>
                            </div>
                        </td>
                        {{-- <td class="px-6 py-3.5">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                <i class="fa-solid fa-globe text-[10px]"></i>
                                {{ $log->ip_address }}
                            </span>
                        </td> --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <i class="fa-regular fa-folder-open text-4xl mb-2 text-gray-300 dark:text-gray-600"></i>
                                <p class="text-base font-medium text-gray-700 dark:text-gray-300">Belum ada log aktivitas</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Aktivitas admin akan tercatat di sini secara otomatis.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
        {{ $logs->links('vendor.pagination.custom') }}
    </div>
</div>
@endsection
