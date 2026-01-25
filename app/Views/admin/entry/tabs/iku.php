<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

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
                <tr id="emptyRow">
                    <td colspan="14" class="px-4 py-8 text-center text-slate-500 italic">Antrian masih kosong.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-8 flex justify-center">
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    // LOAD FROM LOCAL STORAGE
    let queueData = JSON.parse(localStorage.getItem('iku_queue_local')) || [];

    function saveToLocal() {
        localStorage.setItem('iku_queue_local', JSON.stringify(queueData));
    }

$(document).ready(function() {
    
    // FILTER IKU SELECT BERDASAR TAHUN
    // Fungsi untuk memuat IKU berdasarkan tahun yang dipilih
    $('#tahun').on('change', function () {

        const tahun = $(this).val();
        const $noIku = $('#no_iku');
        const $noIndikator = $('#no_indikator');

        // Reset dropdown
        $noIku.html('<option value="" disabled selected>Pilih NO. IKU</option>');
        $noIndikator.html('<option value="" disabled selected>Pilih Indikator</option>');

        // Disable dulu (UX)
        $noIku.prop('disabled', true);
        $noIndikator.prop('disabled', true);

        if (!tahun) return;

        $.ajax({
            url: '<?= base_url("admin/entry/get_iku_by_tahun") ?>/' + tahun,
            type: 'GET',
            dataType: 'json',

            /* ==================================================
            ❌ SEBELUMNYA:
            - CI4 tidak mendeteksi AJAX
            - isAJAX() = false
            ================================================== */

            // ✅ WAJIB agar isAJAX() TRUE
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },

            success: function (response) {

                // Check for error response from controller
                if (response.error) {
                    console.error('Controller Error:', response.message);
                    alert('Error: ' + response.message);
                    return;
                }

                if (Array.isArray(response) && response.length > 0) {

                    response.forEach(function (item) {
                        $noIku.append(
                            `<option value="${item.no_iku}">${item.iku_label}</option>`
                        );

                        $noIndikator.append(
                            `<option value="${item.no_iku}">
                                ${item.nama_indikator}
                            </option>`
                        );
                    });

                    // Enable jika data ada
                    $noIku.prop('disabled', false);
                    $noIndikator.prop('disabled', false);

                } else {
                    alert('Master data tahun ' + tahun + ' tidak ditemukan.');
                }
            },

            error: function (xhr) {

                /* ==================================================
                ERROR INI YANG KAMU ALAMI SEBELUMNYA
                ================================================== */
                console.error('AJAX ERROR:', xhr.responseText);
                alert('Terjadi kesalahan saat mengambil data master.');
            }
        });
    });



    // ===============================
    // 1. RESET SAAT GANTI FUNGSI
    // ===============================
    $('#fungsi').on('change', function() {
        $('#no_iku').val('');
        $('#no_indikator').val(''); // FIX: ID yang benar
    });

    // ===============================
    // 2. TAMBAH KE ANTRIAN
    // ===============================
    $('#btnAddQueue').on('click', function() {

        const selectedIndikator = $('#no_indikator option:selected');
        const namaBulan = $('#bulan').val(); // Ambil nama bulan (Misal: "Februari")

        // 1. KAMUS BULAN (AUTO-CONVERT)
        // Membuat objek untuk memetakan nama ke angka
        const mapBulan = {
            'Januari': 1, 'Februari': 2, 'Maret': 3, 'April': 4,
            'Mei': 5, 'Juni': 6, 'Juli': 7, 'Agustus': 8,
            'September': 9, 'Oktober': 10, 'November': 11, 'Desember': 12
        };


        const data = {
            fungsi: $('#fungsi').val(),
            no_iku: $('#no_iku option:selected').text(), // Ambil TEXT "IKU 1", bukan value "1"

            // INI YANG SEBELUMNYA HILANG
            no_indikator: selectedIndikator.val(),          // WAJIB ADA
            nama_indikator: selectedIndikator.text(),      // ambil TEXT

            bulan: namaBulan,       
            no_bulan: mapBulan[namaBulan], // Konversi nama bulan ke angka

            tahun: $('#tahun').val(),
            target: $('input[name="target"]').val(),
            realisasi: $('input[name="realisasi"]').val(),
            perf_bulan: $('#perf_bulan').val(),
            kat_bulan: $('#kat_bulan').val(),
            perf_tahun: $('#perf_tahun').val(),
            kat_tahun: $('#kat_tahun').val(),
            capaian_normalisasi_persen: $('#capaian_normalisasi_persen').val(),
            capaian_normalisasi_angka: $('#capaian_normalisasi_angka').val()
        };

        // ===============================
        // 3. VALIDASI WAJIB
        // ===============================
        if (
            !data.fungsi ||
            !data.no_iku ||
            !data.no_indikator ||
            !data.target ||
            !data.realisasi
        ) {
            alert('Harap lengkapi Fungsi, No. IKU, Indikator, Target, dan Realisasi!');
            return;
        }

        queueData.push(data);
        saveToLocal(); // Simpan ke local
        renderTable();

        // Reset input angka
        $('input[type="number"]').val('');
    });

    // ===============================
    // 4. RENDER TABEL
    // ===============================
    function renderTable() {
        const tbody = $('#tableIkuStaging tbody');
        const counter = $('#counterQueue');
        const btnSave = $('#btnSaveToDb');

        tbody.empty();

        if (queueData.length === 0) {
            tbody.html(`
                <tr>
                    <td colspan="14" class="px-4 py-8 text-center text-slate-500 italic">
                        Antrian masih kosong.
                    </td>
                </tr>
            `);
            counter.text('0 Baris');
            btnSave.addClass('hidden').prop('disabled', true);
            return;
        }

        queueData.forEach((item, index) => {
            tbody.append(`
                <tr class="hover:bg-slate-800/50 border-b border-slate-700/50">
                    <td class="px-4 py-3 font-semibold text-white">${item.fungsi}</td>
                    <td class="px-4 py-3 text-white">${item.no_iku}</td>
                    <td class="px-4 py-3 whitespace-normal" title="${item.nama_indikator}">${item.nama_indikator}</td>
                    <td class="px-4 py-3 text-center whitespace-nowrap">${item.tahun}</td>
                    <td class="px-4 py-3 whitespace-nowrap">${item.bulan}</td>
                    <td class="px-4 py-3 text-right">${item.target}</td>
                    <td class="px-4 py-3 text-right">${item.realisasi}</td>
                    <td class="px-4 py-3 text-right font-bold text-white">${item.perf_bulan ?? 0}%</td>
                    <td class="px-4 py-3 text-center text-[10px] uppercase tracking-wider">${item.kat_bulan}</td>
                    <td class="px-4 py-3 text-right font-bold text-white">${item.perf_tahun ?? 0}%</td>
                    <td class="px-4 py-3 text-center text-[10px] uppercase tracking-wider">${item.kat_tahun}</td>
                    <td class="px-4 py-3 text-right">${item.capaian_normalisasi_persen ?? 0}</td>
                    <td class="px-4 py-3 text-right">${item.capaian_normalisasi_angka ?? 0}</td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <button class="btn-edit text-amber-400 hover:text-amber-300 transition-transform hover:scale-110" data-index="${index}" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="btn-delete text-rose-500 hover:text-rose-400 transition-transform hover:scale-110" data-index="${index}" title="Hapus">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `);
        });

        counter.text(queueData.length + ' Baris');
        btnSave.removeClass('hidden').prop('disabled', false)
               .removeClass('opacity-50 cursor-not-allowed');
    }

    // ===============================
    // 5. SIMPAN KE DATABASE
    // ===============================
    $('#btnSaveToDb').on('click', function() {
        if (!queueData.length) return;

        if (confirm('Simpan ' + queueData.length + ' data ini?')) {
            $.ajax({
                url: '<?= base_url('admin/entry/simpan_iku_batch') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    bulk_data: JSON.stringify(queueData),
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Berhasil disimpan!');
                        queueData = [];
                        saveToLocal(); // Hapus dari local
                        renderTable();
                    } else {
                        alert('Gagal: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan server');
                }
            });
        }
    });


    // ===============================
    // 6. DELETE ITEM
    // ===============================
    $(document).on('click', '.btn-delete', function() {
        const index = $(this).data('index');
        if(confirm('Hapus data ini dari antrian?')) {
            queueData.splice(index, 1);
            saveToLocal();
            renderTable();
        }
    });

    // ===============================
    // 7. EDIT ITEM (MODAL)
    // ===============================
    let editIndex = -1;
    const $modal = $('#modalEdit');

    $(document).on('click', '.btn-edit', function() {
        editIndex = $(this).data('index');
        const item = queueData[editIndex];

        // 1. COPY OPSI DROPDOWN SIMPLE
        $('#edit_fungsi').html($('#fungsi').html()).val(item.fungsi);
        $('#edit_bulan').html($('#bulan').html()).val(item.bulan);
        $('#edit_tahun').val(item.tahun); 

        // 2. LOAD OPSI IKU & INDIKATOR VIA AJAX (Sesuai Tahun Item)
        // Kita panggil fungsi helper agar opsi tahun tsb di-load ulang khusus untuk modal
        // Lalu set value sesuai item yang diedit
        // item.no_iku sekarang berformat "IKU 1", item.no_indikator berupa angka
        updateModalIkuOptions(item.tahun, item.no_iku, item.no_indikator);

        // 3. ISI FORM NILAI
        $('#edit_target').val(item.target);
        $('#edit_realisasi').val(item.realisasi);
        $('#edit_perf_bulan').val(item.perf_bulan);
        $('#edit_kat_bulan').val(item.kat_bulan);
        $('#edit_perf_tahun').val(item.perf_tahun);
        $('#edit_kat_tahun').val(item.kat_tahun);
        $('#edit_norm_persen').val(item.capaian_normalisasi_persen);
        $('#edit_norm_angka').val(item.capaian_normalisasi_angka);

        $modal.removeClass('hidden').addClass('flex');
        
        // Jalankan validasi setelah modal dibuka dan data terisi
        // Untuk mengecek apakah semua field wajib sudah lengkap
        setTimeout(validateEditForm, 100); // Delay kecil untuk memastikan AJAX selesai
    });

    // Tutup Modal
    $('#btnCloseModal, #btnCancelEdit').on('click', function() {
        $modal.addClass('hidden').removeClass('flex');
    });

    // Simpan Perubahan
    $('#btnUpdateItem').on('click', function() {
        if (editIndex === -1) return;

        // Auto-update nama indikator berdasarkan ID yang dipilih
        const selectedIndikator = $('#edit_no_indikator option:selected');
        const selectedIku = $('#edit_no_iku option:selected');
        const namaBulan = $('#edit_bulan').val();

        // Map Bulan (sama seperti saat add)
        const mapBulan = {
            'Januari': 1, 'Februari': 2, 'Maret': 3, 'April': 4,
            'Mei': 5, 'Juni': 6, 'Juli': 7, 'Agustus': 8,
            'September': 9, 'Oktober': 10, 'November': 11, 'Desember': 12
        };

        const updatedItem = {
            ...queueData[editIndex], // Copy properti lama
            
            // Update properti UTAMA
            fungsi: $('#edit_fungsi').val(),
            tahun: $('#edit_tahun').val(),
            bulan: namaBulan,
            no_bulan: mapBulan[namaBulan] || 0,
            no_iku: $('#edit_no_iku option:selected').text(), // Ambil TEXT "IKU 1"
            no_indikator: $('#edit_no_indikator').val(),
            nama_indikator: selectedIndikator.text().trim(), // Ambil teks indikator baru

            // Update Nilai
            target: $('#edit_target').val(),
            realisasi: $('#edit_realisasi').val(),
            perf_bulan: $('#edit_perf_bulan').val(),
            kat_bulan: $('#edit_kat_bulan').val(),
            perf_tahun: $('#edit_perf_tahun').val(),
            kat_tahun: $('#edit_kat_tahun').val(),
            capaian_normalisasi_persen: $('#edit_norm_persen').val(),
            capaian_normalisasi_angka: $('#edit_norm_angka').val()
        };

        queueData[editIndex] = updatedItem;
        saveToLocal();
        renderTable();
        
        $modal.addClass('hidden').removeClass('flex');
        alert('Data berhasil diperbarui!');
    });

    // ===============================
    // 8. LOGIKA DROPDOWN MODAL (AJAX)
    // ===============================
    
    // Fungsi untuk load opsi IKU di modal
    function updateModalIkuOptions(tahun, selectedIku = null, selectedIndikator = null) {
        const $edtIku = $('#edit_no_iku');
        const $edtInd = $('#edit_no_indikator');

        if (!tahun) return;

        // Tampilkan loading/disable sementara
        $edtIku.prop('disabled', true).html('<option>Loading...</option>');
        $edtInd.prop('disabled', true).html('<option>Loading...</option>');

        $.ajax({
            url: '<?= base_url("admin/entry/get_iku_by_tahun") ?>/' + tahun,
            type: 'GET',
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (response) {
                // Bersihkan
                $edtIku.empty().append('<option value="">-- Pilih NO. IKU --</option>');
                $edtInd.empty().append('<option value="">-- Pilih Indikator --</option>');

                if (Array.isArray(response) && response.length > 0) {
                    response.forEach(function (itm) {
                        // Tambah opsi dengan format "IKU 1", "IKU 2"
                        $edtIku.append(`<option value="${itm.iku_label}">${itm.iku_label}</option>`);
                        $edtInd.append(`<option value="${itm.no_iku}">${itm.nama_indikator}</option>`);
                    });

                    // Restore nilai terpilih (jika ada)
                    if (selectedIku) $edtIku.val(selectedIku);
                    if (selectedIndikator) $edtInd.val(selectedIndikator); // di sini pake ID IKU juga sbg value

                    $edtIku.prop('disabled', false);
                    $edtInd.prop('disabled', false);
                    
                    // Jalankan validasi setelah dropdown terisi
                    validateEditForm();
                } else {
                    $edtIku.html('<option value="">Data Kosong</option>');
                    $edtInd.html('<option value="">Data Kosong</option>');
                    validateEditForm(); // Validasi juga saat data kosong
                }
            },
            error: function () {
                $edtIku.html('<option value="">Error</option>');
                $edtInd.html('<option value="">Error</option>');
                validateEditForm(); // Validasi juga saat error
            }
        });
    }

    // Trigger saat tahun di modal diganti
    $('#edit_tahun').on('change', function() {
        updateModalIkuOptions($(this).val());
        validateEditForm(); // Cek validasi setelah ganti tahun
    });

    // ===============================
    // VALIDASI FORM EDIT MODAL
    // ===============================
    function validateEditForm() {
        const $btnUpdate = $('#btnUpdateItem');
        
        // Field wajib yang harus diisi
        const fungsi = $('#edit_fungsi').val();
        const tahun = $('#edit_tahun').val();
        const bulan = $('#edit_bulan').val();
        const no_iku = $('#edit_no_iku').val();
        const no_indikator = $('#edit_no_indikator').val();
        const target = $('#edit_target').val();
        const realisasi = $('#edit_realisasi').val();

        // Cek apakah semua field wajib terisi (tidak kosong, null, atau undefined)
        // Juga cek apakah bukan placeholder text
        const allFilled = 
            fungsi && fungsi.trim() !== '' &&
            tahun && tahun.trim() !== '' &&
            bulan && bulan.trim() !== '' &&
            no_iku && no_iku.trim() !== '' && !no_iku.includes('Pilih') &&
            no_indikator && no_indikator.trim() !== '' && !no_indikator.includes('Pilih') &&
            target && target.trim() !== '' &&
            realisasi && realisasi.trim() !== '';

        if (allFilled) {
            // Enable tombol
            $btnUpdate.prop('disabled', false)
                      .removeClass('opacity-50 cursor-not-allowed')
                      .addClass('hover:bg-amber-400');
        } else {
            // Disable tombol
            $btnUpdate.prop('disabled', true)
                      .addClass('opacity-50 cursor-not-allowed')
                      .removeClass('hover:bg-amber-400');
        }
    }

    // Event listener untuk semua field wajib di modal Edit
    $('#edit_fungsi, #edit_tahun, #edit_bulan, #edit_no_iku, #edit_no_indikator, #edit_target, #edit_realisasi')
        .on('change input', validateEditForm);

    // CATATAN: Sinkronisasi IKU <-> Indikator dihapus karena format value berbeda
    // IKU menggunakan "IKU 1" (string), Indikator menggunakan angka
    // User harus memilih keduanya secara manual di modal Edit



    // Load data awal jika ada di storage
    renderTable();

});
</script>
