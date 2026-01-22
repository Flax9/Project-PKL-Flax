<div class="mb-6 p-4 bg-amber-500/10 border border-amber-500/50 rounded-2xl flex items-start gap-4">
    <div class="text-amber-500 mt-1">
        <i class="fa-solid fa-triangle-exclamation"></i>
    </div>
    <div>
        <h4 class="text-sm font-bold text-amber-500 uppercase tracking-wider">Peringatan Keamanan</h4>
        <p class="text-xs text-slate-400 leading-relaxed mt-1">
            Data pada tabel antrian disimpan di memori browser. Jika halaman **di-refresh atau ditutup** sebelum klik "Simpan Permanen", semua inputan akan hilang.
        </p>
    </div>
</div>

<form id="formIku" class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Tahun Periode</label>
            <select name="tahun" id="tahun" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-teal-500 focus:outline-none transition-all">
                <option value="2025">2025</option>
                <option value="2026">2026</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Bulan Pelaporan</label>
            <select name="bulan" id="bulan" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-teal-500 focus:outline-none transition-all">
                <?php 
                $bulanArr = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                foreach($bulanArr as $idx => $b): ?>
                    <option value="<?= $b ?>"><?= $b ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Fungsi / Substansi</label>
            <select name="fungsi" id="fungsi" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-teal-500 focus:outline-none transition-all">
                <option value="">-- Pilih Fungsi --</option>
                </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="md:col-span-1">
            <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">No. IKU / Indikator</label>
            <select name="no_iku" id="no_iku" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-teal-500 focus:outline-none transition-all">
                <option value="">-- Pilih No --</option>
            </select>
        </div>
        <div class="md:col-span-3">
            <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Nama Indikator (Auto-Fill)</label>
            <input type="text" id="nama_indikator" readonly 
                   class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-slate-400 cursor-not-allowed italic" 
                   placeholder="Nama indikator akan muncul otomatis...">
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Target</label>
            <input type="number" step="0.01" name="target" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-teal-500 focus:outline-none">
        </div>
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Realisasi</label>
            <input type="number" step="0.01" name="realisasi" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-teal-500 focus:outline-none">
        </div>
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Perf. Bulan</label>
            <input type="number" step="0.01" name="perf_bulan" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-teal-500 focus:outline-none">
        </div>
        <div class="col-span-2">
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Kategori Bulan</label>
            <select name="kat_bulan" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-teal-500 focus:outline-none">
                <option value="Sangat Baik">Sangat Baik</option>
                <option value="Baik">Baik</option>
                <option value="Cukup">Cukup</option>
            </select>
        </div>
    </div>

    <div class="flex flex-col md:flex-row items-end gap-6 pt-4 border-t border-slate-700/50">
        <div class="w-full md:w-1/3">
            <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Normalisasi (%)</label>
            <input type="number" step="0.01" name="normalisasi" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-teal-400 font-bold focus:border-teal-500 focus:outline-none">
        </div>
        <div class="w-full md:w-2/3 text-right">
            <button type="button" id="btnAddQueue" class="bg-teal-600 hover:bg-teal-500 text-white font-bold py-3 px-8 rounded-xl transition-all flex items-center gap-2 ml-auto">
                <i class="fa-solid fa-plus"></i> Kumpulkan Data
            </button>
        </div>
    </div>
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
                    <th class="px-4 py-3">No. IKU</th>
                    <th class="px-4 py-3">Nama Indikator</th>
                    <th class="px-4 py-3">Bulan</th>
                    <th class="px-4 py-3 text-right">Target</th>
                    <th class="px-4 py-3 text-right">Realisasi</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700 text-slate-300">
                <tr id="emptyRow">
                    <td colspan="6" class="px-4 py-8 text-center text-slate-500 italic">Antrian masih kosong. Silakan isi form di atas.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-8 flex justify-center">
        <button type="button" id="btnSaveToDb" disabled 
                class="hidden bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-bold py-4 px-12 rounded-2xl shadow-xl shadow-teal-900/20 opacity-50 cursor-not-allowed transition-all">
            <i class="fa-solid fa-cloud-arrow-up me-2"></i> Simpan Permanen ke Database
        </button>
    </div>
</div>