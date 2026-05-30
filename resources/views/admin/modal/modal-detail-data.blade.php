<x-ui.modal @close-modal-detail-nasabah.window="open = false" :isOpen="false" class="max-w-[600px] p-6 lg:p-10">
    <div class="flex flex-col overflow-y-auto custom-scrollbar" x-data="{ detailData: {} }" @open-modal-detail-nasabah.window="open = true; detailData = $event.detail">
        <!-- Modal Header -->
        <div class="modal-header border-gray-200 dark:border-gray-800 pb-4 border-b">
            <h5 class="mb-1 font-semibold text-gray-800 modal-title text-theme-xl lg:text-2xl dark:text-white/90">
                Detail Nasabah
            </h5>
        </div>
        
        <!-- Modal Body -->
        <div class="mt-6 modal-body">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-6">
                <!-- Nama Lengkap -->
                <div>
                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nama Lengkap</span>
                    <p class="mt-1.5 text-base font-semibold text-gray-800 dark:text-white/90" x-text="detailData.nama"></p>
                </div>

                <!-- NIK -->
                <div>
                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">NIK</span>
                    <p class="mt-1.5 text-base font-semibold text-gray-800 dark:text-white/90" x-text="detailData.nik"></p>
                </div>

                <!-- No. Telepon -->
                <div>
                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">No. Telepon</span>
                    <p class="mt-1.5 text-base font-semibold text-gray-800 dark:text-white/90" x-text="detailData.no_hp"></p>
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Lahir</span>
                    <p class="mt-1.5 text-base font-semibold text-gray-800 dark:text-white/90" x-text="detailData.tanggal_lahir"></p>
                </div>

                <!-- Status -->
                <div>
                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status Akun</span>
                    <div class="mt-1.5">
                        <span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium"
                              :class="detailData.status_akun === 'aktif' ? 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500' : 'bg-orange-50 text-orange-700 dark:bg-orange-500/15 dark:text-orange-500'"
                              x-text="detailData.status_akun === 'aktif' ? 'Aktif' : 'Tidak Aktif'">
                        </span>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="sm:col-span-2">
                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Alamat Lengkap</span>
                    <p class="mt-1.5 text-base font-medium text-gray-800 dark:text-white/90" x-text="detailData.alamat"></p>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="flex items-center gap-3 mt-8 pt-4 border-gray-100 dark:border-gray-800 modal-footer sm:justify-end">
                <button type="button" @click="open = false" class="flex w-full justify-center rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-200 sm:w-auto shadow-theme-xs dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</x-ui.modal>
