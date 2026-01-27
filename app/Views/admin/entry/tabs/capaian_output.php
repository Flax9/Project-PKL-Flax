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

<script>
$(document).ready(function() {
    let capaianOutputQueue = JSON.parse(localStorage.getItem('capaianOutputQueue_v1')) || [];
    renderCapaianOutputTable();
    
    // --- MASTER DATA LOADING ---
    let masterRoData = [];
    
    function loadMasterRo() {
        $.ajax({
            url: '<?= base_url('admin/entry/get_master_capaian_output') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                masterRoData = data;
                const $sel = $('#select_master_ro_output');
                $sel.empty().append('<option value="">-- Pilih RO --</option>');
                
                if (data.length === 0) {
                    $sel.append('<option value="" disabled>-- Tidak ada data --</option>');
                    return;
                }
                
                data.forEach(item => {
                    // Format Label: No. RO - RO - Rincian Output
                    const label = `${item['No. RO']} - ${item['RO']} - ${item['Rincian Output']}`;
                    $sel.append(`<option value="${item['No. RO']}">${label}</option>`);
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading Master RO:', error);
            }
        });
    }
    loadMasterRo();
    
    // On Change Dropdown
    $('#select_master_ro_output').on('change', function() {
        const noRo = $(this).val();
        if(!noRo) {
            $('#rincian_output').val('');
            $('#kode_ro').val('');
            $('#no_ro_capaian').val('');
            return;
        }
        
        const selected = masterRoData.find(d => d['No. RO'] == noRo);
        if(selected) {
            $('#rincian_output').val(selected['Rincian Output'] || '');
            $('#kode_ro').val(selected['RO'] || '');
            $('#no_ro_capaian').val(selected['No. RO']); 
        }
    });

    // 1. ADD / UPDATE MANUAL
    $('#btnAddCapaianOutput').on('click', function() {
        const tahun = $('#thn_capaian_output').val();
        const bulan = $('#bulan').val();
        const noBulan = $('#no_bulan').val();
        const rincianOutput = $('#rincian_output').val();
        const kodeRo = $('#kode_ro').val();
        const noRo = $('#no_ro_capaian').val();
        const targetPersenBulan = $('#target_persen_bulan').val();
        const realisasi = $('#realisasi_capaian').val();
        const persenRealisasi = $('#persen_realisasi').val();
        const realisasiKumulatif = $('#realisasi_kumulatif').val();
        const salahPersenRealisasiKumulatif = $('#salah_persen_realisasi_kumulatif').val();
        const capaian = $('#capaian').val();
        const kategori = $('#kategori').val();
        const targetTahun = $('#target_tahun').val();
        const kategoriBelanja = $('#kategori_belanja').val();
        const realisasiKumulatifPersen = $('#realisasi_kumulatif_persen').val();

        const editIdx = parseInt($('#capaianOutputEditIndex').val());

        const dataItem = {
            tahun, bulan, no_bulan: noBulan, rincian_output: rincianOutput,
            kode_ro: kodeRo, no_ro: noRo,
            target_persen_bulan: targetPersenBulan, realisasi, persen_realisasi: persenRealisasi,
            realisasi_kumulatif: realisasiKumulatif, salah_persen_realisasi_kumulatif: salahPersenRealisasiKumulatif,
            capaian, kategori, target_tahun: targetTahun, kategori_belanja: kategoriBelanja,
            realisasi_kumulatif_persen: realisasiKumulatifPersen
        };

        if (editIdx >= 0) {
            capaianOutputQueue[editIdx] = dataItem;
            alert('Data berhasil diperbarui!');
            resetFormCapaianOutput();
        } else {
            capaianOutputQueue.push(dataItem);
        }

        saveCapaianOutputLocal();
        renderCapaianOutputTable();
        if(editIdx < 0) resetFormCapaianOutput(); 
    });

    // Helper Reset
    function resetFormCapaianOutput() {
        $('#select_master_ro_output').val('');
        $('#no_bulan').val('');
        $('#rincian_output').val('');
        $('#kode_ro').val('');
        $('#no_ro_capaian').val('');
        $('#target_persen_bulan').val('');
        $('#realisasi_capaian').val('');
        $('#persen_realisasi').val('');
        $('#realisasi_kumulatif').val('');
        $('#salah_persen_realisasi_kumulatif').val('');
        $('#capaian').val('');
        $('#kategori').val('');
        $('#target_tahun').val('');
        $('#kategori_belanja').val('');
        $('#realisasi_kumulatif_persen').val('');
        
        $('#capaianOutputEditIndex').val('-1');
        $('#btnAddCapaianOutput').html('<i class="fa-solid fa-plus"></i> Tambah ke Antrian').removeClass('bg-amber-600 hover:bg-amber-500').addClass('bg-teal-600 hover:bg-teal-500');
    }

    // 2. IMPORT EXCEL
    $('#btnImportCapaianOutput').on('click', function() {
        $('#fileImportCapaianOutput').click();
    });

    $('#fileImportCapaianOutput').on('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('file', file);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        const $btn = $('#btnImportCapaianOutput');
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Loading...');

        $.ajax({
            url: '<?= base_url('admin/entry/import_capaian_output') ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(resp) {
                if (resp.status === 'success') {
                    resp.data.forEach(item => capaianOutputQueue.push(item));
                    saveCapaianOutputLocal();
                    renderCapaianOutputTable();
                    alert('Import berhasil: ' + resp.count + ' data.');
                } else {
                    alert('Gagal: ' + resp.message);
                }
            },
            error: function(xhr) {
                alert('Error upload file.');
                console.error(xhr);
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalText);
                $('#fileImportCapaianOutput').val('');
            }
        });
    });

    // 3. RENDER TABLE
    function renderCapaianOutputTable() {
        const tbody = $('#tableCapaianOutputStaging tbody');
        const btnSave = $('#btnSaveCapaianOutput');
        const btnClear = $('#btnClearCapaianOutput');
        const counter = $('#counterCapaianOutput');

        tbody.empty();

        if (capaianOutputQueue.length === 0) {
            tbody.html('<tr><td colspan="16" class="px-6 py-8 text-center text-slate-500 italic">Antrian masih kosong.</td></tr>');
            btnSave.addClass('hidden').prop('disabled', true);
            btnClear.addClass('hidden');
            counter.text('0 Baris');
            return;
        }

        capaianOutputQueue.forEach((item, i) => {
            tbody.append(`
                <tr class="hover:bg-slate-800/50 border-b border-slate-700/50">
                    <td class="px-4 py-3 text-white font-medium">${item.tahun}</td>
                    <td class="px-4 py-3 text-white font-medium">${item.bulan}</td>
                    <td class="px-4 py-3 text-white">${item.no_bulan || '-'}</td>
                    <td class="px-4 py-3 text-white truncate max-w-[150px]" title="${item.rincian_output}">${item.rincian_output || '-'}</td>
                    <td class="px-4 py-3 text-white">${item.kode_ro || '-'}</td>
                    <td class="px-4 py-3 text-white">${item.no_ro || '-'}</td>
                    <td class="px-4 py-3 text-right text-white">${item.target_persen_bulan || 0}%</td>
                    <td class="px-4 py-3 text-right text-white">${item.realisasi || 0}</td>
                    <td class="px-4 py-3 text-right text-white">${item.persen_realisasi || 0}%</td>
                    <td class="px-4 py-3 text-right text-white">${item.realisasi_kumulatif || 0}</td>
                    <td class="px-4 py-3 text-right text-white">${item.salah_persen_realisasi_kumulatif || 0}%</td>
                    <td class="px-4 py-3 text-right text-white">${item.capaian || 0}</td>
                    <td class="px-4 py-3 text-center text-white text-[10px] uppercase">${item.kategori || '-'}</td>
                    <td class="px-4 py-3 text-right text-white">${item.target_tahun || 0}</td>
                    <td class="px-4 py-3 text-center text-white text-[10px]">${item.kategori_belanja || '-'}</td>
                    <td class="px-4 py-3 text-right text-white">${item.realisasi_kumulatif_persen || 0}%</td>
                    
                    <td class="px-4 py-3 text-center whitespace-nowrap">
                        <button class="btn-edit-capaian-output text-amber-500 hover:text-amber-400 transition-transform hover:scale-110 mr-3" data-index="${i}" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn-delete-capaian-output text-rose-500 hover:text-rose-400 transition-transform hover:scale-110" data-index="${i}" title="Hapus">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </td>
                </tr>
            `);
        });

        btnSave.removeClass('hidden').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
        btnClear.removeClass('hidden');
        counter.text(capaianOutputQueue.length + ' Baris');
    }

    // 4. ACTIONS
    function saveCapaianOutputLocal() {
        localStorage.setItem('capaianOutputQueue_v1', JSON.stringify(capaianOutputQueue));
    }

    $(document).on('click', '.btn-delete-capaian-output', function() {
        const idx = $(this).data('index');
        if(confirm('Hapus data ini?')) {
            capaianOutputQueue.splice(idx, 1);
            saveCapaianOutputLocal();
            renderCapaianOutputTable();
            if ($('#capaianOutputEditIndex').val() == idx) resetFormCapaianOutput();
        }
    });

    $(document).on('click', '.btn-edit-capaian-output', function() {
        const idx = $(this).data('index');
        const item = capaianOutputQueue[idx];
        
        $('#thn_capaian_output').val(item.tahun);
        $('#bulan').val(item.bulan);
        $('#no_bulan').val(item.no_bulan);
        
        $('#select_master_ro_output').val(item.no_ro);
        $('#rincian_output').val(item.rincian_output);
        $('#kode_ro').val(item.kode_ro);
        $('#no_ro_capaian').val(item.no_ro);
        
        $('#target_persen_bulan').val(item.target_persen_bulan);
        $('#realisasi_capaian').val(item.realisasi);
        $('#persen_realisasi').val(item.persen_realisasi);
        $('#realisasi_kumulatif').val(item.realisasi_kumulatif);
        $('#salah_persen_realisasi_kumulatif').val(item.salah_persen_realisasi_kumulatif);
        $('#capaian').val(item.capaian);
        $('#kategori').val(item.kategori);
        $('#target_tahun').val(item.target_tahun);
        $('#kategori_belanja').val(item.kategori_belanja);
        $('#realisasi_kumulatif_persen').val(item.realisasi_kumulatif_persen);
        
        $('#capaianOutputEditIndex').val(idx);
        
        $('#btnAddCapaianOutput').html('<i class="fa-solid fa-pen"></i> Update Antrian')
            .removeClass('bg-teal-600 hover:bg-teal-500')
            .addClass('bg-amber-600 hover:bg-amber-500');
            
        $('html, body').animate({
            scrollTop: $("#formCapaianOutputEntry").offset().top - 100
        }, 500);
    });

    $('#btnClearCapaianOutput').on('click', function() {
        if(confirm('Hapus SEMUA data antrian Capaian Output?')) {
            capaianOutputQueue = [];
            saveCapaianOutputLocal();
            renderCapaianOutputTable();
        }
    });

    // 5. SAVE TO DB
    $('#btnSaveCapaianOutput').on('click', function() {
        if(!capaianOutputQueue.length) return;
        if(!confirm('Simpan ' + capaianOutputQueue.length + ' data Capaian Output ini?')) return;

        $.ajax({
            url: '<?= base_url('admin/entry/simpan_capaian_output_batch') ?>',
            type: 'POST',
            data: {
                bulk_data: JSON.stringify(capaianOutputQueue),
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(resp) {
                if(resp.status === 'success') {
                    alert('Berhasil disimpan!');
                    capaianOutputQueue = [];
                    saveCapaianOutputLocal();
                    renderCapaianOutputTable();
                } else {
                    alert('Gagal: ' + resp.message);
                }
            },
            error: function(xhr) {
                 let tableUserMsg = '';
                 try {
                     const r = JSON.parse(xhr.responseText);
                     if(r.message) tableUserMsg = r.message;
                 } catch(e) {}
                 
                alert('Server Error' + (tableUserMsg ? ': ' + tableUserMsg : ''));
            }
        });
    });
});
</script>
