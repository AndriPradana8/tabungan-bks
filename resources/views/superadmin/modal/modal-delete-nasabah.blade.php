<!-- Modal Delete Nasabah -->
<x-ui.modal @close-modal-delete-nasabah.window="open = false" :isOpen="false" :blurBackdrop="false" :showCloseButton="false" class="max-w-[400px] p-6 text-center">
    <div x-data="{ deleteData: {} }" @open-modal-delete-nasabah.window="open = true; deleteData = $event.detail">
        <h4 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Hapus Nasabah?</h4>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
            Apakah Anda yakin ingin menghapus akun <span class="font-semibold" x-text="deleteData.nama"></span>? Tindakan ini tidak dapat dibatalkan.
        </p>
        <div class="flex justify-center gap-3">
            <button @click="open = false" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 text-sm font-medium transition-colors">Batal</button>
            
            <form :action="`/superadmin/nasabah/${deleteData.id}`" method="POST" class="m-0 p-0">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-red-500 hover:bg-red-600 text-white text-sm font-medium transition-colors">Hapus</button>
            </form>
        </div>
    </div>
</x-ui.modal>
