<!-- TAB CAPAIAN OUTPUT -->
<h2 class="text-xl font-bold text-white mb-2 flex items-center gap-2">
    <span class="bg-teal-500/10 text-teal-400 p-2 rounded-lg">
        <i class="fa-solid fa-chart-line"></i>
    </span>
    Input Capaian Output
</h2>
<p class="text-slate-400 text-sm mb-6">
    Masukkan data Capaian Output secara manual atau import dari Excel.
    <span class="text-teal-500/80 text-xs block mt-1"><i class="fa-solid fa-circle-info me-1"></i> Catatan: Jika kolom 'Tahun' tidak ada di Excel, data akan otomatis dicatat sebagai Tahun <?= date('Y') ?>.</span>
</p>

<!-- FORM INPUT MANUAL -->
<form id="formCapaianOutputEntry" class="bg-slate-900/50 rounded-xl p-6 border border-slate-700/50">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- TAHUN -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Tahun</label>
            <select id="thn_capaian_output" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white font-bold focus:outline-none focus:border-teal-500">
                <?php for($y = date('Y'); $y >= 2020; $y--): ?>
                    <option value="<?= $y ?>"><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <!-- BULAN -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Bulan</label>
            <select id="bulan" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white font-bold focus:outline-none focus:border-teal-500">
                <?php 
                $months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                foreach($months as $m): 
                ?>
                    <option value="<?= $m ?>"><?= $m ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- NO BULAN -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">No. Bulan</label>
            <select id="no_bulan" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white font-bold focus:outline-none focus:border-teal-500">
                <?php for($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <!-- MASTER RO DROPDOWN -->
        <div class="md:col-span-4">
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Pilih Master RO (Rincian Output)</label>
            <select id="select_master_ro_output" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-teal-500">
                <option value="">-- Pilih RO --</option>
            </select>
        </div>

        <!-- RINCIAN OUTPUT (Auto-fill, readonly) -->
        <div class="md:col-span-2">
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Rincian Output</label>
            <input type="text" id="rincian_output" readonly class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-slate-300 cursor-not-allowed focus:outline-none" placeholder="Auto">
        </div>

        <!-- KODE RO (Auto-fill, readonly) -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">RO</label>
            <input type="text" id="kode_ro" readonly class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-slate-300 cursor-not-allowed focus:outline-none" placeholder="Auto">
        </div>

        <!-- NO RO (Auto-fill, readonly) -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">No. RO</label>
            <input type="number" id="no_ro_capaian" readonly class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-slate-300 cursor-not-allowed focus:outline-none" placeholder="Auto">
        </div>

        <!-- TARGET % BULAN -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Target % Bulan</label>
            <input type="number" step="0.01" id="target_persen_bulan" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-teal-500" placeholder="0.00">
        </div>

        <!-- REALISASI -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Realisasi</label>
            <input type="number" step="0.01" id="realisasi_capaian" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-teal-500" placeholder="0.00">
        </div>

        <!-- % REALISASI -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">% Realisasi</label>
            <input type="number" step="0.01" id="persen_realisasi" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-teal-500" placeholder="0.00">
        </div>

        <!-- REALISASI KUMULATIF -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Realisasi Kumulatif</label>
            <input type="number" step="0.01" id="realisasi_kumulatif" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-teal-500" placeholder="0.00">
        </div>

        <!-- % REALISASI KUMULATIF -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">salah % Realisasi Kumulatif</label>
            <input type="number" step="0.01" id="salah_persen_realisasi_kumulatif" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-teal-500" placeholder="0.00">
        </div>

        <!-- CAPAIAN -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Capaian</label>
            <input type="number" step="0.01" id="capaian" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-teal-500" placeholder="0.00">
        </div>

        <!-- KATEGORI -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Kategori</label>
            <select id="kategori" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white font-bold focus:outline-none focus:border-teal-500">
                <option value="">-- Pilih Kategori --</option>
                <option value="Tercapai">Tercapai</option>
                <option value="Tidak Tercapai">Tidak Tercapai</option>
            </select>
        </div>

        <!-- TARGET TAHUN -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Target Tahun</label>
            <input type="number" step="0.01" id="target_tahun" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-teal-500" placeholder="0.00">
        </div>

        <!-- KATEGORI BELANJA -->
        <div class="md:col-span-2">
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Kategori Belanja</label>
            <select id="kategori_belanja" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white font-bold focus:outline-none focus:border-teal-500">
                <option value="">-- Pilih Kategori Belanja --</option>
                <option value="51 (Belanja Pegawai)">51 (Belanja Pegawai)</option>
                <option value="52 (Belanja Barang)">52 (Belanja Barang)</option>
                <option value="53 (Belanja Modal)">53 (Belanja Modal)</option>
            </select>
        </div>

        <!-- REALISASI KUMULATIF % -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Realisasi Kumulatif %</label>
            <input type="number" step="0.01" id="realisasi_kumulatif_persen" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-teal-500" placeholder="0.00">
        </div>
    </div>
    
    <div class="mt-4 flex flex-col md:flex-row items-center justify-end gap-3">
        <!-- Tombol Reset Table -->
        <button type="button" id="btnClearCapaianOutput" class="hidden bg-red-600 hover:bg-red-500 text-white font-bold py-3 px-6 rounded-xl transition-all flex items-center gap-2">
            <i class="fa-solid fa-trash-can"></i> Hapus Semua
        </button>
        
        <!-- Import Button -->
        <button type="button" id="btnImportCapaianOutput" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-6 rounded-xl transition-all flex items-center gap-2">
            <i class="fa-solid fa-file-excel"></i> Import Excel
        </button>

        <!-- Add Button -->
        <button type="button" id="btnAddCapaianOutput" class="bg-teal-600 hover:bg-teal-500 text-white font-bold py-3 px-6 rounded-xl transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah ke Antrian
        </button>
    </div>

    <!-- Hidden File Input & Edit Index -->
    <input type="file" id="fileImportCapaianOutput" accept=".xlsx,.xls,.csv" class="hidden">
    <input type="hidden" id="capaianOutputEditIndex" value="-1">
</form>


<!-- STAGING TABLE -->
<div class="mt-8">
    <div class="flex items-center justify-between mb-4 px-2">
        <h3 class="text-sm font-bold text-white uppercase tracking-widest">
            <i class="fa-solid fa-chart-line text-teal-400 me-2"></i>Antrian Data Capaian Output
        </h3>
        <span id="counterCapaianOutput" class="text-[10px] bg-slate-700 text-slate-300 px-3 py-1 rounded-full uppercase font-bold">0 Baris</span>
    </div>

    <div class="overflow-x-auto border border-slate-700 rounded-2xl">
        <table class="w-full text-left text-xs" id="tableCapaianOutputStaging">
            <thead class="bg-slate-800 text-slate-400 border-b border-slate-700 uppercase font-semibold">
                <tr>
                    <th class="px-4 py-3 whitespace-nowrap">Tahun</th>
                    <th class="px-4 py-3 whitespace-nowrap">Bulan</th>
                    <th class="px-4 py-3 whitespace-nowrap">No. Bulan</th>
                    <th class="px-4 py-3 whitespace-nowrap">Rincian Output</th>
                    <th class="px-4 py-3 whitespace-nowrap">RO</th>
                    <th class="px-4 py-3 whitespace-nowrap">No. RO</th>
                    <th class="px-4 py-3 text-right">Target % Bulan</th>
                    <th class="px-4 py-3 text-right">Realisasi</th>
                    <th class="px-4 py-3 text-right">% Realisasi</th>
                    <th class="px-4 py-3 text-right">Realisasi Kumulatif</th>
                    <th class="px-4 py-3 text-right">salah % Realisasi Kumulatif</th>
                    <th class="px-4 py-3 text-right">Capaian</th>
                    <th class="px-4 py-3 text-center">Kategori</th>
                    <th class="px-4 py-3 text-right">Target Tahun</th>
                    <th class="px-4 py-3 text-center">Kategori Belanja</th>
                    <th class="px-4 py-3 text-right">Realisasi Kumulatif %</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700 text-white">
                <tr id="emptyCapaianOutputRow">
                    <td colspan="16" class="px-6 py-8 text-center text-slate-500 italic">Antrian masih kosong.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- SAVE BUTTON -->
    <div class="mt-8 flex justify-center">
        <button type="button" id="btnSaveCapaianOutput" disabled class="hidden bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-bold py-4 px-12 rounded-2xl shadow-xl opacity-50 cursor-not-allowed">
            <i class="fa-solid fa-cloud-arrow-up me-2"></i> Simpan Permanen
        </button>
    </div>
</div>

<?= $this->section('scripts') ?>
    <script src="<?= base_url('assets/js/admin/entry_capaian_output.js') ?>"></script>
<?= $this->endSection() ?>
