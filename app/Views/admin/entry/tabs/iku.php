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
            id="capaian_normalisasi_persen" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-teal-400 font-bold focus:outline-none focus:border-teal-500">
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
            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-teal-400 font-bold focus:outline-none focus:border-teal-500">
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
                    <th class="px-4 py-3">Indikator</th>
                    <th class="px-4 py-3">Bulan</th>
                    <th class="px-4 py-3 text-right">Target</th>
                    <th class="px-4 py-3 text-right">Realisasi</th>
                    <th class="px-4 py-3 text-right">Perf %</th> 
                    <th class="px-4 py-3 text-right">Norm %</th>
                    <th class="px-4 py-3 text-right">Norm Angka</th> 
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700 text-slate-300">
                <tr id="emptyRow">
                    <td colspan="8" class="px-4 py-8 text-center text-slate-500 italic">Antrian masih kosong.</td>
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
let queueData = [];

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

                if (Array.isArray(response) && response.length > 0) {

                    response.forEach(function (item) {
                        $noIku.append(
                            `<option value="${item.no_iku}">${item.no_iku}</option>`
                        );

                        $noIndikator.append(
                            `<option value="${item.no_iku}">
                                ${item.no_iku} - ${item.nama_indikator}
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
            no_iku: $('#no_iku').val(),

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
                    <td colspan="8" class="px-4 py-8 text-center text-slate-500 italic">
                        Antrian masih kosong.
                    </td>
                </tr>
            `);
            counter.text('0 Baris');
            btnSave.addClass('hidden').prop('disabled', true);
            return;
        }

        queueData.forEach((item) => {
            tbody.append(`
                <tr class="hover:bg-slate-800/50 border-b border-slate-700/50">
                    <td class="px-4 py-3 text-teal-400">${item.no_iku}</td>
                    <td class="px-4 py-3">${item.nama_indikator}</td>
                    <td class="px-4 py-3">${item.bulan} ${item.tahun}</td>
                    <td class="px-4 py-3 text-right">${item.target}</td>
                    <td class="px-4 py-3 text-right">${item.realisasi}</td>
                    <td class="px-4 py-3 text-right">${item.perf_bulan ?? 0}%</td>
                    <td class="px-4 py-3 text-right">${item.capaian_normalisasi_persen ?? 0}</td>
                    <td class="px-4 py-3 text-right">${item.capaian_normalisasi_angka ?? 0}</td>
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

});
</script>
