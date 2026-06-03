<!-- Modal Edit Admin -->
<x-ui.modal @close-modal-edit-admin.window="open = false" :isOpen="false" class="max-w-[600px] p-6 lg:p-10">
    <div class="flex flex-col overflow-y-auto custom-scrollbar" x-data="{ editData: {} }" @open-modal-edit-admin.window="open = true; editData = $event.detail">
        <!-- Modal Header -->
        <div class="modal-header border-gray-200 dark:border-gray-800 pb-4">
            <h5 class="mb-1 font-semibold text-gray-800 modal-title text-theme-xl lg:text-2xl dark:text-white/90">
                Edit Data Admin
            </h5>
        </div>
        
        <!-- Modal Body -->
        <div class="mt-6 modal-body">
            <form :action="`/superadmin/admin/${editData.id}`" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-y-5 gap-x-6">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama</label>
                        <input type="text" name="nama" x-model="editData.nama" required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                            placeholder="Masukkan nama">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Username</label>
                        <input type="text" name="username" x-model="editData.username" required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                            placeholder="Masukkan username unik">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Password Baru <span class="font-normal text-xs text-gray-400">(Opsional)</span></label>
                        <input type="password" name="password" minlength="6"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                            placeholder="Biarkan kosong jika tidak ingin mengubah password">
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="flex items-center gap-3 mt-8 pt-4 border-gray-100 dark:border-gray-800 modal-footer sm:justify-end">
                    <button type="button" @click="open = false" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 sm:w-auto shadow-theme-xs dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto shadow-theme-xs dark:bg-brand-500 dark:hover:bg-brand-600 transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-ui.modal>
