<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?= $this->include('admin/pengajuan/partials/header') ?>

<div class="flex-1 overflow-y-auto p-6 md:p-8 pb-32">
    <div class="max-w-6xl mx-auto space-y-6">

        <!-- ALERTS -->
        <?php if(session()->getFlashdata('message')): ?>
            <div class="bg-teal-500/10 border border-teal-500/50 text-teal-400 p-4 rounded-xl flex items-center gap-3 animate-fade-in-down">
                <i class="fa-solid fa-circle-check text-xl"></i>
                <div>
                    <span class="font-bold block">Berhasil!</span>
                    <span class="text-sm"><?= session()->getFlashdata('message') ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="bg-red-500/10 border border-red-500/50 text-red-400 p-4 rounded-xl flex items-center gap-3 animate-fade-in-down">
                <i class="fa-solid fa-circle-exclamation text-xl"></i>
                <div>
                    <span class="font-bold block">Gagal!</span>
                    <span class="text-sm"><?= session()->getFlashdata('error') ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('errors')): ?>
             <div class="bg-red-500/10 border border-red-500/50 text-red-400 p-4 rounded-xl animate-fade-in-down">
                <div class="flex items-center gap-3 mb-2">
                    <i class="fa-solid fa-circle-exclamation text-xl"></i>
                    <span class="font-bold">Terjadi Kesalahan Validasi:</span>
                </div>
                <ul class="list-disc list-inside text-sm opacity-80 pl-8">
                <?php foreach(session()->getFlashdata('errors') as $e): ?>
                    <li><?= $e ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- CARD 1: FILTER PENCARIAN -->
        <div class="bg-slate-900/50 border border-slate-800 rounded-3xl p-8">
            <h3 class="text-teal-500 font-bold uppercase tracking-wider text-sm mb-6 flex items-center gap-2">
                <i class="fa-solid fa-filter"></i> Filter Data (Sumber: capaian_iku)
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- TAHUN -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase">Tahun</label>
                    <select id="tahun" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all">
                        <option value="">Pilih Tahun</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                    </select>
                </div>

                <!-- BULAN -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase">Bulan</label>
                    <select id="bulan" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all">
                        <option value="">Pilih Bulan</option>
                        <?php 
                        $bulanArr = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        foreach($bulanArr as $b): ?>
                            <option value="<?= $b ?>"><?= $b ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- FUNGSI -->
                <div class="space-y-2 md:col-span-2">
                <label class="block text-xs font-bold text-slate-500 uppercase">Fungsi / Substansi</label>
                    <select id="fungsi" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all">
                        <option value="">Pilih Fungsi</option>
                        <?php if(isset($list_fungsi)): foreach($list_fungsi as $f): ?>
                            <option value="<?= $f->Fungsi ?>"><?= $f->Fungsi ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>

                <!-- NO IKU -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase">No. IKU</label>
                    <select id="no_iku" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all" disabled>
                        <option value="">Pilih Tahun Dulu</option>
                    </select>
                </div>

                <!-- NAMA INDIKATOR -->
                <div class="space-y-2 md:col-span-3">
                    <label class="block text-xs font-bold text-slate-500 uppercase">Nama Indikator</label>
                    <input type="text" id="nama_indikator" readonly 
                           class="w-full bg-slate-900/50 border border-slate-800 text-slate-400 rounded-xl px-4 py-3 text-sm focus:outline-none cursor-not-allowed placeholder-slate-700"
                           placeholder="Otomatis terisi...">
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <button id="btnCheck" class="bg-teal-600 hover:bg-teal-500 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-teal-500/20 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-search"></i>
                    <span>Cek Data Database</span>
                </button>
            </div>
        </div>

        <!-- CARD 2: HASIL DATA DARI DB (TAHAP 2) -->
        <div id="resultCard" class="hidden bg-slate-900/50 border border-slate-800 rounded-3xl p-8 relative overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-teal-500/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            
            <div class="relative z-10 mb-6 border-b border-slate-800 pb-4">
                 <h3 class="text-teal-500 font-bold uppercase tracking-wider text-sm flex items-center gap-2">
                    <i class="fa-solid fa-list-check"></i> Tahap 2: Konfirmasi Data Database (15 Kolom)
                </h3>
                <p class="text-xs text-slate-500 mt-1">Pilih data spesifik dari dropdown berikut ini untuk memastikan integritas data.</p>
            </div>

            <!-- GRID DATA (15 DROPDOWNS) -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 relative z-10">
                
                <!-- 1. Tahun -->
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">Tahun</label>
                    <select id="db_tahun" name="db_tahun" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-teal-500">
                        <option value="">-</option>
                    </select>
                </div>

                <!-- 2. Bulan -->
                 <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">Bulan</label>
                    <select id="db_bulan" name="db_bulan" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-teal-500">
                        <option value="">-</option>
                    </select>
                </div>

                <!-- 3. Fungsi -->
                 <div class="bg-slate-950 p-3 rounded-xl border border-slate-800 md:col-span-2">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">Fungsi</label>
                    <select id="db_fungsi" name="db_fungsi" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-teal-500">
                        <option value="">-</option>
                    </select>
                </div>

                <!-- 4. No. IKU -->
                 <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">No. IKU</label>
                    <select id="db_no_iku" name="db_no_iku" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-teal-500">
                        <option value="">-</option>
                    </select>
                </div>

                <!-- 5. Nama Indikator (Full Width) -->
                 <div class="bg-slate-950 p-3 rounded-xl border border-slate-800 md:col-span-5">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">Nama Indikator</label>
                     <select id="db_nama_indikator" name="db_nama_indikator" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-teal-500">
                        <option value="">-</option>
                    </select>
                </div>
                
                <!-- 6. No Indikator -->
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">No. Indikator</label>
                    <select id="db_no_indikator" name="db_no_indikator" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-teal-500">
                        <option value="">-</option>
                    </select>
                </div>

                <!-- 7. No Bulan -->
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">No. Bulan</label>
                    <select id="db_no_bulan" name="db_no_bulan" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-teal-500">
                        <option value="">-</option>
                    </select>
                </div>

                <!-- 8. Target -->
                <div class="bg-slate-950 p-3 rounded-xl border border-teal-900/30">
                    <label class="block text-[10px] text-teal-500 font-bold uppercase mb-1">Target</label>
                     <select id="db_target" name="db_target" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-teal-400 font-mono font-bold text-xs focus:ring-1 focus:ring-teal-500">
                        <option value="">-</option>
                    </select>
                </div>

                <!-- 9. Realisasi -->
                <div class="bg-slate-950 p-3 rounded-xl border border-amber-900/30">
                    <label class="block text-[10px] text-amber-500 font-bold uppercase mb-1">Realisasi</label>
                     <select id="db_realisasi" name="db_realisasi" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-amber-400 font-mono font-bold text-xs focus:ring-1 focus:ring-teal-500">
                        <option value="">-</option>
                    </select>
                </div>

                <!-- 10. Capaian Bulan -->
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">% Capaian Bulan</label>
                    <select id="db_perf_bulan" name="db_perf_bulan" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-teal-500">
                        <option value="">-</option>
                    </select>
                </div>

                <!-- 11. Kategori Bulan -->
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">Kategori Bulan</label>
                    <select id="db_kat_bulan" name="db_kat_bulan" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-teal-500">
                        <option value="">-</option>
                    </select>
                </div>

                <!-- 12. Capaian Tahun -->
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">% Capaian Tahun</label>
                    <select id="db_perf_tahun" name="db_perf_tahun" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-teal-500">
                        <option value="">-</option>
                    </select>
                </div>

                <!-- 13. Kategori Tahun -->
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">Kategori Tahun</label>
                    <select id="db_kat_tahun" name="db_kat_tahun" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-teal-500">
                        <option value="">-</option>
                    </select>
                </div>

                <!-- 14. Capaian Normalisasi -->
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">Capaian Norm</label>
                    <select id="db_cap_norm" name="db_cap_norm" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-teal-500">
                        <option value="">-</option>
                    </select>
                </div>

                <!-- 15. Cap Norm Angka -->
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">Cap. Norm Angka</label>
                    <select id="db_cap_norm_angka" name="db_cap_norm_angka" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-teal-500">
                        <option value="">-</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button id="btnValidate" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-check-double"></i>
                    <span>Cek Validitas Data</span>
                </button>
            </div>
        </div>

        <!-- CARD 3: INPUT NILAI BARU (ORANGE THEME) -->
        <div id="newValueCard" class="hidden bg-slate-900/50 border border-amber-600/50 rounded-3xl p-8 relative overflow-hidden">
             <!-- Background Decoration -->
             <div class="absolute top-0 left-0 w-64 h-64 bg-amber-500/5 rounded-full blur-3xl -translate-y-1/2 -translate-x-1/2"></div>
            
             <div class="relative z-10 mb-6 border-b border-amber-900/50 pb-4">
                 <h3 class="text-amber-500 font-bold uppercase tracking-wider text-sm flex items-center gap-2">
                    <i class="fa-solid fa-pencil"></i> Tahap 3: Input Perubahan Nilai Data
                </h3>
                <p class="text-xs text-slate-500 mt-1">Silakan ubah nilai pada kolom-kolom di bawah ini sesuai perbaikan yang diinginkan (Otomatis terisi data lama).</p>
            </div>

            <!-- GRID DATA EDITABLE (15 INPUTS) -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 relative z-10">
                
                <!-- 1. Tahun -->
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">Tahun</label>
                    <input type="number" id="new_tahun" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-amber-500 outline-none">
                </div>

                <!-- 2. Bulan -->
                 <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">Bulan</label>
                    <input type="text" id="new_bulan" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-amber-500 outline-none">
                </div>

                <!-- 3. Fungsi -->
                 <div class="bg-slate-950 p-3 rounded-xl border border-slate-800 md:col-span-2">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">Fungsi</label>
                    <input type="text" id="new_fungsi" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-amber-500 outline-none">
                </div>

                <!-- 4. No. IKU -->
                 <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">No. IKU</label>
                    <input type="text" id="new_no_iku" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-amber-500 outline-none">
                </div>

                <!-- 5. Nama Indikator -->
                 <div class="bg-slate-950 p-3 rounded-xl border border-slate-800 md:col-span-5">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">Nama Indikator</label>
                     <input type="text" id="new_nama_indikator" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-amber-500 outline-none">
                </div>
                
                <!-- 6. No Indikator -->
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">No. Indikator</label>
                    <input type="text" id="new_no_indikator" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-amber-500 outline-none">
                </div>

                <!-- 7. No Bulan -->
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">No. Bulan</label>
                    <input type="text" id="new_no_bulan" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-amber-500 outline-none">
                </div>

                <!-- 8. Target (Highlighted) -->
                <div class="bg-slate-950 p-3 rounded-xl border border-amber-500/50 shadow shadow-amber-500/10">
                    <label class="block text-[10px] text-amber-500 font-bold uppercase mb-1">Target</label>
                     <input type="text" id="new_target" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-amber-400 font-mono font-bold text-xs focus:ring-1 focus:ring-amber-500 outline-none">
                </div>

                <!-- 9. Realisasi (Highlighted) -->
                <div class="bg-slate-950 p-3 rounded-xl border border-amber-500/50 shadow shadow-amber-500/10">
                    <label class="block text-[10px] text-amber-500 font-bold uppercase mb-1">Realisasi</label>
                     <input type="text" id="new_realisasi" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-amber-400 font-mono font-bold text-xs focus:ring-1 focus:ring-amber-500 outline-none">
                </div>

                <!-- 10. Capaian Bulan -->
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">% Capaian Bulan</label>
                    <input type="text" id="new_perf_bulan" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-amber-500 outline-none">
                </div>

                <!-- 11. Kategori Bulan -->
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">Kategori Bulan</label>
                    <input type="text" id="new_kat_bulan" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-amber-500 outline-none">
                </div>

                <!-- 12. Capaian Tahun -->
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">% Capaian Tahun</label>
                    <input type="text" id="new_perf_tahun" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-amber-500 outline-none">
                </div>

                <!-- 13. Kategori Tahun -->
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">Kategori Tahun</label>
                    <input type="text" id="new_kat_tahun" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-amber-500 outline-none">
                </div>

                <!-- 14. Capaian Normalisasi -->
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">Capaian Norm</label>
                    <input type="text" id="new_cap_norm" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-amber-500 outline-none">
                </div>

                <!-- 15. Cap Norm Angka -->
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                    <label class="block text-[10px] text-slate-500 font-bold uppercase mb-1">Cap. Norm Angka</label>
                    <input type="text" id="new_cap_norm_angka" class="w-full bg-slate-900 border border-slate-700 rounded px-2 py-1 text-slate-300 text-xs focus:ring-1 focus:ring-amber-500 outline-none">
                </div>
            </div>
        </div>
        
        <!-- CARD 4: FORM PENGAJUAN REVISI (TAHAP 4) -->
        <div id="revisionCard" class="hidden bg-slate-900/50 border border-slate-800 rounded-3xl p-8 relative overflow-hidden">
             <!-- Background Decoration -->
             <div class="absolute top-0 right-0 w-64 h-64 bg-teal-500/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            
            <div class="relative z-10 mb-6 border-b border-slate-800 pb-4">
                 <h3 class="text-teal-500 font-bold uppercase tracking-wider text-sm flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Tahap 4: Upload Bukti & Kirim
                </h3>
                <p class="text-xs text-slate-500 mt-1">Masukkan dokumen bukti dukung dan catatan tambahan.</p>
            </div>

            <form action="<?= base_url('admin/pengajuan/store') ?>" method="POST" enctype="multipart/form-data" class="space-y-6 relative z-10">
                <?= csrf_field() ?>
                
                <!-- Hidden Inputs for Identity -->
                <input type="hidden" name="tahun" id="input_tahun">
                <input type="hidden" name="bulan" id="input_bulan">
                <input type="hidden" name="fungsi" id="input_fungsi">
                <input type="hidden" name="no_iku" id="input_no_iku">
                <input type="hidden" name="nama_indikator" id="input_nama_indikator">
                <input type="hidden" name="no_indikator" id="input_no_indikator">
                <input type="hidden" name="no_bulan" id="input_no_bulan">
                
                <!-- Hidden Inputs for Detailed Revision -->
                <input type="hidden" name="target" id="input_target">
                <input type="hidden" name="realisasi" id="input_realisasi">
                <input type="hidden" name="perf_bulan" id="input_perf_bulan">
                <input type="hidden" name="kat_bulan" id="input_kat_bulan">
                <input type="hidden" name="perf_tahun" id="input_perf_tahun">
                <input type="hidden" name="kat_tahun" id="input_kat_tahun">
                <input type="hidden" name="cap_norm" id="input_cap_norm">
                <input type="hidden" name="cap_norm_angka" id="input_cap_norm_angka">

                <!-- Hidden Input for New Values Summary (Legacy/Extra) -->
                <input type="hidden" name="nilai_menjadi" id="input_nilai_menjadi">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Dokumen Bukti -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase">Dokumen Pendukung / Bukti (PDF/IMG)</label>
                        <input type="file" name="file_nota_dinas" required
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 text-sm text-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-amber-600 file:text-white hover:file:bg-amber-500 transition-all">
                        <p class="text-[10px] text-slate-600">Max size: 5MB. Format: PDF, DOC, JPG, PNG.</p>
                    </div>

                    <!-- Jenis Perubahan (Opsional) -> Mapping to logic later, for now just collecting -->
                    <div class="space-y-2">
                         <label class="block text-xs font-bold text-slate-500 uppercase">Jenis Revisi</label>
                         <select name="jenis_revisi" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500">
                             <option value="Perbaikan Nilai">Perbaikan Nilai Capaian</option>
                             <option value="Kesalahan Input">Kesalahan Input</option>
                             <option value="Lainnya">Lainnya</option>
                         </select>
                    </div>

                    <!-- Keterangan / Catatan -->
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase">Rincian / Catatan Perubahan</label>
                        <textarea name="keterangan" rows="4" required placeholder="Jelaskan bagian mana yang perlu direvisi dan nilai yang seharusnya..."
                                  class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500 transition-all"></textarea>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-amber-600 hover:bg-amber-500 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-amber-500/20 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>Kirim Pengajuan Revisi</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    
    // 1. Load Dropdown IKU saat Tahun Berubah
    $('#tahun').on('change', function() {
        const tahun = $(this).val();
        const $noIku = $('#no_iku');
        
        $noIku.prop('disabled', true).html('<option>Loading...</option>');
        
        if(!tahun) {
            $noIku.html('<option>Pilih Tahun Dulu</option>');
            return;
        }

        // Gunakan endpoint existing Entry untuk list IKU
        $.ajax({
            url: '<?= base_url("admin/entry/get_iku_by_tahun") ?>/' + tahun,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                 $noIku.empty().append('<option value="">-- Pilih No. IKU --</option>');
                 window.ikuMap = {}; // Simpan untuk auto-fill nama indikator
                 
                 if(res.length > 0) {
                     res.forEach(item => {
                         $noIku.append(`<option value="${item.no_iku}">${item.iku_label}</option>`);
                         window.ikuMap[item.no_iku] = item.nama_indikator;
                     });
                     $noIku.prop('disabled', false);
                 } else {
                     $noIku.html('<option>Tidak ada data IKU</option>');
                 }
            },
            error: function() {
                $noIku.html('<option>Gagal memuat data</option>');
            }
        });
    });

    // 2. Auto-fill Nama Indikator
    $('#no_iku').on('change', function() {
        const val = $(this).val();
        if(val && window.ikuMap && window.ikuMap[val]) {
            $('#nama_indikator').val(window.ikuMap[val]);
        } else {
            $('#nama_indikator').val('');
        }
    });

    // Helper function to Populate Select Options
    function populateDbSelect(id, options) {
        let html = '';
        if(options && options.length > 0) {
            options.forEach(opt => {
                html += `<option value="${opt}">${opt}</option>`;
            });
        } else {
            html = '<option value="">-</option>';
        }
        $(id).html(html);
    }

    // 3. Tombol Cek Data Database
    $('#btnCheck').on('click', function() {
        const tahun = $('#tahun').val();
        const bulan = $('#bulan').val();
        const fungsi = $('#fungsi').val();
        const no_iku = $('#no_iku').val();

        if(!tahun || !bulan || !fungsi || !no_iku) {
            alert('Mohon lengkapi semua filter (Tahun, Bulan, Fungsi, No. IKU)');
            return;
        }

        const $btn = $(this);
        const originalText = $btn.html();
        $btn.html('<i class="fa-solid fa-spinner fa-spin"></i> Checking...').prop('disabled', true);
        
        // Hide next stages if research triggered
        $('#resultCard').addClass('hidden');
        $('#revisionCard').addClass('hidden');

        // AJAX ke Check Data
        $.ajax({
            url: '<?= base_url("admin/pengajuan/check_data") ?>',
            type: 'GET',
            data: { tahun, bulan, fungsi, no_iku },
            dataType: 'json',
            success: function(res) {
                $btn.html(originalText).prop('disabled', false);
                
                if(res.status === 'found') {
                    // Tampilkan Card Hasil
                    $('#resultCard').removeClass('hidden').addClass('block');
                    
                    const opts = res.options;
                    
                    // Populate All 15 Dropdowns with Options
                    populateDbSelect('#db_tahun', opts.tahun);
                    populateDbSelect('#db_bulan', opts.bulan);
                    populateDbSelect('#db_fungsi', opts.fungsi);
                    populateDbSelect('#db_no_iku', opts.no_iku);
                    populateDbSelect('#db_nama_indikator', opts.nama_indikator);

                    populateDbSelect('#db_no_indikator', opts.no_indikator);
                    populateDbSelect('#db_no_bulan', opts.no_bulan);
                    populateDbSelect('#db_target', opts.target);
                    populateDbSelect('#db_realisasi', opts.realisasi);
                    populateDbSelect('#db_perf_bulan', opts.perf_bulan);
                    populateDbSelect('#db_kat_bulan', opts.kat_bulan);
                    populateDbSelect('#db_perf_tahun', opts.perf_tahun);
                    populateDbSelect('#db_kat_tahun', opts.kat_tahun);
                    populateDbSelect('#db_cap_norm', opts.cap_norm);
                    populateDbSelect('#db_cap_norm_angka', opts.cap_norm_angka);

                    // Scroll ke hasil
                    $('html, body').animate({
                        scrollTop: $("#resultCard").offset().top - 100
                    }, 500);

                } else {
                    $('#resultCard').addClass('hidden');
                    $('#revisionCard').addClass('hidden');
                    
                    // Show Error Info
                    const debugMsg = res.debug ? '\nInfo: ' + res.debug : '';
                    const queryMsg = res.query ? '\nQuery: ' + res.query : '';
                    alert('Data Tidak Ditemukan.' + debugMsg + queryMsg);
                }
            },
            error: function(xhr) {
                $btn.html(originalText).prop('disabled', false);
                alert('Terjadi kesalahan server saat mengambil data.');
            }
        });
    });

    // 4. Tombol Cek Validitas
    $('#btnValidate').on('click', function() {
        const data = {
            db_tahun: $('#db_tahun').val(),
            db_bulan: $('#db_bulan').val(),
            db_fungsi: $('#db_fungsi').val(),
            db_no_iku: $('#db_no_iku').val(),
            db_nama_indikator: $('#db_nama_indikator').val(),
            db_no_indikator: $('#db_no_indikator').val(),
            db_no_bulan: $('#db_no_bulan').val(),
            db_target: $('#db_target').val(),
            db_realisasi: $('#db_realisasi').val(),
            db_perf_bulan: $('#db_perf_bulan').val(),
            db_kat_bulan: $('#db_kat_bulan').val(),
            db_perf_tahun: $('#db_perf_tahun').val(),
            db_kat_tahun: $('#db_kat_tahun').val(),
            db_cap_norm: $('#db_cap_norm').val(),
            db_cap_norm_angka: $('#db_cap_norm_angka').val()
        };

        const $btn = $(this);
        const originalText = $btn.html();
        $btn.html('<i class="fa-solid fa-spinner fa-spin"></i> Validating...').prop('disabled', true);
        
        // Hide revision card until valid
        $('#revisionCard').addClass('hidden');

        $.ajax({
            url: '<?= base_url("admin/pengajuan/check_validity") ?>',
            type: 'GET',
            data: data,
            dataType: 'json',
            success: function(res) {
                $btn.html(originalText).prop('disabled', false);
                if(res.status === 'valid') {
                    // Valid! Show Revision Cards
                    alert(res.message);
                    $('#newValueCard').removeClass('hidden').addClass('block');
                    $('#revisionCard').removeClass('hidden').addClass('block');
                    
                    // Populate Hidden Inputs for Submission (Identity)
                    $('#input_tahun').val($('#db_tahun').val());
                    $('#input_bulan').val($('#db_bulan').val());
                    $('#input_fungsi').val($('#db_fungsi').val());
                    $('#input_no_iku').val($('#db_no_iku').val());
                    $('#input_nama_indikator').val($('#db_nama_indikator').val());
                    
                    // Populate Stage 3 Inputs from Stage 2 Values
                    $('#new_tahun').val($('#db_tahun').val());
                    $('#new_bulan').val($('#db_bulan').val());
                    $('#new_fungsi').val($('#db_fungsi').val());
                    $('#new_no_iku').val($('#db_no_iku').val());
                    $('#new_nama_indikator').val($('#db_nama_indikator').val());
                    $('#new_no_indikator').val($('#db_no_indikator').val());
                    $('#new_no_bulan').val($('#db_no_bulan').val());
                    $('#new_target').val($('#db_target').val());
                    $('#new_realisasi').val($('#db_realisasi').val());
                    $('#new_perf_bulan').val($('#db_perf_bulan').val());
                    $('#new_kat_bulan').val($('#db_kat_bulan').val());
                    $('#new_perf_tahun').val($('#db_perf_tahun').val());
                    $('#new_kat_tahun').val($('#db_kat_tahun').val());
                    $('#new_cap_norm').val($('#db_cap_norm').val());
                    $('#new_cap_norm_angka').val($('#db_cap_norm_angka').val());

                    // Scroll to New Value Card
                    $('html, body').animate({
                        scrollTop: $("#newValueCard").offset().top - 100
                    }, 500);

                } else {
                    // Invalid
                    const debugMsg = res.debug_query ? '\nQuery Debug: ' + res.debug_query : '';
                    alert(res.message + debugMsg);
                    $('#newValueCard').addClass('hidden');
                    $('#revisionCard').addClass('hidden');
                }
            },
            error: function() {
                $btn.html(originalText).prop('disabled', false);
                alert('Gagal memvalidasi data.');
            }
        });
    });
    
    // 5. On Form Submit, aggregate New Values into "nilai_menjadi" and Populate Hidden Inputs
    $('form').on('submit', function() {
        // Collect all new values
        const newValues = {
            tahun: $('#new_tahun').val(),
            bulan: $('#new_bulan').val(),
            fungsi: $('#new_fungsi').val(),
            no_iku: $('#new_no_iku').val(),
            nama_indikator: $('#new_nama_indikator').val(),
            no_indikator: $('#new_no_indikator').val(),
            no_bulan: $('#new_no_bulan').val(),
            target: $('#new_target').val(),
            realisasi: $('#new_realisasi').val(),
            perf_bulan: $('#new_perf_bulan').val(),
            kat_bulan: $('#new_kat_bulan').val(),
            perf_tahun: $('#new_perf_tahun').val(),
            kat_tahun: $('#new_kat_tahun').val(),
            cap_norm: $('#new_cap_norm').val(),
            cap_norm_angka: $('#new_cap_norm_angka').val()
        };
        
        // Convert to JSON string (summary)
        $('#input_nilai_menjadi').val(JSON.stringify(newValues));
        
        // Populate Hidden Identity Inputs (Redundant safety)
        if(!$('#input_tahun').val()) $('#input_tahun').val($('#db_tahun').val());
        if(!$('#input_bulan').val()) $('#input_bulan').val($('#db_bulan').val());
        if(!$('#input_fungsi').val()) $('#input_fungsi').val($('#db_fungsi').val());
        if(!$('#input_no_iku').val()) $('#input_no_iku').val($('#db_no_iku').val());
        if(!$('#input_nama_indikator').val()) $('#input_nama_indikator').val($('#db_nama_indikator').val());

        // Populate Detailed Revision Inputs
        $('#input_no_indikator').val($('#new_no_indikator').val());
        $('#input_no_bulan').val($('#new_no_bulan').val());
        $('#input_target').val($('#new_target').val());
        $('#input_realisasi').val($('#new_realisasi').val());
        $('#input_perf_bulan').val($('#new_perf_bulan').val());
        $('#input_kat_bulan').val($('#new_kat_bulan').val());
        $('#input_perf_tahun').val($('#new_perf_tahun').val());
        $('#input_kat_tahun').val($('#new_kat_tahun').val());
        $('#input_cap_norm').val($('#new_cap_norm').val());
        $('#input_cap_norm_angka').val($('#new_cap_norm_angka').val());
    });

});
</script>
<?= $this->endSection() ?>
