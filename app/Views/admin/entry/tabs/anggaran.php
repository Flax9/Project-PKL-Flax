<!-- TAB ANGGARAN -->
<h2 class="text-xl font-bold text-white mb-2 flex items-center gap-2">
    <span class="bg-teal-500/10 text-teal-400 p-2 rounded-lg">
        <i class="fa-solid fa-coins"></i>
    </span>
    Input Data Anggaran
</h2>
<p class="text-slate-400 text-sm mb-6">
    Masukkan data Anggaran secara manual atau import dari Excel.
    <span class="text-teal-500/80 text-xs block mt-1"><i class="fa-solid fa-circle-info me-1"></i> Catatan: Jika kolom 'Tahun' tidak ada di Excel, data akan otomatis dicatat sebagai Tahun <?= date('Y') ?>.</span>
</p>

<!-- FORM INPUT MANUAL -->
<form id="formAnggaranEntry" class="bg-slate-900/50 rounded-xl p-6 border border-slate-700/50">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- TAHUN -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Tahun</label>
            <select id="thn_anggaran" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white font-bold focus:outline-none focus:border-teal-500">
                <?php for($y = date('Y'); $y >= 2020; $y--): ?>
                    <option value="<?= $y ?>"><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <!-- BULAN -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Bulan</label>
            <select id="bln_anggaran" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white font-bold focus:outline-none focus:border-teal-500">
                <?php 
                $months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                foreach($months as $m): 
                ?>
                    <option value="<?= $m ?>"><?= $m ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- MASTER RO DROPDOWN -->
        <div class="md:col-span-4">
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Pilih Master RO (Program/Kegiatan)</label>
            <select id="select_master_ro" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-teal-500">
                <option value="">-- Pilih RO --</option>
            </select>
        </div>

        <!-- NO RO -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">No. RO</label>
            <input type="number" id="no_ro" readonly class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-slate-300 cursor-not-allowed focus:outline-none" placeholder="Auto">
        </div>

        <!-- RO -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">RO</label>
            <input type="text" id="ro_text" readonly class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-slate-300 cursor-not-allowed focus:outline-none" placeholder="Auto">
        </div>

        <!-- PROGRAM -->
        <div class="md:col-span-2">
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Program / Kegiatan</label>
            <input type="text" id="program" readonly class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-slate-300 cursor-not-allowed focus:outline-none" placeholder="Auto">
        </div>

        <!-- PAGU -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Pagu</label>
            <input type="number" step="0.01" id="pagu" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-teal-500" placeholder="0.00">
        </div>

        <!-- REALISASI -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Realisasi</label>
            <input type="number" step="0.01" id="realisasi" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-teal-500" placeholder="0.00">
        </div>
        
        <!-- TARGET TW -->
         <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">% Target TW</label>
            <input type="number" step="0.01" id="target_tw" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-teal-500" placeholder="0.00">
        </div>

        <!-- CAPAIAN REALISASI -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Capaian Realisasi</label>
            <input type="number" step="0.01" id="capaian_realisasi" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-teal-500" placeholder="0.00">
        </div>
        
        <!-- CAPAIAN THD TARGET -->
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Cap. Thd Target TW</label>
            <input type="number" step="0.01" id="capaian_target_tw" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-teal-500" placeholder="0.00">
        </div>

         <!-- KATEGORI TW -->
         <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Kategori TW</label>
            <select id="kategori_tw" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white font-bold focus:outline-none focus:border-teal-500">
                <option value="">-- Pilih TW --</option>
                <option value="TW 1">TW 1</option>
                <option value="TW 2">TW 2</option>
                <option value="TW 3">TW 3</option>
                <option value="TW 4">TW 4</option>
            </select>
        </div>
    </div>
    
    <div class="mt-4 flex flex-col md:flex-row items-center justify-end gap-3">
        <!-- Tombol Reset Table -->
        <button type="button" id="btnClearAnggaran" class="hidden bg-red-600 hover:bg-red-500 text-white font-bold py-3 px-6 rounded-xl transition-all flex items-center gap-2">
            <i class="fa-solid fa-trash-can"></i> Hapus Semua
        </button>
        
        <!-- Import Button -->
        <button type="button" id="btnImportAnggaran" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-6 rounded-xl transition-all flex items-center gap-2">
            <i class="fa-solid fa-file-excel"></i> Import Excel
        </button>

        <!-- Add Button -->
        <button type="button" id="btnAddAnggaran" class="bg-teal-600 hover:bg-teal-500 text-white font-bold py-3 px-6 rounded-xl transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah ke Antrian
        </button>
    </div>

    <!-- Hidden File Input & Edit Index -->
    <input type="file" id="fileImportAnggaran" accept=".xlsx,.xls,.csv" class="hidden">
    <input type="hidden" id="anggaranEditIndex" value="-1">
</form>


<!-- STAGING TABLE -->
<div class="mt-8">
    <div class="flex items-center justify-between mb-4 px-2">
        <h3 class="text-sm font-bold text-white uppercase tracking-widest">
            <i class="fa-solid fa-coins text-teal-400 me-2"></i>Antrian Data Anggaran
        </h3>
        <span id="counterAnggaran" class="text-[10px] bg-slate-700 text-slate-300 px-3 py-1 rounded-full uppercase font-bold">0 Baris</span>
    </div>

    <div class="overflow-x-auto border border-slate-700 rounded-2xl">
        <table class="w-full text-left text-xs" id="tableAnggaranStaging">
            <thead class="bg-slate-800 text-slate-400 border-b border-slate-700 uppercase font-semibold">
                <tr>
                    <th class="px-4 py-3 whitespace-nowrap">Tahun</th>
                    <th class="px-4 py-3 whitespace-nowrap">Bulan</th>
                    <th class="px-4 py-3 whitespace-nowrap">No. RO</th>
                    <th class="px-4 py-3 whitespace-nowrap">RO</th>
                    <th class="px-4 py-3 whitespace-nowrap">Program</th>
                    <th class="px-4 py-3 text-right">Pagu</th>
                    <th class="px-4 py-3 text-right">Realisasi</th>
                    <th class="px-4 py-3 text-right">Cap. Realisasi</th>
                    <th class="px-4 py-3 text-right">% Target TW</th>
                    <th class="px-4 py-3 text-right">Cap. Thd Target</th>
                    <th class="px-4 py-3 text-center">Kategori</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700 text-white">
                <tr id="emptyAnggaranRow">
                    <td colspan="12" class="px-6 py-8 text-center text-slate-500 italic">Antrian masih kosong.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- SAVE BUTTON -->
    <div class="mt-8 flex justify-center">
        <button type="button" id="btnSaveAnggaran" disabled class="hidden bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-bold py-4 px-12 rounded-2xl shadow-xl opacity-50 cursor-not-allowed">
            <i class="fa-solid fa-cloud-arrow-up me-2"></i> Simpan Permanen
        </button>
    </div>
</div>

<script src="<?= base_url('assets/js/admin/entry_anggaran.js') ?>"></script>
