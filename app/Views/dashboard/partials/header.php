<header class="h-auto border-b border-slate-800 bg-slate-900/80 backdrop-blur-md z-10 sticky top-0">
    <div class="flex flex-wrap items-center justify-between px-8 py-4 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white">
                <?php 
                    if($activeMenu == 'dashboard') echo 'Ringkasan Kinerja IKU';
                    elseif($activeMenu == 'anggaran') echo 'Manajemen Anggaran';
                    elseif($activeMenu == 'capaian_output') echo 'Monitor Capaian Output';
                    elseif($activeMenu == 'data_entry') echo 'Data Management System';
                    else echo 'Ringkasan Kinerja';
                ?>
            </h2>
            <p class="text-sm text-slate-500">
                <?= ($activeMenu == 'data_entry') ? 'Silakan pilih kategori data yang ingin diinput atau diperbarui.' : 'Update terakhir: ' . date('d M Y') ?>
            </p>
        </div>
        
        <?php if($activeMenu != 'data_entry'): ?>
        <div class="flex flex-wrap gap-3 no-print">
            <?php if($activeMenu == 'dashboard'): ?>
                <select id='filterNamaIndikator' onchange="applyFilter()" class="bg-slate-800 border border-slate-700 text-white text-xs rounded-lg p-2.5 w-48 cursor-pointer focus:ring-1 focus:ring-teal-500 outline-none">
                    <option value="" class="bg-slate-900">Nama Indikator</option>
                    <?php if(isset($filterIndikator)): ?>
                        <?php foreach($filterIndikator as $i): ?>
                            <option value="<?= $i['nama'] ?>" class="bg-slate-900" 
                                <?= (request()->getGet('nama_indikator') == $i['nama']) ? 'selected' : '' ?>>
                                <?= $i['nama'] ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

                <select id="filterFungsi" onchange="applyFilter()" class="bg-slate-800 border border-slate-700 text-white text-xs rounded-lg p-2.5 cursor-pointer focus:ring-1 focus:ring-teal-500 outline-none">
                    <option value="" class="bg-slate-900">Semua Fungsi</option>
                    <?php if(isset($filter_fungsi)): ?>
                        <?php foreach($filter_fungsi as $f): ?>
                            <option value="<?= $f['Fungsi'] ?>" class="bg-slate-900" 
                                <?= (request()->getGet('fungsi') == $f['Fungsi']) ? 'selected' : '' ?>>
                                <?= $f['Fungsi'] ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

            <?php else: ?>
                <?php if($activeMenu == 'anggaran'): ?>
                    <select id="filterProgram" onchange="applyFilter()" class="bg-slate-800 border border-slate-700 text-white text-xs rounded-lg p-2.5 max-w-[200px] cursor-pointer focus:ring-1 focus:ring-teal-500 outline-none">
                        <option value="" class="bg-slate-900">Semua Program</option>
                        <?php if(isset($filter_program)): ?>
                            <?php foreach($filter_program as $p): ?>
                                <option value="<?= $p['PROGRAM/KEGIATAN'] ?>" class="bg-slate-900" 
                                    <?= (request()->getGet('program') == $p['PROGRAM/KEGIATAN']) ? 'selected' : '' ?>>
                                    <?= $p['PROGRAM/KEGIATAN'] ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                <?php endif; ?>

                <select id="filterRO" onchange="applyFilter()" class="bg-slate-800 border border-slate-700 text-white text-xs rounded-lg p-2.5 max-w-[200px] cursor-pointer focus:ring-1 focus:ring-teal-500 outline-none">
                    <option value="" class="bg-slate-900">Keterangan RO</option>
                    <?php 
                        $ro_data = ($activeMenu == 'anggaran') ? ($filter_ro ?? []) : ($filter_keterangan_ro ?? []);
                        $ro_param = ($activeMenu == 'anggaran') ? 'ro' : 'keterangan_ro';
                    ?>
                    <?php foreach($ro_data as $ro): ?>
                        <?php $val = ($activeMenu == 'anggaran') ? $ro['RO'] : $ro['keterangan']; ?>
                        <option value="<?= $val ?>" class="bg-slate-900" <?= (request()->getGet($ro_param) == $val) ? 'selected' : '' ?>>
                            <?= $val ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>

            <select id="filterBulan" onchange="applyFilter()" class="bg-slate-800 border border-slate-700 text-white text-xs rounded-lg p-2.5 cursor-pointer outline-none">
                <option value="" class="bg-slate-900">Semua Bulan</option>
                <?php if(isset($filter_bulan)): ?>
                    <?php foreach($filter_bulan as $b): ?>
                        <option value="<?= $b['Bulan'] ?>" class="bg-slate-900" <?= (request()->getGet('bulan') == $b['Bulan']) ? 'selected' : '' ?>>
                            <?= $b['Bulan'] ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

            <select id="filterTahun" onchange="applyFilter()" class="bg-slate-800 border border-slate-700 text-white text-xs rounded-lg p-2.5 min-w-[100px] cursor-pointer outline-none">
                <option value="" class="bg-slate-900">Tahun</option>
                <?php if(isset($filter_tahun)): ?>
                    <?php foreach($filter_tahun as $t): ?>
                        <option value="<?= $t['Tahun'] ?>" class="bg-slate-900" <?= (request()->getGet('tahun') == $t['Tahun']) ? 'selected' : '' ?>>
                            <?= $t['Tahun'] ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

            <button onclick="handleExportPDF()" class="bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-lg shadow-teal-500/20">
                <i class="fa-solid fa-download mr-2"></i> <span>Ekspor PDF</span>
            </button>
        </div>
        <?php endif; ?>
    </div>

    <?php if($activeMenu == 'data_entry'): ?>
    <div class="px-8 border-t border-slate-800/50 no-print">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="entryTabs" role="tablist">
            <li class="me-2" role="presentation">
                <button class="inline-flex items-center p-4 border-b-2 border-teal-500 text-teal-400 rounded-t-lg active" 
                        id="iku-tab" data-bs-toggle="tab" data-bs-target="#iku-content" type="button" role="tab">
                    <i class="fa-solid fa-chart-line me-2"></i>Capaian IKU
                </button>
            </li>
            <li class="me-2" role="presentation">
                <button class="inline-flex items-center p-4 border-b-2 border-transparent text-slate-500 rounded-t-lg opacity-50 cursor-not-allowed" disabled>
                    <i class="fa-solid fa-file-contract me-2"></i>Perjanjian Kinerja
                </button>
            </li>
            <li class="me-2" role="presentation">
                <button class="inline-flex items-center p-4 border-b-2 border-transparent text-slate-500 rounded-t-lg opacity-50 cursor-not-allowed" disabled>
                    <i class="fa-solid fa-list-check me-2"></i>Capaian Output
                </button>
            </li>
            <li class="me-2" role="presentation">
                <button class="inline-flex items-center p-4 border-b-2 border-transparent text-slate-500 rounded-t-lg opacity-50 cursor-not-allowed" disabled>
                    <i class="fa-solid fa-coins me-2"></i>Anggaran
                </button>
            </li>
            <li class="me-2" role="presentation">
                <button class="inline-flex items-center p-4 border-b-2 border-transparent text-slate-500 rounded-t-lg opacity-50 cursor-not-allowed" disabled>
                    <i class="fa-solid fa-database me-2"></i>Master Database
                </button>
            </li>
        </ul>
    </div>
    <?php endif; ?>
