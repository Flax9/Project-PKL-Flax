<?php
/**
 * IKU Entry Tab
 * JavaScript externalized to: public/assets/js/admin/entry_iku.js
 */
?>


<!-- TITLE HEADING -->
<h2 class="text-xl font-bold text-white mb-2 flex items-center gap-2">
    <span class="bg-teal-500/10 text-teal-400 p-2 rounded-lg">
        <i class="fa-solid fa-bullseye"></i>
    </span>
    Input Capaian Indikator Kinerja Utama (IKU)
</h2>
<p class="text-slate-400 text-sm mb-6">
    Masukkan data IKU secara manual atau import dari Excel.
    <span class="text-teal-500/80 text-xs block mt-1"><i class="fa-solid fa-circle-info me-1"></i> Catatan: Pastikan format kolom Excel sesuai dengan template.</span>
</p>


<div class="mb-6 p-4 bg-amber-500/10 border border-amber-500/50 rounded-2xl flex items-start gap-4">
    <div class="text-amber-500 mt-1">
        <i class="fa-solid fa-triangle-exclamation"></i>
    </div>
    <div>
        <h4 class="text-sm font-bold text-amber-500 uppercase tracking-wider">Peringatan Keamanan</h4>
        <p class="text-xs text-slate-400 leading-relaxed mt-1">
            Data antrian bersifat sementara. Jika halaman di-refresh sebelum disimpan, data akan hilang.
        </p>
    </div>
</div>

<form id="formIku" class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
        <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Tahun Periode</label>
        <select name="tahun" id="tahun" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-teal-500">
            <option value="" disabled selected>-- Pilih Tahun --</option>
            
            <option value="2025">2025</option>
            <option value="2026">2026</option>
        </select>
    </div>

        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Bulan Pelaporan</label>
            <select name="bulan" id="bulan" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">-- Pilih Bulan --</option>
                <?php 
                $bulanArr = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                foreach($bulanArr as $b): ?>
                    <option value="<?= $b ?>"><?= $b ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Fungsi / Substansi</label>
            <select name="fungsi" id="fungsi" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">-- Pilih Fungsi --</option>
                <?php foreach($list_fungsi as $f): ?>
                    <option value="<?= $f->Fungsi ?>"><?= $f->Fungsi ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="md:col-span-1">
            <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">No. IKU / Indikator</label>
            <select name="no_iku" id="no_iku" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">-- Pilih NO. IKU --</option>
                <?php foreach($list_iku as $row): ?>
                    <option value="<?= $row['no_iku']; ?>"><?= $row['no_iku']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="md:col-span-3">
            <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">
                Nama Indikator
            </label>

            <select name="no_indikator" id="no_indikator"
                class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-teal-500"
                required>

                <option value="">-- Pilih Indikator --</option>

                <?php foreach ($list_nama_indikator as $indi): ?>
                    <option value="<?= $indi['no_indikator']; ?>">
                        <?= $indi['nama_indikator']; ?>
                    </option>
                <?php endforeach; ?>

            </select>
        </div>

    </div>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Target</label>
            <input 
            step="0.01"
            type="number"  
            name="target" 
            max="999"
            oninput="if(this.value.length > 3) this.value = this.value.slice(0,3);"
            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-teal-500">

        </div>
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Realisasi</label>
            <input  
            step="0.01" 
            type="number"  
            name="realisasi" 
            max="999"
            oninput="if(this.value.length > 3) this.value = this.value.slice(0,3);"
            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-teal-500">

        </div>
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Performa % Capaian Bulan</label>
            <input 
            step="0.01"
            type="number" 
            name="perf_bulan"
            max="999" 
            oninput="if(this.value.length > 3) this.value = this.value.slice(0,3);"
            id="perf_bulan" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-teal-500">

        </div>
        <div class="col-span-2">
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Kategori Capaian Bulan</label>
            <select name="kat_bulan" id="kat_bulan" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-teal-500">
                <option value="Sangat Baik">Sangat Baik</option>
                <option value="Baik">Baik</option>
                <option value="Cukup">Cukup</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Performa % Capaian Tahun</label>
            <input
            step="0.01" type="number" 
            name="perf_tahun" 
            max="999" 
            oninput="if(this.value.length > 3) this.value = this.value.slice(0,3);"
            id="perf_tahun" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-teal-500">

        </div>
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Kategori Capaian Thn</label>
            <select name="kat_tahun" id="kat_tahun" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-teal-500">
                <option value="Sangat Baik">Sangat Baik</option>
                <option value="Baik">Baik</option>
                <option value="Cukup">Cukup</option>
            </select>
        </div>
    </div>
    
    <div class="flex flex-col md:flex-row items-end gap-6 pt-4 border-t border-slate-700/50 mt-4">
        <div class="w-full md:w-1/4">
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Normalisasi %</label>
            <input  
            step="0.01"
            type="number"  
            max="999" 
            oninput="if(this.value.length > 3) this.value = this.value.slice(0,3);"
            name="capaian_normalisasi_persen" 
            id="capaian_normalisasi_persen" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white font-bold focus:outline-none focus:border-teal-500">
        </div>
        <div class="w-full md:w-1/4">
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Norm Angka</label>
            <input 
            type="number" 
            step="0.01"
            max="999" 
            oninput="if(this.value.length > 3) this.value = this.value.slice(0,3);"
            name="capaian_normalisasi_angka" 
            id="capaian_normalisasi_angka" 
            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white font-bold focus:outline-none focus:border-teal-500">
        </div>
        <div class="w-full md:w-2/3 text-right flex gap-3 justify-end">
            <!-- Tombol Reset Table -->
            <button type="button" id="btnClearQueue" class="hidden bg-red-600 hover:bg-red-500 text-white font-bold py-3 px-8 rounded-xl transition-all flex items-center gap-2">
                <i class="fa-solid fa-trash-can"></i> Hapus Semua
            </button>
            <button type="button" id="btnImportExcel" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-8 rounded-xl transition-all flex items-center gap-2">
                <i class="fa-solid fa-file-excel"></i> Import Excel
            </button>
            <button type="button" id="btnAddQueue" class="bg-teal-600 hover:bg-teal-500 text-white font-bold py-3 px-8 rounded-xl transition-all flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Kumpulkan Data
            </button>
        </div>
    </div>
    
    <!-- Hidden File Input -->
    <input type="file" id="fileImportInput" accept=".xlsx,.xls,.csv" class="hidden">
