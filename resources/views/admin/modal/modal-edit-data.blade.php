<x-ui.modal @open-modal-edit-nasabah.window="open = true; editForm = $event.detail; formAction = '{{ url('admin/nasabah') }}/' + editForm.id" @close-modal-edit-nasabah.window="open = false" :isOpen="$errors->has('edit_error')" class="max-w-[700px] p-6 lg:p-10">
    <!-- Form in Modal -->
    <div class="flex flex-col overflow-y-auto custom-scrollbar">
        <!-- Modal Header -->
        <div class="modal-header border-gray-200 dark:border-gray-800">
            <h5 class="mb-2 font-semibold text-gray-800 modal-title text-theme-xl lg:text-2xl dark:text-white/90">
                Edit Data Nasabah
            </h5>
        </div>
        
        <!-- Modal Body -->
        <div class="mt-6 modal-body" x-data="{ editForm: {}, formAction: '' }" @open-modal-edit-nasabah.window="editForm = $event.detail; formAction = '{{ url('admin/nasabah') }}/' + editForm.id">
            <form x-bind:action="formAction" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama <span class="text-red-500">*</span>
                        </label>
                        <input name="nama" x-model="editForm.nama" type="text" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" placeholder="Masukkan nama nasabah" required>
                    </div>

                    <!-- NIK -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            NIK <span class="text-red-500">*</span>
                        </label>
                        <input name="nik" x-model="editForm.nik" type="text" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" placeholder="Masukkan NIK" required>
                    </div>

                    <!-- No. Telepon -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            No. Telepon <span class="text-red-500">*</span>
                        </label>
                        <input name="no_hp" x-model="editForm.no_hp" type="text" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" placeholder="Masukkan nomor telepon" required>
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tanggal Lahir <span class="text-red-500">*</span>
                        </label>
                        <input name="tanggal_lahir" x-model="editForm.tanggal_lahir" type="date" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" required>
                    </div>

                    <!-- Alamat -->
                    <div class="sm:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Alamat <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alamat" x-model="editForm.alamat" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" rows="3" required></textarea>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="flex items-center gap-3 mt-6 modal-footer sm:justify-end">
                    <button type="button" @click="open = false" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 sm:w-auto dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                        Batal
                    </button>
                    <button type="submit" class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-ui.modal>
