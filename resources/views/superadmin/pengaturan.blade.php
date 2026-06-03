@extends('layouts.superadmin.app')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-semibold text-black dark:text-white">Pengaturan</h2>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola informasi profil dan keamanan akun Anda.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Profil Section --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-white/[0.05]">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white">Informasi Profil</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Perbarui nama dan username akun Anda.</p>
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

        <form action="{{ route('superadmin.pengaturan.profile') }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="nama" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', auth()->user()->nama) }}"
                    placeholder="Masukkan nama lengkap"
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800
                    @error('nama') border-red-400 focus:border-red-400 focus:ring-red-500/10 dark:border-red-500 @enderror">
                @error('nama')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="username" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username', auth()->user()->username) }}"
                    placeholder="Masukkan username"
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800
                    @error('username') border-red-400 focus:border-red-400 focus:ring-red-500/10 dark:border-red-500 @enderror">
                @error('username')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-1">
                <button type="submit"
                    class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 shadow-theme-xs transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- Password Section --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-white/[0.05]">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white">Ubah Password</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Pastikan akun Anda menggunakan password yang kuat.</p>
        </div>

        @if (session('success_password'))
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
                    <p class="text-[14px] leading-relaxed text-slate-500 dark:text-gray-400">{{ session('success_password') }}</p>
                </div>
                <button @click="show = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-gray-300 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        <form action="{{ route('superadmin.pengaturan.password') }}" method="POST" class="p-6 space-y-5"
            x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Password Saat Ini</label>
                <div class="relative">
                    <input :type="showCurrent ? 'text' : 'password'" id="current_password" name="current_password"
                        placeholder="Masukkan password saat ini"
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800
                        @error('current_password') border-red-400 dark:border-red-500 @enderror">
                    <button type="button" @click="showCurrent = !showCurrent"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i :class="showCurrent ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'" class="text-sm"></i>
                    </button>
                </div>
                @error('current_password')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Password Baru</label>
                <div class="relative">
                    <input :type="showNew ? 'text' : 'password'" id="password" name="password"
                        placeholder="Minimal 8 karakter"
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800
                        @error('password') border-red-400 dark:border-red-500 @enderror">
                    <button type="button" @click="showNew = !showNew"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i :class="showNew ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'" class="text-sm"></i>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Konfirmasi Password Baru</label>
                <div class="relative">
                    <input :type="showConfirm ? 'text' : 'password'" id="password_confirmation" name="password_confirmation"
                        placeholder="Ulangi password baru"
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                    <button type="button" @click="showConfirm = !showConfirm"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i :class="showConfirm ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'" class="text-sm"></i>
                    </button>
                </div>
            </div>

            <div class="pt-1">
                <button type="submit"
                    class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 shadow-theme-xs transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500/30">
                    Perbarui Password
                </button>
            </div>
        </form>
    </div>

</div>

{{-- Info Card --}}
<div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 dark:border-white/[0.05] dark:bg-white/[0.03]">
    <h3 class="text-base font-semibold text-gray-800 dark:text-white mb-4">Informasi Akun</h3>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-50 dark:bg-brand-500/10">
                <i class="fa-solid fa-user text-brand-500 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Nama</p>
                <p class="text-sm font-medium text-gray-800 dark:text-white">{{ auth()->user()->nama }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-50 dark:bg-green-500/10">
                <i class="fa-solid fa-at text-green-500 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Username</p>
                <p class="text-sm font-medium text-gray-800 dark:text-white">{{ auth()->user()->username }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-purple-50 dark:bg-purple-500/10">
                <i class="fa-solid fa-shield-halved text-purple-500 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Role</p>
                <p class="text-sm font-medium text-gray-800 dark:text-white capitalize">{{ auth()->user()->role->nama_role }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
