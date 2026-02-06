/**
 * Anggaran Entry Tab JS
 */
$(document).ready(function () {
    let anggaranQueue = JSON.parse(localStorage.getItem('anggaranQueue_v1')) || [];
    renderAnggaranTable();

    // --- MASTER DATA LOADING ---
    let masterAnggaranData = [];

    function loadMasterAnggaran() {
        $.ajax({
            url: window.appConfig.baseUrl + 'admin/entry/get_master_anggaran',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                masterAnggaranData = data;
                const $sel = $('#select_master_ro');
                $sel.empty().append('<option value="">-- Pilih RO --</option>');

                data.forEach(item => {
                    const label = `${item.ro} - ${item.program_kegiatan}`;
                    $sel.append(`<option value="${item.no_ro}">${label}</option>`);
                });
            }
        });
    }
    loadMasterAnggaran();

    // On Change Dropdown
    $('#select_master_ro').on('change', function () {
        const id = $(this).val();
        if (!id) {
            $('#no_ro').val('');
            $('#ro_text').val('');
            $('#program').val('');
            return;
        }

        const selected = masterAnggaranData.find(d => d.no_ro == id);
        if (selected) {
            $('#no_ro').val(selected.no_ro);
            $('#ro_text').val(selected.ro);
            $('#program').val(selected.program_kegiatan);
        }
    });

    // 1. ADD / UPDATE MANUAL
    $('#btnAddAnggaran').on('click', function () {
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
        if (editIdx < 0) resetFormAnggaran();
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
    $('#btnImportAnggaran').on('click', function () {
        $('#fileImportAnggaran').click();
    });

    $('#fileImportAnggaran').on('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('file', file);
        formData.append(window.appConfig.csrfToken, window.appConfig.csrfHash);

        const $btn = $('#btnImportAnggaran');
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Loading...');

        $.ajax({
            url: window.appConfig.baseUrl + 'admin/entry/import_anggaran',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (resp) {
                if (resp.status === 'success') {
                    resp.data.forEach(item => anggaranQueue.push(item));
                    saveAnggaranLocal();
                    renderAnggaranTable();
                    alert('Import berhasil: ' + resp.count + ' data.');
                } else {
                    alert('Gagal: ' + resp.message);
                }
            },
            error: function (xhr) {
                alert('Error upload file.');
                console.error(xhr);
            },
            complete: function () {
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

    $(document).on('click', '.btn-delete-anggaran', function () {
        const idx = $(this).data('index');
        if (confirm('Hapus data ini?')) {
            anggaranQueue.splice(idx, 1);
            saveAnggaranLocal();
            renderAnggaranTable();
            if ($('#anggaranEditIndex').val() == idx) resetFormAnggaran();
        }
    });

    $(document).on('click', '.btn-edit-anggaran', function () {
        const idx = $(this).data('index');
        const item = anggaranQueue[idx];

        $('#thn_anggaran').val(item.tahun);
        $('#bln_anggaran').val(item.bulan);

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

    $('#btnClearAnggaran').on('click', function () {
        if (confirm('Hapus SEMUA data antrian Anggaran?')) {
            anggaranQueue = [];
            saveAnggaranLocal();
            renderAnggaranTable();
        }
    });

    // 5. SAVE TO DB
    $('#btnSaveAnggaran').on('click', function () {
        if (!anggaranQueue.length) return;
        if (!confirm('Simpan ' + anggaranQueue.length + ' data Anggaran ini?')) return;

        $.ajax({
            url: window.appConfig.baseUrl + 'admin/entry/simpan_anggaran_batch',
            type: 'POST',
            data: {
                bulk_data: JSON.stringify(anggaranQueue),
                [window.appConfig.csrfToken]: window.appConfig.csrfHash
            },
            dataType: 'json',
            success: function (resp) {
                if (resp.status === 'success') {
                    alert('Berhasil disimpan!');
                    anggaranQueue = [];
                    saveAnggaranLocal();
                    renderAnggaranTable();
                } else {
                    alert('Gagal: ' + resp.message);
                }
            },
            error: function (xhr) {
                let tableUserMsg = '';
                try {
                    const r = JSON.parse(xhr.responseText);
                    if (r.message) tableUserMsg = r.message;
                } catch (e) { }

                alert('Server Error' + (tableUserMsg ? ': ' + tableUserMsg : ''));
            }
        });
    });
});
