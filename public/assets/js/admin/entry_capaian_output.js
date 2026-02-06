/**
 * Capaian Output Entry Tab JS
 */
$(document).ready(function () {
    let capaianOutputQueue = JSON.parse(localStorage.getItem('capaianOutputQueue_v1')) || [];
    renderCapaianOutputTable();

    // --- MASTER DATA LOADING ---
    let masterRoData = [];

    function loadMasterRo() {
        $.ajax({
            url: window.appConfig.baseUrl + 'admin/entry/get_master_capaian_output',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                masterRoData = data;
                const $sel = $('#select_master_ro_output');
                $sel.empty().append('<option value="">-- Pilih RO --</option>');

                if (data.length === 0) {
                    $sel.append('<option value="" disabled>-- Tidak ada data --</option>');
                    return;
                }

                data.forEach(item => {
                    const label = `${item['No. RO']} - ${item['RO']} - ${item['Rincian Output']}`;
                    $sel.append(`<option value="${item['No. RO']}">${label}</option>`);
                });
            }
        });
    }
    loadMasterRo();

    // On Change Dropdown
    $('#select_master_ro_output').on('change', function () {
        const noRo = $(this).val();
        if (!noRo) {
            $('#rincian_output').val('');
            $('#kode_ro').val('');
            $('#no_ro_capaian').val('');
            return;
        }

        const selected = masterRoData.find(d => d['No. RO'] == noRo);
        if (selected) {
            $('#rincian_output').val(selected['Rincian Output'] || '');
            $('#kode_ro').val(selected['RO'] || '');
            $('#no_ro_capaian').val(selected['No. RO']);
        }
    });

    // 1. ADD / UPDATE MANUAL
    $('#btnAddCapaianOutput').on('click', function () {
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
        if (editIdx < 0) resetFormCapaianOutput();
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
    $('#btnImportCapaianOutput').on('click', function () {
        $('#fileImportCapaianOutput').click();
    });

    $('#fileImportCapaianOutput').on('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('file', file);
        formData.append(window.appConfig.csrfToken, window.appConfig.csrfHash);

        const $btn = $('#btnImportCapaianOutput');
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Loading...');

        $.ajax({
            url: window.appConfig.baseUrl + 'admin/entry/import_capaian_output',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (resp) {
                if (resp.status === 'success') {
                    resp.data.forEach(item => capaianOutputQueue.push(item));
                    saveCapaianOutputLocal();
                    renderCapaianOutputTable();
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

    $(document).on('click', '.btn-delete-capaian-output', function () {
        const idx = $(this).data('index');
        if (confirm('Hapus data ini?')) {
            capaianOutputQueue.splice(idx, 1);
            saveCapaianOutputLocal();
            renderCapaianOutputTable();
            if ($('#capaianOutputEditIndex').val() == idx) resetFormCapaianOutput();
        }
    });

    $(document).on('click', '.btn-edit-capaian-output', function () {
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

    $('#btnClearCapaianOutput').on('click', function () {
        if (confirm('Hapus SEMUA data antrian Capaian Output?')) {
            capaianOutputQueue = [];
            saveCapaianOutputLocal();
            renderCapaianOutputTable();
        }
    });

    // 5. SAVE TO DB
    $('#btnSaveCapaianOutput').on('click', function () {
        if (!capaianOutputQueue.length) return;
        if (!confirm('Simpan ' + capaianOutputQueue.length + ' data Capaian Output ini?')) return;

        $.ajax({
            url: window.appConfig.baseUrl + 'admin/entry/simpan_capaian_output_batch',
            type: 'POST',
            data: {
                bulk_data: JSON.stringify(capaianOutputQueue),
                [window.appConfig.csrfToken]: window.appConfig.csrfHash
            },
            dataType: 'json',
            success: function (resp) {
                if (resp.status === 'success') {
                    alert('Berhasil disimpan!');
                    capaianOutputQueue = [];
                    saveCapaianOutputLocal();
                    renderCapaianOutputTable();
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