</form>

<div class="mt-12">
    <div class="flex items-center justify-between mb-4 px-2">
        <h3 class="text-sm font-bold text-white uppercase tracking-widest">
            <i class="fa-solid fa-layer-group text-teal-400 me-2"></i>Antrian Data Sementara
        </h3>
        <span id="counterQueue" class="text-[10px] bg-slate-700 text-slate-300 px-3 py-1 rounded-full uppercase font-bold">0 Baris</span>
    </div>

    <div class="overflow-x-auto border border-slate-700 rounded-2xl">
        <table class="w-full text-left text-xs" id="tableIkuStaging">
            <thead class="bg-slate-800 text-slate-400 border-b border-slate-700 uppercase font-semibold">
                <tr>
                    <th class="px-4 py-3">Fungsi</th>
                    <th class="px-4 py-3">No. IKU</th>
                    <th class="px-4 py-3">Indikator</th>
                    <th class="px-4 py-3">Tahun</th>
                    <th class="px-4 py-3">Bulan</th>
                    <th class="px-4 py-3 text-right">Target</th>
                    <th class="px-4 py-3 text-right">Realisasi</th>
                    <th class="px-4 py-3 text-right">Performa % Capaian Bulan</th> 
                    <th class="px-4 py-3 text-center">Kategori Capaian Bulan</th>
                    <th class="px-4 py-3 text-right">Performa % Capaian Tahun</th>
                    <th class="px-4 py-3 text-center">Kategori Capaian Tahun</th>
                    <th class="px-4 py-3 text-right">Normalisasi %</th>
                    <th class="px-4 py-3 text-right">Norm Angka</th> 
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700 text-white">
                    <td colspan="14" class="px-4 py-8 text-center text-slate-500 italic">Antrian masih kosong.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- ACTION BUTTONS: RESET & SAVE -->
    <div class="mt-8 flex justify-center gap-4">

        
        <!-- Tombol Simpan Permanen -->
        <button type="button" id="btnSaveToDb" disabled class="hidden bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-bold py-4 px-12 rounded-2xl shadow-xl opacity-50 cursor-not-allowed">
            <i class="fa-solid fa-cloud-arrow-up me-2"></i> Simpan Permanen
        </button>
    </div>
