(function () {
    // IKU Entry Management
    // LOAD FROM LOCAL STORAGE
    let queueData = JSON.parse(localStorage.getItem('iku_queue_local')) || [];

    function saveToLocal() {
        localStorage.setItem('iku_queue_local', JSON.stringify(queueData));
    }

    $(document).ready(function () {

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
                url: window.appConfig.baseUrl + 'admin/entry/get_iku_by_tahun/' + tahun,
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (response) {
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
                        $noIku.prop('disabled', false);
                        $noIndikator.prop('disabled', false);
                    } else {
                        alert('Master data tahun ' + tahun + ' tidak ditemukan.');
                    }
                },
                error: function (xhr) {
                    console.error('AJAX ERROR:', xhr.responseText);
                    alert('Terjadi kesalahan saat mengambil data master.');
                }
            });
        });

        // 1. RESET SAAT GANTI FUNGSI
        $('#fungsi').on('change', function () {
            $('#no_iku').val('');
            $('#no_indikator').val('');
        });

        // 2. TAMBAH KE ANTRIAN
        $('#btnAddQueue').on('click', function () {
            const selectedIndikator = $('#no_indikator option:selected');
            const namaBulan = $('#bulan').val();

            const mapBulan = {
                'Januari': 1, 'Februari': 2, 'Maret': 3, 'April': 4,
                'Mei': 5, 'Juni': 6, 'Juli': 7, 'Agustus': 8,
                'September': 9, 'Oktober': 10, 'November': 11, 'Desember': 12
            };

            const data = {
                fungsi: $('#fungsi').val(),
                no_iku: $('#no_iku option:selected').text(),
                no_indikator: selectedIndikator.val(),
                nama_indikator: selectedIndikator.text(),
                bulan: namaBulan,
                no_bulan: mapBulan[namaBulan],
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

            if (!data.fungsi || !data.no_iku || !data.no_indikator || !data.target || !data.realisasi) {
                alert('Harap lengkapi Fungsi, No. IKU, Indikator, Target, dan Realisasi!');
                return;
            }

            queueData.push(data);
            saveToLocal();
            renderTable();
            $('input[type="number"]').val('');
        });

        // 3. RENDER TABEL
        function renderTable() {
            const tbody = $('#tableIkuStaging tbody');
            const counter = $('#counterQueue');
            const btnSave = $('#btnSaveToDb');
            const btnClear = $('#btnClearQueue');

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
                btnClear.addClass('hidden');
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
                            <button class="btn-edit text-amber-400 hover:text-amber-300 transition-transform hover:scale-110" data-index="${index}" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="btn-delete text-rose-500 hover:text-rose-400 transition-transform hover:scale-110" data-index="${index}" title="Hapus">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });

            counter.text(queueData.length + ' Baris');
            btnSave.removeClass('hidden').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
            btnClear.removeClass('hidden');
        }

        // RESET ALL DATA
        $('#btnClearQueue').on('click', function () {
            if (confirm('Yakin ingin menghapus SEMUA data di antrian sementara? Data tidak bisa dikembalikan.')) {
                queueData = [];
                saveToLocal();
                renderTable();
            }
        });

        // 4. SIMPAN KE DATABASE
        $('#btnSaveToDb').on('click', function () {
            if (!queueData.length) return;

            if (confirm('Simpan ' + queueData.length + ' data ini?')) {
                const dataSend = {
                    bulk_data: JSON.stringify(queueData)
                };
                dataSend[window.appConfig.csrfToken] = window.appConfig.csrfHash;

                $.ajax({
                    url: window.appConfig.baseUrl + 'admin/entry/simpan_iku_batch',
                    type: 'POST',
                    dataType: 'json',
                    data: dataSend,
                    success: function (response) {
                        if (response.status === 'success') {
                            alert('Berhasil disimpan!');
                            queueData = [];
                            saveToLocal();
                            renderTable();
                        } else {
                            alert('Gagal: ' + response.message);
                        }
                    },
                    error: function (xhr) {
                        alert('Terjadi kesalahan server');
                    }
                });
            }
        });

        // 5. DELETE ITEM
        $(document).on('click', '.btn-delete', function () {
            const index = $(this).data('index');
            if (confirm('Hapus data ini dari antrian?')) {
                queueData.splice(index, 1);
                saveToLocal();
                renderTable();
            }
        });

        // 6. EDIT ITEM (MODAL)
        let editIndex = -1;
        const $modal = $('#modalEdit');

        $(document).on('click', '.btn-edit', function () {
            editIndex = $(this).data('index');
            const item = queueData[editIndex];

            $('#edit_fungsi').html($('#fungsi').html()).val(item.fungsi);
            $('#edit_bulan').html($('#bulan').html()).val(item.bulan);
            $('#edit_tahun').val(item.tahun);

            updateModalIkuOptions(item.tahun, item.no_iku, item.no_indikator);

            $('#edit_target').val(item.target);
            $('#edit_realisasi').val(item.realisasi);
            $('#edit_perf_bulan').val(item.perf_bulan);
            $('#edit_kat_bulan').val(item.kat_bulan);
            $('#edit_perf_tahun').val(item.perf_tahun);
            $('#edit_kat_tahun').val(item.kat_tahun);
            $('#edit_norm_persen').val(item.capaian_normalisasi_persen);
            $('#edit_norm_angka').val(item.capaian_normalisasi_angka);

            $modal.removeClass('hidden').addClass('flex');
            setTimeout(validateEditForm, 100);
        });

        $('#btnCloseModal, #btnCancelEdit').on('click', function () {
            $modal.addClass('hidden').removeClass('flex');
        });

        $('#btnUpdateItem').on('click', function () {
            if (editIndex === -1) return;

            const selectedIndikator = $('#edit_no_indikator option:selected');
            const namaBulan = $('#edit_bulan').val();

            const mapBulan = {
                'Januari': 1, 'Februari': 2, 'Maret': 3, 'April': 4,
                'Mei': 5, 'Juni': 6, 'Juli': 7, 'Agustus': 8,
                'September': 9, 'Oktober': 10, 'November': 11, 'Desember': 12
            };

            const updatedItem = {
                ...queueData[editIndex],
                fungsi: $('#edit_fungsi').val(),
                tahun: $('#edit_tahun').val(),
                bulan: namaBulan,
                no_bulan: mapBulan[namaBulan] || 0,
                no_iku: $('#edit_no_iku option:selected').text(),
                no_indikator: $('#edit_no_indikator').val(),
                nama_indikator: selectedIndikator.text().trim(),
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

        function updateModalIkuOptions(tahun, selectedIku = null, selectedIndikator = null) {
            const $edtIku = $('#edit_no_iku');
            const $edtInd = $('#edit_no_indikator');

            if (!tahun) return;

            $edtIku.prop('disabled', true).html('<option>Loading...</option>');
            $edtInd.prop('disabled', true).html('<option>Loading...</option>');

            $.ajax({
                url: window.appConfig.baseUrl + '/admin/entry/get_iku_by_tahun/' + tahun,
                type: 'GET',
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function (response) {
                    $edtIku.empty().append('<option value="">-- Pilih NO. IKU --</option>');
                    $edtInd.empty().append('<option value="">-- Pilih Indikator --</option>');

                    if (Array.isArray(response) && response.length > 0) {
                        response.forEach(function (itm) {
                            $edtIku.append(`<option value="${itm.iku_label}">${itm.iku_label}</option>`);
                            $edtInd.append(`<option value="${itm.no_iku}">${itm.nama_indikator}</option>`);
                        });

                        if (selectedIku) $edtIku.val(selectedIku);
                        if (selectedIndikator) $edtInd.val(selectedIndikator);

                        $edtIku.prop('disabled', false);
                        $edtInd.prop('disabled', false);
                        validateEditForm();
                    } else {
                        $edtIku.html('<option value="">Data Kosong</option>');
                        $edtInd.html('<option value="">Data Kosong</option>');
                        validateEditForm();
                    }
                },
                error: function () {
                    $edtIku.html('<option value="">Error</option>');
                    $edtInd.html('<option value="">Error</option>');
                    validateEditForm();
                }
            });
        }

        $('#edit_tahun').on('change', function () {
            updateModalIkuOptions($(this).val());
            validateEditForm();
        });

        function validateEditForm() {
            const $btnUpdate = $('#btnUpdateItem');
            const fields = {
                fungsi: $('#edit_fungsi').val(),
                tahun: $('#edit_tahun').val(),
                bulan: $('#edit_bulan').val(),
                no_iku: $('#edit_no_iku').val(),
                no_indikator: $('#edit_no_indikator').val(),
                target: $('#edit_target').val(),
                realisasi: $('#edit_realisasi').val()
            };

            const allFilled = Object.values(fields).every(val =>
                val && val.toString().trim() !== '' && !val.toString().includes('Pilih')
            );

            if (allFilled) {
                $btnUpdate.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed').addClass('hover:bg-amber-400');
            } else {
                $btnUpdate.prop('disabled', true).addClass('opacity-50 cursor-not-allowed').removeClass('hover:bg-amber-400');
            }
        }

        $('#edit_fungsi, #edit_tahun, #edit_bulan, #edit_no_iku, #edit_no_indikator, #edit_target, #edit_realisasi')
            .on('change input', validateEditForm);

        // 7. IMPORT EXCEL FUNCTIONALITY
        $('#btnImportExcel').on('click', function () {
            $('#fileImportInput').click();
        });

        $('#fileImportInput').on('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const validExtensions = ['xlsx', 'xls', 'csv'];
            const fileExtension = file.name.split('.').pop().toLowerCase();

            if (!validExtensions.includes(fileExtension)) {
                alert('Format file tidak valid! Hanya menerima .xlsx, .xls, atau .csv');
                $(this).val('');
                return;
            }

            const $btnImport = $('#btnImportExcel');
            const originalText = $btnImport.html();
            $btnImport.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Memproses...');

            const formData = new FormData();
            formData.append('file', file);
            formData.append(window.appConfig.csrfToken, window.appConfig.csrfHash);

            $.ajax({
                url: window.appConfig.baseUrl + 'admin/entry/import_iku',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        response.data.forEach(item => queueData.push(item));
                        saveToLocal();
                        renderTable();
                        alert(`Berhasil mengimpor ${response.data.length} baris data!`);
                    } else {
                        alert('Gagal: ' + (response.message || 'Unknown error'));
                    }
                },
                error: function (xhr) {
                    let errorMsg = 'Terjadi kesalahan saat mengimpor file.';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) errorMsg = response.message;
                    } catch (e) { }
                    alert(errorMsg);
                },
                complete: function () {
                    $btnImport.prop('disabled', false).html(originalText);
                    $('#fileImportInput').val('');
                }
            });
        });

        renderTable();
    });
})();
