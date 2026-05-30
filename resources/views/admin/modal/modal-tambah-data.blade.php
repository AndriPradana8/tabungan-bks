<x-ui.modal @open-modal-tambah-nasabah.window="open = true" @close-modal-tambah-nasabah.window="open = false" :isOpen="$errors->any()" class="max-w-[700px] p-6 lg:p-10">
    <!-- Form in Modal -->
    <div class="flex flex-col overflow-y-auto custom-scrollbar">
        <!-- Modal Header -->
        <div class="modal-header border-gray-200 dark:border-gray-800">
            <h5 class="mb-2 font-semibold text-gray-800 modal-title text-theme-xl lg:text-2xl dark:text-white/90">
                Tambah Data Nasabah
            </h5>
        </div>
        
        <!-- Modal Body -->
        <div class="mt-6 modal-body">
            <form action="{{ route('admin.nasabah.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama <span class="text-red-500">*</span>
                        </label>
                        <input name="nama" value="{{ old('nama') }}" type="text" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" placeholder="Masukkan nama nasabah" required>
                        @error('nama')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- NIK -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            NIK <span class="text-red-500">*</span>
                        </label>
                        <input name="nik" value="{{ old('nik') }}" type="text" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" placeholder="Masukkan NIK" required>
                        @error('nik')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- No. Telepon -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            No. Telepon <span class="text-red-500">*</span>
                        </label>
                        <input name="no_hp" value="{{ old('no_hp') }}" type="text" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" placeholder="Masukkan nomor telepon" required>
                        @error('no_hp')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tanggal Lahir <span class="text-red-500">*</span>
                        </label>
                        <input name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" type="date" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" required>
                        @error('tanggal_lahir')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="sm:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Alamat <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alamat" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" rows="3" required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="flex items-center gap-3 mt-6 modal-footer sm:justify-end">
                    <button type="button" @click="open = false" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 sm:w-auto dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                        Batal
                    </button>
                    <button type="submit" class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-ui.modal>
