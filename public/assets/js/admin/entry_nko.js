/**
 * NKO Entry Tab JS
 */
$(document).ready(function () {
    let nkoQueue = JSON.parse(localStorage.getItem('nkoQueue_v1')) || [];
    renderNkoTable();

    // 1. ADD / UPDATE MANUAL
    $('#btnAddNkoQueue').on('click', function () {
        const tahun = $('#tahun_nko').val();
        const bulan = $('#bulan_nko').val();
        const totalCapaian = $('#total_capaian').val();
        const totalIku = $('#total_iku').val();
        const nko = $('#nilai_nko').val();
        const editIdx = parseInt($('#nkoEditIndex').val());

        if (!nko) {
            alert('Nilai NKO wajib diisi!');
            return;
        }

        const dataItem = {
            tahun: tahun,
            bulan: bulan,
            total_capaian: totalCapaian || 0,
            total_iku: totalIku || 0,
            nko: nko
        };

        if (editIdx >= 0) {
            // Update Mode
            nkoQueue[editIdx] = dataItem;
            alert('Data berhasil diperbarui!');
            resetFormNko();
        } else {
            // Add Mode
            nkoQueue.push(dataItem);
        }

        saveNkoLocal();
        renderNkoTable();
        if (editIdx < 0) resetFormNko();
    });

    // Helper Reset
    function resetFormNko() {
        $('#nilai_nko').val('');
        $('#total_capaian').val('');
        $('#total_iku').val('');
        $('#nkoEditIndex').val('-1');
        $('#btnAddNkoQueue').html('<i class="fa-solid fa-plus"></i> Tambah ke Antrian').removeClass('bg-amber-600 hover:bg-amber-500').addClass('bg-teal-600 hover:bg-teal-500');
    }

    // 2. IMPORT EXCEL
    $('#btnImportNko').on('click', function () {
        $('#fileImportNko').click();
    });

    $('#fileImportNko').on('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('file', file);
        formData.append(window.appConfig.csrfToken, window.appConfig.csrfHash);

        const $btn = $('#btnImportNko');
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Loading...');

        $.ajax({
            url: window.appConfig.baseUrl + 'admin/entry/import_nko',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (resp) {
                if (resp.status === 'success') {
                    resp.data.forEach(item => nkoQueue.push(item));
                    saveNkoLocal();
                    renderNkoTable();
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
                $('#fileImportNko').val('');
            }
        });
    });

    // 3. RENDER TABLE
    function renderNkoTable() {
        const tbody = $('#tableNkoStaging tbody');
        const btnSave = $('#btnSaveNko');
        const btnClear = $('#btnClearNkoQueue');
        const counter = $('#counterNkoQueue');

        tbody.empty();

        if (nkoQueue.length === 0) {
            tbody.html('<tr><td colspan="6" class="px-6 py-8 text-center text-slate-500 italic">Antrian masih kosong.</td></tr>');
            btnSave.addClass('hidden').prop('disabled', true);
            btnClear.addClass('hidden');
            counter.text('0 Baris');
            return;
        }

        nkoQueue.forEach((item, i) => {
            tbody.append(`
                <tr class="hover:bg-slate-800/50 border-b border-slate-700/50">
                    <td class="px-6 py-4 text-white font-medium">${item.tahun}</td>
                    <td class="px-6 py-4 text-white font-medium">${item.bulan}</td>
                    <td class="px-6 py-4 text-center text-white font-medium">${item.total_capaian}</td>
                    <td class="px-6 py-4 text-center text-white font-medium">${item.total_iku}</td>
                    <td class="px-6 py-4 text-center text-white font-bold">${item.nko}</td>
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        <button class="btn-edit-nko text-amber-500 hover:text-amber-400 transition-transform hover:scale-110 mr-3" data-index="${i}" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn-delete-nko text-rose-500 hover:text-rose-400 transition-transform hover:scale-110" data-index="${i}" title="Hapus">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </td>
                </tr>
            `);
        });

        btnSave.removeClass('hidden').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
        btnClear.removeClass('hidden');
        counter.text(nkoQueue.length + ' Baris');
    }

    // 4. ACTIONS
    function saveNkoLocal() {
        localStorage.setItem('nkoQueue_v1', JSON.stringify(nkoQueue));
    }

    $(document).on('click', '.btn-delete-nko', function () {
        const idx = $(this).data('index');
        if (confirm('Hapus data ini?')) {
            nkoQueue.splice(idx, 1);
            saveNkoLocal();
            renderNkoTable();
            if ($('#nkoEditIndex').val() == idx) resetFormNko();
        }
    });

    $(document).on('click', '.btn-edit-nko', function () {
        const idx = $(this).data('index');
        const item = nkoQueue[idx];

        $('#tahun_nko').val(item.tahun);
        $('#bulan_nko').val(item.bulan);
        $('#total_capaian').val(item.total_capaian);
        $('#total_iku').val(item.total_iku);
        $('#nilai_nko').val(item.nko);

        $('#nkoEditIndex').val(idx);

        // Change Button State
        $('#btnAddNkoQueue').html('<i class="fa-solid fa-pen"></i> Update Antrian')
            .removeClass('bg-teal-600 hover:bg-teal-500')
            .addClass('bg-amber-600 hover:bg-amber-500');

        // Scroll to form
        $('html, body').animate({
            scrollTop: $("#formNkoEntry").offset().top - 100
        }, 500);
    });

    $('#btnClearNkoQueue').on('click', function () {
        if (confirm('Hapus SEMUA data antrian NKO?')) {
            nkoQueue = [];
            saveNkoLocal();
            renderNkoTable();
        }
    });

    // 5. SAVE TO DB
    $('#btnSaveNko').on('click', function () {
        if (!nkoQueue.length) return;
        if (!confirm('Simpan ' + nkoQueue.length + ' data NKO ini?')) return;

        $.ajax({
            url: window.appConfig.baseUrl + 'admin/entry/simpan_nko_batch',
            type: 'POST',
            data: {
                bulk_data: JSON.stringify(nkoQueue),
                [window.appConfig.csrfToken]: window.appConfig.csrfHash
            },
            dataType: 'json',
            success: function (resp) {
                if (resp.status === 'success') {
                    alert('Berhasil disimpan!');
                    nkoQueue = [];
                    saveNkoLocal();
                    renderNkoTable();
                } else {
                    alert('Gagal: ' + resp.message);
                }
            },
            error: function () {
                alert('Server Error');
            }
        });
    });
});
