<!-- TAB NKO -->
<h2 class="text-xl font-bold text-white mb-2 flex items-center gap-2">
    <span class="bg-teal-500/10 text-teal-400 p-2 rounded-lg">
        <i class="fa-solid fa-chart-pie"></i>
    </span>
    Input Nilai Kinerja Organisasi (NKO)
</h2>
<p class="text-slate-400 text-sm mb-6">
    Masukkan data NKO secara manual atau import dari Excel. 
    <span class="text-teal-500/80 text-xs block mt-1"><i class="fa-solid fa-circle-info me-1"></i> Catatan: Jika kolom 'Tahun' tidak ada di Excel, data akan otomatis dicatat sebagai Tahun <?= date('Y') ?>.</span>
</p>

<!-- FORM INPUT MANUAL -->
<form id="formNkoEntry" class="bg-slate-900/50 rounded-xl p-6 border border-slate-700/50">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <!-- TAHUN -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Tahun</label>
            <select name="tahun_nko" id="tahun_nko" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white font-bold focus:outline-none focus:border-teal-500">
                <?php for($y = date('Y'); $y >= 2020; $y--): ?>
                    <option value="<?= $y ?>"><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <!-- BULAN -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Bulan</label>
            <select name="bulan_nko" id="bulan_nko" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white font-bold focus:outline-none focus:border-teal-500">
                <?php 
                $months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                foreach($months as $m): 
                ?>
                    <option value="<?= $m ?>"><?= $m ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- TOTAL CAPAIAN -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Total Capaian</label>
            <input type="number" step="0.01" name="total_capaian" id="total_capaian" 
                class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white font-bold focus:outline-none focus:border-teal-500" placeholder="0.00">
        </div>

        <!-- TOTAL IKU -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Total IKU</label>
            <input type="number" name="total_iku" id="total_iku" 
                class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white font-bold focus:outline-none focus:border-teal-500" placeholder="0">
        </div>

        <!-- NILAI NKO -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Nilai NKO</label>
            <input type="number" step="0.01" name="nilai_nko" id="nilai_nko" 
                class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white font-bold focus:outline-none focus:border-teal-500" placeholder="0.00">
        </div>
    </div>
    
    <div class="mt-4 flex flex-col md:flex-row items-center justify-end gap-3">
        <!-- Tombol Reset Table -->
        <button type="button" id="btnClearNkoQueue" class="hidden bg-red-600 hover:bg-red-500 text-white font-bold py-3 px-6 rounded-xl transition-all flex items-center gap-2">
            <i class="fa-solid fa-trash-can"></i> Hapus Semua
        </button>
        
        <!-- Import Button -->
        <button type="button" id="btnImportNko" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-6 rounded-xl transition-all flex items-center gap-2">
            <i class="fa-solid fa-file-excel"></i> Import Excel
        </button>

        <!-- Add Button -->
        <button type="button" id="btnAddNkoQueue" class="bg-teal-600 hover:bg-teal-500 text-white font-bold py-3 px-6 rounded-xl transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah ke Antrian
        </button>
    </div>

    <!-- Hidden File Input & Edit Index -->
    <input type="file" id="fileImportNko" accept=".xlsx,.xls,.csv" class="hidden">
    <input type="hidden" id="nkoEditIndex" value="-1">
</form>

<!-- STAGING TABLE -->
<div class="mt-8">
    <div class="flex items-center justify-between mb-4 px-2">
        <h3 class="text-sm font-bold text-white uppercase tracking-widest">
            <i class="fa-solid fa-list-ol text-teal-400 me-2"></i>Antrian Data NKO
        </h3>
        <span id="counterNkoQueue" class="text-[10px] bg-slate-700 text-slate-300 px-3 py-1 rounded-full uppercase font-bold">0 Baris</span>
    </div>

    <!-- CODE OMITTED FOR BREVITY -->
    
    <div class="overflow-x-auto border border-slate-700 rounded-2xl">
        <table class="w-full text-left text-xs" id="tableNkoStaging">
            <thead class="bg-slate-800 text-slate-400 border-b border-slate-700 uppercase font-semibold">
                <tr>
                    <th class="px-6 py-4">Tahun</th>
                    <th class="px-6 py-4">Bulan</th>
                    <th class="px-6 py-4 text-center">Total Capaian</th>
                    <th class="px-6 py-4 text-center">Total IKU</th>
                    <th class="px-6 py-4 text-center">Nilai NKO</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700 text-white">
                <tr id="emptyNkoRow">
                    <td colspan="6" class="px-6 py-8 text-center text-slate-500 italic">Antrian masih kosong.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- SAVE BUTTON -->
    <div class="mt-8 flex justify-center">
        <button type="button" id="btnSaveNko" disabled class="hidden bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-bold py-4 px-12 rounded-2xl shadow-xl opacity-50 cursor-not-allowed">
            <i class="fa-solid fa-cloud-arrow-up me-2"></i> Simpan Permanen
        </button>
    </div>
</div>

<script src="<?= base_url('assets/js/admin/entry_nko.js') ?>"></script>
