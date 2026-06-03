<!-- Modal Toggle Admin -->
<x-ui.modal @close-modal-toggle-admin.window="open = false" :isOpen="false" :blurBackdrop="false" :showCloseButton="false" class="max-w-[400px] p-6 text-center">
    <div x-data="{ toggleData: {} }" @open-modal-toggle-admin.window="open = true; toggleData = $event.detail">
        <h4 class="text-xl font-bold text-gray-800 dark:text-white mb-2" x-text="toggleData.status_akun === 'aktif' ? 'Nonaktifkan Admin?' : 'Aktifkan Admin?'"></h4>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
            Apakah Anda yakin ingin <span x-text="toggleData.status_akun === 'aktif' ? 'menonaktifkan' : 'mengaktifkan'"></span> akun <span class="font-semibold" x-text="toggleData.nama"></span>?
        </p>
        <div class="flex justify-center gap-3">
            <button @click="open = false" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 text-sm font-medium transition-colors">Batal</button>
            
            <form :action="`/superadmin/admin/${toggleData.id}/toggle`" method="POST" class="m-0 p-0">
                @csrf
                @method('PUT')
                <button type="submit" class="px-5 py-2.5 rounded-lg text-white text-sm font-medium transition-colors"
                        :class="toggleData.status_akun === 'aktif' ? 'bg-orange-500 hover:bg-orange-600' : 'bg-brand-500 hover:bg-brand-600'"
                        x-text="toggleData.status_akun === 'aktif' ? 'Nonaktifkan' : 'Aktifkan'"></button>
            </form>
        </div>
    </div>
</x-ui.modal>