</div>

<!-- MODAL EDIT -->
<div id="modalEdit" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-2xl shadow-2xl transform transition-all">
        <div class="p-6 border-b border-slate-700 flex justify-between items-center bg-slate-800/50 rounded-t-2xl">
            <h3 class="text-lg font-bold text-white">
                <i class="fa-solid fa-pen-to-square text-amber-500 mr-2"></i> Edit Data
            </h3>
            <button id="btnCloseModal" class="text-slate-400 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        
        <div class="p-6 space-y-6">
            <!-- AREA EDIT UTAMA (IKU & PERIODE) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-slate-800/50 rounded-xl border border-slate-700/50">
                <div class="col-span-2">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Fungsi</label>
                    <select id="edit_fungsi" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2 text-white focus:border-amber-500 focus:outline-none">
                        <!-- Opsi akan dicopy dari main form via JS -->
                    </select>
                </div>
                <div>
                     <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Tahun</label>
                     <select id="edit_tahun" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2 text-white focus:border-amber-500 focus:outline-none">
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                     </select>
                </div>
                <div>
                     <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Bulan</label>
                     <select id="edit_bulan" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2 text-white focus:border-amber-500 focus:outline-none">
                        <!-- Opsi akan dicopy dari main form via JS -->
                     </select>
                </div>
                 <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">No. IKU</label>
                    <select id="edit_no_iku" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2 text-white focus:border-amber-500 focus:outline-none">
                        <!-- Opsi akan dicopy dari main form via JS -->
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Indikator</label>
                    <select id="edit_no_indikator" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2 text-white focus:border-amber-500 focus:outline-none">
                         <!-- Opsi akan dicopy dari main form via JS -->
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Target</label>
                    <input type="number" step="0.01" id="edit_target" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2 text-white focus:border-amber-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Realisasi</label>
                    <input type="number" step="0.01" id="edit_realisasi" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2 text-white focus:border-amber-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Perf % Bulan</label>
                    <input type="number" step="0.01" id="edit_perf_bulan" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2 text-white focus:border-amber-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Kat Bulan</label>
                    <select id="edit_kat_bulan" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2 text-white focus:border-amber-500 focus:outline-none">
                        <option value="Sangat Baik">Sangat Baik</option>
                        <option value="Baik">Baik</option>
                        <option value="Cukup">Cukup</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Perf % Tahun</label>
                    <input type="number" step="0.01" id="edit_perf_tahun" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2 text-white focus:border-amber-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Kat Tahun</label>
                    <select id="edit_kat_tahun" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2 text-white focus:border-amber-500 focus:outline-none">
                        <option value="Sangat Baik">Sangat Baik</option>
                        <option value="Baik">Baik</option>
                        <option value="Cukup">Cukup</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Norm %</label>
                    <input type="number" step="0.01" id="edit_norm_persen" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2 text-white focus:border-amber-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Norm Angka</label>
                    <input type="number" step="0.01" id="edit_norm_angka" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2 text-white focus:border-amber-500 focus:outline-none">
                </div>
            </div>
        </div>

        <div class="p-6 border-t border-slate-700 flex justify-end gap-3 bg-slate-800/50 rounded-b-2xl">
            <button id="btnCancelEdit" class="px-6 py-2 rounded-xl text-slate-300 hover:bg-slate-700 font-bold transition-all">Batal</button>
            <button id="btnUpdateItem" disabled class="px-6 py-2 rounded-xl bg-amber-500 text-slate-900 font-bold shadow-lg shadow-amber-500/20 transition-all opacity-50 cursor-not-allowed">
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
    <script src="<?= base_url('assets/js/admin/entry_iku.js') ?>"></script>
<?= $this->endSection() ?>

