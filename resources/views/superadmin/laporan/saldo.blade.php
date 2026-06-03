@extends('layouts.superadmin.app')

@section('content')
<div x-data="saldoCalculator()">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-2xl font-semibold text-black dark:text-white">Saldo Tabungan</h2>
        
        <form method="GET" action="{{ route('superadmin.laporan.export-pdf') }}" class="inline-block" @submit="onSubmitExport">
            <input type="hidden" name="type" value="saldo">
            <input type="hidden" name="selected_ids" :value="selectedIds.join(',')">
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition-colors">
                <i class="fa-solid fa-file-pdf"></i> Export PDF
            </button>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400" x-text="isAllSelected ? 'Total Nasabah Aktif' : 'Total Nasabah Terpilih'">Total Nasabah Terpilih</p>
            <p class="mt-1 text-2xl font-bold text-gray-800 dark:text-white" x-text="totalNasabahTerpilih"></p>
        </div>
        <div class="rounded-2xl border border-brand-200 bg-brand-50 p-5 dark:border-brand-500/20 dark:bg-brand-500/10">
            <p class="text-sm text-brand-600 dark:text-brand-400" x-text="isAllSelected ? 'Total Saldo Keseluruhan' : 'Total Saldo Terpilih'">Total Saldo Terpilih</p>
            <p class="mt-1 text-2xl font-bold text-brand-700 dark:text-brand-300" x-text="formatRupiah(totalSaldoTerpilih)"></p>
        </div>
    </div>

    {{-- Search + Table --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
        <div class="px-6 mb-4">
            <form method="GET" action="{{ route('superadmin.laporan.saldo') }}" x-data="{
                search: '{{ $search }}',
                performSearch() {
                    this.$el.submit();
                }
            }">
                <div class="relative">
                    <button type="submit" class="absolute -translate-y-1/2 left-4 top-1/2">
                        <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" fill=""/>
                        </svg>
                    </button>
                    <input type="text" name="search" x-model="search" @input.debounce.500ms="performSearch()" placeholder="Cari nama atau NIK nasabah..."
                        class="h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-[42px] pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 xl:w-[300px]"/>
                </div>
            </form>
        </div>

        <div class="max-w-full overflow-x-auto">
            <table class="w-full">
                <thead class="border-y border-gray-100 bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-start w-[50px]">
                            <input type="checkbox" :checked="isAllSelected" @change="toggleAll()" class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                        </th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400 w-[50px]">No</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400">Nama Nasabah</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400">NIK</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400">Status Akun</th>
                        <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tabungans as $i => $tabungan)
                        <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                            <td class="px-6 py-3.5">
                                <input type="checkbox" value="{{ $tabungan->id }}" x-model.number="selectedIds" class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                            </td>
                            <td class="px-6 py-3.5 text-sm text-gray-700 dark:text-gray-400">{{ $tabungans->firstItem() + $i }}</td>
                            <td class="px-6 py-3.5 text-sm font-medium text-gray-700 dark:text-gray-400">{{ $tabungan->nama ?? '-' }}</td>
                            <td class="px-6 py-3.5 text-sm text-gray-700 dark:text-gray-400">{{ $tabungan->nik ?? '-' }}</td>
                            <td class="px-6 py-3.5">
                                @if (($tabungan->status_akun ?? '') === 'aktif')
                                    <span class="text-xs inline-block rounded-full px-2 py-0.5 font-medium bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500">Aktif</span>
                                @else
                                    <span class="text-xs inline-block rounded-full px-2 py-0.5 font-medium bg-orange-50 text-orange-700 dark:bg-orange-500/15 dark:text-orange-500">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-sm font-semibold text-brand-600 dark:text-brand-400">
                                Rp {{ number_format($tabungan->tabungan->saldo ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fa-regular fa-folder-open text-4xl mb-2 text-gray-300 dark:text-gray-600"></i>
                                    <p class="text-base font-medium text-gray-700 dark:text-gray-300">Tidak ada data saldo</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $tabungans->links('vendor.pagination.custom') }}
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('saldoCalculator', () => ({
            allBalances: @json($allBalances),
            selectedIds: [],
            
            init() {
                // By default, select all IDs
                this.selectedIds = Object.keys(this.allBalances).map(Number);
            },
            
            get totalNasabahTerpilih() {
                return this.selectedIds.length;
            },
            
            get totalSaldoTerpilih() {
                return this.selectedIds.reduce((sum, id) => {
                    return sum + (this.allBalances[id] || 0);
                }, 0);
            },
            
            get isAllSelected() {
                return this.selectedIds.length === Object.keys(this.allBalances).length && this.selectedIds.length > 0;
            },
            
            toggleAll() {
                if (this.isAllSelected) {
                    this.selectedIds = [];
                } else {
                    this.selectedIds = Object.keys(this.allBalances).map(Number);
                }
            },
            
            formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
            },

            onSubmitExport(e) {
                if (this.selectedIds.length === 0) {
                    e.preventDefault();
                    alert('Silakan pilih minimal 1 nasabah untuk diekspor.');
                }
            }
        }));
    });
</script>
@endsection
