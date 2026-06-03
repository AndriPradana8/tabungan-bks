@extends('layouts.superadmin.app')

@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-semibold text-black dark:text-white">
        Laporan Transaksi
        <span class="ml-2 text-base font-normal text-gray-500 capitalize">({{ $periode }})</span>
    </h2>
</div>

{{-- Filter --}}
<div class="rounded-2xl border border-gray-200 bg-white p-5 mb-6 dark:border-white/[0.05] dark:bg-white/[0.03]">
    <form method="GET" action="{{ route('superadmin.laporan.transaksi') }}" class="flex flex-wrap items-end gap-4">
        <input type="hidden" name="periode" value="{{ $periode }}">

        @if ($periode === 'harian')
            <div x-data="{
                tanggal: '{{ $tanggal }}',
                submitForm() {
                    this.$el.closest('form').submit();
                }
            }" class="flex items-center gap-3">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden sm:block">Filter</label>
                <div class="flex items-center gap-2">
                    <div class="w-[200px]">
                        <input type="hidden" name="tanggal" x-model="tanggal">
                        <x-form.date-picker 
                            id="filter-tanggal" 
                            placeholder="Pilih Tanggal"
                            :defaultDate="$tanggal"
                            :isStatic="false"
                            altFormat="d-m-Y"
                            @date-change="tanggal = $event.detail.dateStr; submitForm()" 
                        />
                    </div>
                    @if (request()->has('tanggal'))
                        <a href="{{ route('superadmin.laporan.transaksi', ['periode' => 'harian']) }}" class="text-sm font-medium text-red-500 hover:text-red-700 dark:hover:text-red-400 transition-colors">Reset</a>
                    @endif
                </div>
            </div>
        @elseif ($periode === 'bulanan')
            <div class="flex items-center gap-3">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden sm:block">Filter</label>
                <div class="flex items-center gap-2">
                    <select name="bulan" onchange="this.form.submit()" class="h-[42px] rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                    <select name="tahun" onchange="this.form.submit()" class="h-[42px] rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        @for ($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    @if (request()->has('bulan') || request()->has('tahun'))
                        <a href="{{ route('superadmin.laporan.transaksi', ['periode' => 'bulanan']) }}" class="text-sm font-medium text-red-500 hover:text-red-700 dark:hover:text-red-400 transition-colors">Reset</a>
                    @endif
                </div>
            </div>
        @elseif ($periode === 'tahunan')
            <div class="flex items-center gap-3">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden sm:block">Filter</label>
                <div class="flex items-center gap-2">
                    <select name="tahun" onchange="this.form.submit()" class="h-[42px] rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        @for ($y = now()->year; $y >= now()->year - 10; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    @if (request()->has('tahun'))
                        <a href="{{ route('superadmin.laporan.transaksi', ['periode' => 'tahunan']) }}" class="text-sm font-medium text-red-500 hover:text-red-700 dark:hover:text-red-400 transition-colors">Reset</a>
                    @endif
                </div>
            </div>
        @endif

        {{-- Action Buttons --}}
        <div class="flex items-center gap-3 ml-auto">
            <a href="{{ route('superadmin.laporan.export-pdf', array_merge(['type' => 'transaksi', 'periode' => $periode], $periode === 'harian' ? ['tanggal' => $tanggal] : ($periode === 'bulanan' ? ['bulan' => $bulan, 'tahun' => $tahun] : ['tahun' => $tahun]))) }}"
                class="h-[42px] inline-flex items-center gap-2 px-5 rounded-lg bg-red-500 text-sm font-medium text-white hover:bg-red-600 transition-colors">
                <i class="fa-solid fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </form>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-white/[0.05] dark:bg-white/[0.03]">
        <p class="text-sm text-gray-500 dark:text-gray-400">Total Transaksi</p>
        <p class="mt-1 text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($totalCount) }}</p>
    </div>
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-500/20 dark:bg-emerald-500/10">
        <p class="text-sm text-emerald-600 dark:text-emerald-400">Total Setor</p>
        <p class="mt-1 text-2xl font-bold text-emerald-700 dark:text-emerald-300">Rp {{ number_format($totalSetor, 0, ',', '.') }}</p>
    </div>
    <div class="rounded-2xl border border-red-200 bg-red-50 p-5 dark:border-red-500/20 dark:bg-red-500/10">
        <p class="text-sm text-red-600 dark:text-red-400">Total Tarik</p>
        <p class="mt-1 text-2xl font-bold text-red-700 dark:text-red-300">Rp {{ number_format($totalTarik, 0, ',', '.') }}</p>
    </div>
</div>

{{-- Table --}}
<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
    <div class="max-w-full overflow-x-auto">
        <table class="w-full">
            <thead class="border-y border-gray-100 bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400">No</th>
                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400">Tanggal</th>
                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400">Nama Nasabah</th>
                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400">Admin</th>
                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400">Jenis</th>
                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400">Nominal</th>
                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 dark:text-gray-400">Saldo Sesudah</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaksis as $i => $t)
                    <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                        <td class="px-6 py-3.5 text-sm text-gray-700 dark:text-gray-400">{{ $transaksis->firstItem() + $i }}</td>
                        <td class="px-6 py-3.5 text-sm text-gray-700 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($t->tanggal_transaksi)->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-3.5 text-sm font-medium text-gray-700 dark:text-gray-400">
                            {{ $t->tabungan->user->nama ?? '-' }}
                        </td>
                        <td class="px-6 py-3.5 text-sm text-gray-700 dark:text-gray-400">
                            {{ $t->admin->nama ?? '-' }}
                        </td>
                        <td class="px-6 py-3.5">
                            @if ($t->jenis_transaksi === 'setor')
                                <span class="text-xs inline-block rounded-full px-2 py-0.5 font-medium bg-emerald-50 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400">Setor</span>
                            @else
                                <span class="text-xs inline-block rounded-full px-2 py-0.5 font-medium bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-400">Tarik</span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5 text-sm font-semibold {{ $t->jenis_transaksi === 'setor' ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $t->jenis_transaksi === 'setor' ? '+' : '-' }} Rp {{ number_format($t->nominal, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3.5 text-sm text-gray-700 dark:text-gray-400">
                            Rp {{ number_format($t->saldo_sesudah, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <i class="fa-regular fa-folder-open text-4xl mb-2 text-gray-300 dark:text-gray-600"></i>
                                <p class="text-base font-medium text-gray-700 dark:text-gray-300">Tidak ada data transaksi</p>
                                <p class="text-sm">Tidak ada transaksi pada periode yang dipilih.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $transaksis->links('vendor.pagination.custom') }}
</div>
@endsection
