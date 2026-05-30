<div x-data="{
        nasabah: {},
        nominal: '',
        formattedNominal: '',
        formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        },
        updateNominal(value) {
            let raw = value.toString().replace(/[^0-9]/g, '');
            if (raw === '') {
                this.nominal = '';
                this.formattedNominal = '';
                return;
            }
            this.nominal = parseInt(raw, 10);
            this.formattedNominal = 'Rp ' + new Intl.NumberFormat('id-ID').format(this.nominal);
        }
    }" 
    @open-modal-setor.window="nasabah = $event.detail; nominal = ''; formattedNominal = '';">
    
    <x-ui.modal @open-modal-setor.window="open = true" @close-modal-setor.window="open = false" class="max-w-[500px] p-6 lg:p-8">
        <div class="flex flex-col overflow-y-auto custom-scrollbar">
            <div class="modal-header border-gray-200 dark:border-gray-800 mb-6 border-b pb-4">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">
                    Setor Tunai
                </h3>
            </div>

            <form action="{{ route('admin.tabungan.setor') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" :value="nasabah.id">
                <input type="hidden" name="nominal" :value="nominal">

                <div class="mb-5 space-y-4">
                    <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                        <h4 class="mb-3 text-md font-semibold text-gray-500 dark:text-gray-400">Informasi Nasabah</h4>
                        
                        <div class="grid grid-cols-3 gap-y-2 text-sm">
                            <div class="text-gray-500 dark:text-gray-400">Nama</div>
                            <div class="col-span-2 font-medium text-gray-900 dark:text-white" x-text="nasabah.nama"></div>
                            
                            <div class="text-gray-500 dark:text-gray-400">NIK</div>
                            <div class="col-span-2 font-medium text-gray-900 dark:text-white" x-text="nasabah.nik"></div>
                            
                            <div class="text-gray-500 dark:text-gray-400">Alamat</div>
                            <div class="col-span-2 font-medium text-gray-900 dark:text-white line-clamp-2" x-text="nasabah.alamat"></div>
                            
                            <div class="text-gray-500 dark:text-gray-400 mt-2 pt-2 border-t border-gray-200 dark:border-gray-700">Saldo Saat Ini</div>
                            <div class="col-span-2 font-bold text-brand-500 mt-2 pt-2 border-t border-gray-200 dark:border-gray-700" x-text="formatRupiah(nasabah.saldo)"></div>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nominal Setor (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" :value="formattedNominal" @input="updateNominal($event.target.value)" required
                            placeholder="Masukkan Nominal"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                        <p x-show="nominal > 0 && nominal < 1000" class="mt-2 text-sm text-red-500">
                            * Minimal setor Rp 1.000
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-8">
                    <button type="button" @click="open = false"
                        class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Batal
                    </button>
                    <button type="submit"
                        :disabled="!nominal || nominal < 1000"
                        :class="!nominal || nominal < 1000 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-green-600'"
                        class="rounded-lg bg-green-500 px-5 py-2.5 text-sm font-medium text-white transition-colors">
                        Proses Setor
                    </button>
                </div>
            </form>
        </div>
    </x-ui.modal>
</div>
