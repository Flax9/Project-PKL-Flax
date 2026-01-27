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

<script>
$(document).ready(function() {
    let anggaranQueue = JSON.parse(localStorage.getItem('anggaranQueue_v1')) || [];
    renderAnggaranTable();
    
    // --- MASTER DATA LOADING ---
    let masterAnggaranData = [];
    
    function loadMasterAnggaran() {
        $.ajax({
            url: '<?= base_url('admin/entry/get_master_anggaran') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                masterAnggaranData = data;
                const $sel = $('#select_master_ro');
                $sel.empty().append('<option value="">-- Pilih RO --</option>');
                
                data.forEach(item => {
                    // Display: "RO ID - Program" (Truncated if too long?)
                    // Item: no_ro, ro, program_kegiatan
                    const label = `${item.ro} - ${item.program_kegiatan}`;
                    $sel.append(`<option value="${item.no_ro}">${label}</option>`);
                });
            }
        });
    }
    loadMasterAnggaran();
    
    // On Change Dropdown
    $('#select_master_ro').on('change', function() {
        const id = $(this).val();
        if(!id) {
            $('#no_ro').val('');
            $('#ro_text').val('');
            $('#program').val('');
            return;
        }
        
        const selected = masterAnggaranData.find(d => d.no_ro == id);
        if(selected) {
            $('#no_ro').val(selected.no_ro);
            $('#ro_text').val(selected.ro);
            $('#program').val(selected.program_kegiatan);
        }
    });

    // 1. ADD / UPDATE MANUAL
    $('#btnAddAnggaran').on('click', function() {
        const tahun = $('#thn_anggaran').val();
        const bulan = $('#bln_anggaran').val();
        const noRo = $('#no_ro').val();
        const ro = $('#ro_text').val();
        const program = $('#program').val();
        const pagu = $('#pagu').val();
        const realisasi = $('#realisasi').val();
        const capReal = $('#capaian_realisasi').val();
        const targetTw = $('#target_tw').val();
        const capTarget = $('#capaian_target_tw').val();
        const kategori = $('#kategori_tw').val();

        const editIdx = parseInt($('#anggaranEditIndex').val());

        const dataItem = {
            tahun: tahun,
            bulan: bulan,
            no_ro: noRo,
            ro: ro,
            program: program,
            pagu: pagu,
            realisasi: realisasi,
            capaian_realisasi: capReal,
            target_tw: targetTw,
            capaian_target_tw: capTarget,
            kategori_tw: kategori
        };

        if (editIdx >= 0) {
            anggaranQueue[editIdx] = dataItem;
            alert('Data berhasil diperbarui!');
            resetFormAnggaran();
        } else {
            anggaranQueue.push(dataItem);
        }

        saveAnggaranLocal();
        renderAnggaranTable();
        if(editIdx < 0) resetFormAnggaran(); 
    });

    // Helper Reset
    function resetFormAnggaran() {
        $('#select_master_ro').val('');
        $('#no_ro').val('');
        $('#ro_text').val('');
        $('#program').val('');
        $('#pagu').val('');
        $('#realisasi').val('');
        $('#capaian_realisasi').val('');
        $('#target_tw').val('');
        $('#capaian_target_tw').val('');
        $('#kategori_tw').val('');
        
        $('#anggaranEditIndex').val('-1');
        $('#btnAddAnggaran').html('<i class="fa-solid fa-plus"></i> Tambah ke Antrian').removeClass('bg-amber-600 hover:bg-amber-500').addClass('bg-teal-600 hover:bg-teal-500');
    }

    // 2. IMPORT EXCEL
    $('#btnImportAnggaran').on('click', function() {
        $('#fileImportAnggaran').click();
    });

    $('#fileImportAnggaran').on('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('file', file);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        const $btn = $('#btnImportAnggaran');
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Loading...');

        $.ajax({
            url: '<?= base_url('admin/entry/import_anggaran') ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(resp) {
                if (resp.status === 'success') {
                    resp.data.forEach(item => anggaranQueue.push(item));
                    saveAnggaranLocal();
                    renderAnggaranTable();
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
                $('#fileImportAnggaran').val('');
            }
        });
    });

    // 3. RENDER TABLE
    function renderAnggaranTable() {
        const tbody = $('#tableAnggaranStaging tbody');
        const btnSave = $('#btnSaveAnggaran');
        const btnClear = $('#btnClearAnggaran');
        const counter = $('#counterAnggaran');

        tbody.empty();

        if (anggaranQueue.length === 0) {
            tbody.html('<tr><td colspan="12" class="px-6 py-8 text-center text-slate-500 italic">Antrian masih kosong.</td></tr>');
            btnSave.addClass('hidden').prop('disabled', true);
            btnClear.addClass('hidden');
            counter.text('0 Baris');
            return;
        }

        anggaranQueue.forEach((item, i) => {
            tbody.append(`
                <tr class="hover:bg-slate-800/50 border-b border-slate-700/50">
                    <td class="px-4 py-3 text-white font-medium">${item.tahun}</td>
                    <td class="px-4 py-3 text-white font-medium">${item.bulan}</td>
                    <td class="px-4 py-3 text-white">${item.no_ro || '-'}</td>
                    <td class="px-4 py-3 text-white truncate max-w-[150px]" title="${item.ro}">${item.ro || '-'}</td>
                    <td class="px-4 py-3 text-white truncate max-w-[150px]" title="${item.program}">${item.program || '-'}</td>
                    <td class="px-4 py-3 text-right text-white">${item.pagu || 0}</td>
                    <td class="px-4 py-3 text-right text-white">${item.realisasi || 0}</td>
                    <td class="px-4 py-3 text-right text-white">${item.capaian_realisasi || 0}</td>
                    <td class="px-4 py-3 text-right text-white">${item.target_tw || 0}</td>
                    <td class="px-4 py-3 text-right text-white">${item.capaian_target_tw || 0}</td>
                    <td class="px-4 py-3 text-center text-white text-[10px] uppercase">${item.kategori_tw || '-'}</td>
                    
                    <td class="px-4 py-3 text-center whitespace-nowrap">
                        <button class="btn-edit-anggaran text-amber-500 hover:text-amber-400 transition-transform hover:scale-110 mr-3" data-index="${i}" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn-delete-anggaran text-rose-500 hover:text-rose-400 transition-transform hover:scale-110" data-index="${i}" title="Hapus">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </td>
                </tr>
            `);
        });

        btnSave.removeClass('hidden').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
        btnClear.removeClass('hidden');
        counter.text(anggaranQueue.length + ' Baris');
    }

    // 4. ACTIONS
    function saveAnggaranLocal() {
        localStorage.setItem('anggaranQueue_v1', JSON.stringify(anggaranQueue));
    }

    $(document).on('click', '.btn-delete-anggaran', function() {
        const idx = $(this).data('index');
        if(confirm('Hapus data ini?')) {
            anggaranQueue.splice(idx, 1);
            saveAnggaranLocal();
            renderAnggaranTable();
            if ($('#anggaranEditIndex').val() == idx) resetFormAnggaran();
        }
    });

    $(document).on('click', '.btn-edit-anggaran', function() {
        const idx = $(this).data('index');
        const item = anggaranQueue[idx];
        
        $('#thn_anggaran').val(item.tahun);
        $('#bln_anggaran').val(item.bulan);
        
        // Update selection and fields
        $('#select_master_ro').val(item.no_ro).trigger('change'); // Trigger change to ensure consistency? 
        // OR manually set values if item has them (safer if item was imported and not in master yet)
        // If we trigger change, it might look up master and set fields.
        // But what if item.no_ro is 0 or not found? 
        // Let's set the value of select, but ALSO set the text fields explicitly from QUEUE DATA.
        // Because Queue Data is source of truth.
        
        $('#select_master_ro').val(item.no_ro);
        $('#no_ro').val(item.no_ro);
        $('#ro_text').val(item.ro);
        $('#program').val(item.program);
        
        $('#pagu').val(item.pagu);
        $('#realisasi').val(item.realisasi);
        $('#capaian_realisasi').val(item.capaian_realisasi);
        $('#target_tw').val(item.target_tw);
        $('#capaian_target_tw').val(item.capaian_target_tw);
        $('#kategori_tw').val(item.kategori_tw);
        
        $('#anggaranEditIndex').val(idx);
        
        $('#btnAddAnggaran').html('<i class="fa-solid fa-pen"></i> Update Antrian')
            .removeClass('bg-teal-600 hover:bg-teal-500')
            .addClass('bg-amber-600 hover:bg-amber-500');
            
        $('html, body').animate({
            scrollTop: $("#formAnggaranEntry").offset().top - 100
        }, 500);
    });

    $('#btnClearAnggaran').on('click', function() {
        if(confirm('Hapus SEMUA data antrian Anggaran?')) {
            anggaranQueue = [];
            saveAnggaranLocal();
            renderAnggaranTable();
        }
    });

    // 5. SAVE TO DB
    $('#btnSaveAnggaran').on('click', function() {
        if(!anggaranQueue.length) return;
        if(!confirm('Simpan ' + anggaranQueue.length + ' data Anggaran ini?')) return;

        $.ajax({
            url: '<?= base_url('admin/entry/simpan_anggaran_batch') ?>',
            type: 'POST',
            data: {
                bulk_data: JSON.stringify(anggaranQueue),
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(resp) {
                if(resp.status === 'success') {
                    alert('Berhasil disimpan!');
                    anggaranQueue = [];
                    saveAnggaranLocal();
                    renderAnggaranTable();
                } else {
                    alert('Gagal: ' + resp.message);
                }
            },
            error: function(xhr) {
                 // Try to get JSON response from error if available
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