</header>


<script>
function handleExportPDF() {
    // 1. Paksa browser melakukan resize internal
    window.dispatchEvent(new Event('resize'));

    // 2. Berikan waktu ekstra bagi mesin render grafik untuk menstabilkan posisi
    setTimeout(() => {
        // Jika menggunakan ApexCharts, ini akan memaksa render ulang ke ukuran container cetak
        if (typeof ApexCharts !== 'undefined') {
            window.dispatchEvent(new Event('resize'));
        }
        window.print();
    }, 800); // Waktu tunggu ditambah menjadi 800ms agar lebih aman
}
</script>

<script>
function applyFilter() {
    const params = new URLSearchParams();
    const activeMenu = '<?= $activeMenu ?>';
    
    // Ambil semua elemen filter
    const namaIndikator = document.getElementById('filterNamaIndikator')?.value;
    const fungsi = document.getElementById('filterFungsi')?.value;
    const program = document.getElementById('filterProgram')?.value;
    const ro = document.getElementById('filterRO')?.value;
    const bulan = document.getElementById('filterBulan')?.value;
    const tahun = document.getElementById('filterTahun')?.value;

    // Logika Penambahan Parameter URL
    if (namaIndikator) params.append('nama_indikator', namaIndikator);
    if (fungsi) params.append('fungsi', fungsi);
    if (program) params.append('program', program);
    
    if (ro) {
        const roParam = (activeMenu === 'anggaran') ? 'ro' : 'keterangan_ro';
        params.append(roParam, ro);
    }
    
    if (bulan) params.append('bulan', bulan);
    if (tahun) params.append('tahun', tahun);

    window.location.href = window.location.pathname + '?' + params.toString();
}
</script>

<style>
@media print {
    /* 1. Paksa Dokumen menjadi Aliran Statis dari Atas ke Bawah */
    html, body {
        height: auto !important;
        overflow: visible !important;
        position: static !important;
        background: white !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    /* 2. Sembunyikan Navigasi secara Agresif */
    .sidebar, #sidebar, .navbar, .no-print, .filter-section, .btn-refresh, .btn-export-pdf {
        display: none !important;
        visibility: hidden !important;
    }

    /* 3. Reset Kontainer Utama agar Tidak Ada Ruang Kosong Sidebar */
    .main-content, main, .content-wrapper, #content {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        position: static !important;
        display: block !important;
        left: 0 !important;
    }

    /* 4. FIX: Kunci Kartu dan Grafik agar Tidak Meluap Keluar */
    .glass-card, .bg-slate-800\/50, .card, .chart-card {
        break-inside: avoid !important;
        page-break-inside: avoid !important;
        background: white !important;
        color: black !important;
        border: 1px solid #ddd !important;
        margin-bottom: 30px !important;
        display: block !important;
        position: relative !important;
        width: 100% !important;
        overflow: hidden !important; /* Paksa konten tetap di dalam kartu */
    }

    /* 5. FIX: Paksa Grafik ApexCharts agar Sesuai Lebar Kartu */
    .chart-container, .apexcharts-canvas, svg, canvas {
        width: 100% !important;
        max-width: 100% !important;
        height: 350px !important; /* Tinggi dikurangi sedikit agar lebih stabil */
        display: block !important;
        position: relative !important;
    }

    /* 6. Pastikan Tabel memenuhi Lebar Halaman */
    table {
        width: 100% !important;
        border-collapse: collapse !important;
    }

    /* 7. Warna Teks Hitam */
    h1, h2, h3, h4, p, span, td, th, .text-white, .text-slate-400 {
        color: black !important;
    }

    @page {
        size: A4 portrait;
        margin: 1cm;
    }
}
</style>