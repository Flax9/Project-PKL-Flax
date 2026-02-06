// Pengajuan Form Management
$(document).ready(function () {

    // 1. Load Dropdown IKU saat Tahun Berubah
    $('#tahun').on('change', function () {
        const tahun = $(this).val();
        const $noIku = $('#no_iku');

        $noIku.prop('disabled', true).html('<option>Loading...</option>');

        if (!tahun) {
            $noIku.html('<option>Pilih Tahun Dulu</option>');
            return;
        }

        $.ajax({
            url: window.appConfig.baseUrl + '/admin/entry/get_iku_by_tahun/' + tahun,
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                $noIku.empty().append('<option value="">-- Pilih No. IKU --</option>');
                window.ikuMap = {};

                if (res.length > 0) {
                    res.forEach(item => {
                        $noIku.append(`<option value="${item.no_iku}">${item.iku_label}</option>`);
                        window.ikuMap[item.no_iku] = item.nama_indikator;
                    });
                    $noIku.prop('disabled', false);
                } else {
                    $noIku.html('<option>Tidak ada data IKU</option>');
                }
            },
            error: function () {
                $noIku.html('<option>Gagal memuat data</option>');
            }
        });
    });

    // 2. Auto-fill Nama Indikator
    $('#no_iku').on('change', function () {
        const val = $(this).val();
        if (val && window.ikuMap && window.ikuMap[val]) {
            $('#nama_indikator').val(window.ikuMap[val]);
        } else {
            $('#nama_indikator').val('');
        }
    });

    // Helper function to Populate Select Options
    function populateDbSelect(id, options) {
        let html = '';
        if (options && options.length > 0) {
            options.forEach(opt => {
                html += `<option value="${opt}">${opt}</option>`;
            });
        } else {
            html = '<option value="">-</option>';
        }
        $(id).html(html);
    }

    // 3. Tombol Cek Data Database
    $('#btnCheck').on('click', function () {
        const tahun = $('#tahun').val();
        const bulan = $('#bulan').val();
        const fungsi = $('#fungsi').val();
        const no_iku = $('#no_iku').val();

        if (!tahun || !bulan || !fungsi || !no_iku) {
            alert('Mohon lengkapi semua filter (Tahun, Bulan, Fungsi, No. IKU)');
            return;
        }

        const $btn = $(this);
        const originalText = $btn.html();
        $btn.html('<i class="fa-solid fa-spinner fa-spin"></i> Checking...').prop('disabled', true);

        $('#resultCard').addClass('hidden');
        $('#revisionCard').addClass('hidden');

        $.ajax({
            url: window.appConfig.baseUrl + '/admin/pengajuan/check_data',
            type: 'GET',
            data: { tahun, bulan, fungsi, no_iku },
            dataType: 'json',
            success: function (res) {
                $btn.html(originalText).prop('disabled', false);

                if (res.status === 'found') {
                    $('#resultCard').removeClass('hidden').addClass('block');
                    const opts = res.options;

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

                    $('html, body').animate({
                        scrollTop: $("#resultCard").offset().top - 100
                    }, 500);

                } else {
                    $('#resultCard').addClass('hidden');
                    $('#revisionCard').addClass('hidden');
                    const debugMsg = res.debug ? '\nInfo: ' + res.debug : '';
                    const queryMsg = res.query ? '\nQuery: ' + res.query : '';
                    alert('Data Tidak Ditemukan.' + debugMsg + queryMsg);
                }
            },
            error: function (xhr) {
                $btn.html(originalText).prop('disabled', false);
                alert('Terjadi kesalahan server saat mengambil data.');
            }
        });
    });

    // 4. Tombol Cek Validitas
    $('#btnValidate').on('click', function () {
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

        $('#revisionCard').addClass('hidden');

        $.ajax({
            url: window.appConfig.baseUrl + '/admin/pengajuan/check_validity',
            type: 'GET',
            data: data,
            dataType: 'json',
            success: function (res) {
                $btn.html(originalText).prop('disabled', false);
                if (res.status === 'valid') {
                    alert(res.message);
                    $('#newValueCard').removeClass('hidden').addClass('block');
                    $('#revisionCard').removeClass('hidden').addClass('block');

                    $('#input_tahun').val($('#db_tahun').val());
                    $('#input_bulan').val($('#db_bulan').val());
                    $('#input_fungsi').val($('#db_fungsi').val());
                    $('#input_no_iku').val($('#db_no_iku').val());
                    $('#input_nama_indikator').val($('#db_nama_indikator').val());

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

                    $('html, body').animate({
                        scrollTop: $("#newValueCard").offset().top - 100
                    }, 500);

                } else {
                    const debugMsg = res.debug_query ? '\nQuery Debug: ' + res.debug_query : '';
                    alert(res.message + debugMsg);
                    $('#newValueCard').addClass('hidden');
                    $('#revisionCard').addClass('hidden');
                }
            },
            error: function () {
                $btn.html(originalText).prop('disabled', false);
                alert('Gagal memvalidasi data.');
            }
        });
    });

    // 5. On Form Submit
    $('form').on('submit', function () {
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

        $('#input_nilai_menjadi').val(JSON.stringify(newValues));

        if (!$('#input_tahun').val()) $('#input_tahun').val($('#db_tahun').val());
        if (!$('#input_bulan').val()) $('#input_bulan').val($('#db_bulan').val());
        if (!$('#input_fungsi').val()) $('#input_fungsi').val($('#db_fungsi').val());
        if (!$('#input_no_iku').val()) $('#input_no_iku').val($('#db_no_iku').val());
        if (!$('#input_nama_indikator').val()) $('#input_nama_indikator').val($('#db_nama_indikator').val());

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
