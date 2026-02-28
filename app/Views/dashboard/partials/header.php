<header class="h-auto border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/80 backdrop-blur-md z-10 sticky top-0 transition-colors duration-300">
    <div class="flex flex-wrap items-center justify-between px-4 md:px-8 py-4 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white transition-colors duration-300">
                <?php 
                if($activeMenu == 'dashboard') echo 'Ringkasan Kinerja IKU';
                    elseif($activeMenu == 'database') echo 'Database Iku Realtime';
                    elseif($activeMenu == 'anggaran') echo 'Manajemen Anggaran';
                    elseif($activeMenu == 'capaian_output') echo 'Monitor Capaian Output';
                    elseif($activeMenu == 'data_entry') echo 'Data Management System';
                    elseif($activeMenu == 'profile') echo 'Pengaturan Profil';
                    else echo 'Ringkasan Kinerja';
                ?>
            </h2>
            <p class="text-sm text-slate-600 dark:text-slate-500 transition-colors duration-300">
                <?php 
                    if($activeMenu == 'data_entry') {
                        echo 'Silakan pilih kategori data yang ingin diinput atau diperbarui.';
                    } elseif($activeMenu == 'profile') {
                        echo 'Perbarui informasi akun dan preferensi Anda.';
                    } else {
                        // Dynamically retrieve the latest update timestamp from the active tables
                        $db = \Config\Database::connect();
                        $q = $db->query("SELECT MAX(updated_at) as last_update FROM (
                            SELECT updated_at FROM capaian_iku
                            UNION ALL
                            SELECT updated_at FROM transaksi_anggaran_iku
                            UNION ALL
                            SELECT updated_at FROM master_anggaran_iku
                        ) as all_updates");
                        $row = $q->getRow();
                        $lastUpdate = ($row && $row->last_update) 
                            ? date('d M Y \P\u\k\u\l H:i', strtotime($row->last_update)) 
                            : date('d M Y');
                        
                        echo 'Update database terakhir: ' . $lastUpdate;
                    }
                ?>
            </p>
        </div>
        
        <?php if($activeMenu != 'data_entry' && $activeMenu != 'profile'): ?>
        <div class="flex flex-wrap gap-3 no-print items-center mt-2 md:mt-0">
            <?php if($activeMenu == 'dashboard' || $activeMenu == 'database'): ?>
                <select id='filterNamaIndikator' onchange="applyFilter()" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-white text-xs rounded-lg p-2.5 w-48 cursor-pointer focus:ring-1 focus:ring-teal-500 outline-none transition-colors shadow-sm">
                    <option value="" class="bg-white dark:bg-slate-900">Nama Indikator</option>
                    <?php if(isset($filterIndikator)): ?>
                        <?php foreach($filterIndikator as $i): ?>
                            <?php if(!empty($i['nama'])): ?>
                                <option value="<?= $i['nama'] ?>" class="bg-white dark:bg-slate-900" 
                                    <?= (request()->getGet('nama_indikator') == $i['nama']) ? 'selected' : '' ?>>
                                    <?= $i['nama'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

                <select id="filterFungsi" onchange="applyFilter()" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-white text-xs rounded-lg p-2.5 cursor-pointer focus:ring-1 focus:ring-teal-500 outline-none transition-colors shadow-sm">
                    <option value="" class="bg-white dark:bg-slate-900">Semua Fungsi</option>
                    <?php if(isset($filter_fungsi)): ?>
                        <?php foreach($filter_fungsi as $f): ?>
                            <?php if(!empty($f['Fungsi'])): ?>
                                <option value="<?= $f['Fungsi'] ?>" class="bg-white dark:bg-slate-900" 
                                    <?= (request()->getGet('fungsi') == $f['Fungsi']) ? 'selected' : '' ?>>
                                    <?= $f['Fungsi'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

            <?php else: ?>
                <?php if($activeMenu == 'anggaran'): ?>
                    <select id="filterProgram" onchange="applyFilter()" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-white text-xs rounded-lg p-2.5 max-w-[200px] cursor-pointer focus:ring-1 focus:ring-teal-500 outline-none transition-colors shadow-sm">
                        <option value="" class="bg-white dark:bg-slate-900">Semua Program</option>
                        <?php if(isset($filter_program)): ?>
                            <?php foreach($filter_program as $p): ?>
                                <?php if(!empty($p['PROGRAM/KEGIATAN'])): ?>
                                    <option value="<?= $p['PROGRAM/KEGIATAN'] ?>" class="bg-white dark:bg-slate-900" 
                                        <?= (request()->getGet('program') == $p['PROGRAM/KEGIATAN']) ? 'selected' : '' ?>>
                                        <?= $p['PROGRAM/KEGIATAN'] ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                <?php endif; ?>

                <select id="filterRO" onchange="applyFilter()" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-white text-xs rounded-lg p-2.5 max-w-[200px] cursor-pointer focus:ring-1 focus:ring-teal-500 outline-none transition-colors shadow-sm">
                    <option value="" class="bg-white dark:bg-slate-900">Keterangan RO</option>
                    <?php 
                        $ro_data = ($activeMenu == 'anggaran') ? ($filter_ro ?? []) : ($filter_keterangan_ro ?? []);
                        $ro_param = ($activeMenu == 'anggaran') ? 'ro' : 'keterangan_ro';
                    ?>
                    <?php foreach($ro_data as $ro): ?>
                        <?php $val = ($activeMenu == 'anggaran') ? $ro['RO'] : $ro['keterangan']; ?>
                        <?php if(!empty($val)): ?>
                            <option value="<?= $val ?>" class="bg-white dark:bg-slate-900" <?= (request()->getGet($ro_param) == $val) ? 'selected' : '' ?>>
                                <?= $val ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>

            <select id="filterBulan" onchange="applyFilter()" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-white text-xs rounded-lg p-2.5 cursor-pointer focus:ring-1 focus:ring-teal-500 outline-none transition-colors shadow-sm">
                <option value="" class="bg-white dark:bg-slate-900" <?= (request()->getGet('bulan') == '') ? 'selected' : '' ?>>Semua Bulan</option>
                <?php if(isset($filter_bulan)): ?>
                    <?php foreach($filter_bulan as $b): ?>
                        <?php if(!empty($b['Bulan'])): ?>
                            <option value="<?= $b['Bulan'] ?>" class="bg-white dark:bg-slate-900" <?= (request()->getGet('bulan') == $b['Bulan']) ? 'selected' : '' ?>>
                                <?= $b['Bulan'] ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

            <?php $currentTahun = (request()->getGet('tahun') === null) ? date('Y') : request()->getGet('tahun'); ?>
            <select id="filterTahun" onchange="applyFilter()" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-white text-xs rounded-lg p-2.5 min-w-[100px] cursor-pointer focus:ring-1 focus:ring-teal-500 outline-none transition-colors shadow-sm">
                <option value="" class="bg-white dark:bg-slate-900" <?= ($currentTahun === '') ? 'selected' : '' ?>>Semua Tahun</option>
                <?php if(isset($filter_tahun)): ?>
                    <?php foreach($filter_tahun as $t): ?>
                        <?php if(!empty($t['Tahun'])): ?>
                            <option value="<?= $t['Tahun'] ?>" class="bg-white dark:bg-slate-900" <?= ($currentTahun == $t['Tahun']) ? 'selected' : '' ?>>
                                <?= $t['Tahun'] ?>
                            </option>
                        <?php endif; ?>
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
    <div class="px-8 border-t border-slate-200 dark:border-slate-800/50 no-print transition-colors duration-300">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="entryTabs" role="tablist">
            <li class="me-2" role="presentation">
                <button class="inline-flex items-center p-4 border-b-2 border-teal-500 text-teal-600 dark:text-teal-400 rounded-t-lg active" 
                        id="iku-tab" data-bs-toggle="tab" data-bs-target="#iku-content" type="button" role="tab">
                    <i class="fa-solid fa-chart-line me-2"></i>Capaian IKU
                </button>
            </li>
            <li class="me-2" role="presentation">
                <button class="inline-flex items-center p-4 border-b-2 border-transparent text-slate-600 dark:text-slate-500 hover:text-teal-600 dark:hover:text-teal-400 hover:border-teal-400 rounded-t-lg transition-all" 
                        id="nko-tab" data-bs-toggle="tab" data-bs-target="#nko-content" type="button" role="tab">
                    <i class="fa-solid fa-chart-pie me-2"></i>NKO
                </button>
            </li>
            <li class="me-2" role="presentation">
                <button class="inline-flex items-center p-4 border-b-2 border-transparent text-slate-400 dark:text-slate-500 rounded-t-lg opacity-50 cursor-not-allowed" disabled>
                    <i class="fa-solid fa-file-contract me-2"></i>Perjanjian Kinerja
                </button>
            </li>
            <li class="me-2" role="presentation">
                <button class="inline-flex items-center p-4 border-b-2 border-transparent text-slate-600 dark:text-slate-500 hover:text-teal-600 dark:hover:text-teal-400 hover:border-teal-400 rounded-t-lg transition-all" 
                        id="capaian-output-tab" data-bs-toggle="tab" data-bs-target="#capaian-output-content" type="button" role="tab">
                    <i class="fa-solid fa-chart-line me-2"></i>Capaian Output
                </button>
            </li>
            <li class="me-2" role="presentation">
                <button class="inline-flex items-center p-4 border-b-2 border-transparent text-slate-600 dark:text-slate-500 hover:text-teal-600 dark:hover:text-teal-400 hover:border-teal-400 rounded-t-lg transition-all" 
                        id="anggaran-tab" data-bs-toggle="tab" data-bs-target="#anggaran-content" type="button" role="tab">
                    <i class="fa-solid fa-coins me-2"></i>Anggaran
                </button>
            </li>
            <li class="me-2" role="presentation">
                <button class="inline-flex items-center p-4 border-b-2 border-transparent text-slate-400 dark:text-slate-500 rounded-t-lg opacity-50 cursor-not-allowed" disabled>
                    <i class="fa-solid fa-database me-2"></i>Master Database
                </button>
            </li>
        </ul>
    </div>
    <?php endif; ?>
</header>

<!-- PDF Export Loading Overlay -->
<div id="pdf-loading-overlay" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[100] hidden flex-col items-center justify-center transition-opacity duration-300 opacity-0">
    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-2xl flex flex-col items-center gap-4 border border-slate-200 dark:border-slate-700">
        <i class="fa-solid fa-spinner fa-spin text-4xl text-teal-500"></i>
        <div class="text-center">
            <h3 class="text-slate-800 dark:text-white font-bold text-lg">Mengekspor PDF...</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">Harap tunggu, sedang merender grafik.</p>
        </div>
    </div>
</div>

<!-- html2pdf.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<?php if(isset($showBackButton) && $showBackButton): ?>
<div class="px-4 md:px-8 py-6">
    <a href="<?= $backUrl ?? '#' ?>" class="flex items-center gap-2 text-slate-500 dark:text-slate-400 hover:text-teal-600 dark:hover:text-teal-400 text-sm transition-colors w-fit">
        <i class="fa-solid fa-arrow-left"></i> <?= $backLabel ?? 'Kembali' ?>
    </a>
</div>
<?php endif; ?>



<script>
function handleExportPDF() {
    // Tentukan nama file secara dinamis berdasarkan halaman aktif
    let activeMenu = '<?= $activeMenu ?>';
    let fileName = 'Export_Data.pdf'; // Default fallback
    
    switch (activeMenu) {
        case 'dashboard':
            fileName = 'Rangkuman_Capaian_IKU.pdf';
            break;
        case 'database':
            fileName = 'Database_IKU_Realtime.pdf';
            break;
        case 'anggaran':
            fileName = 'Rangkuman_Manajemen_Anggaran.pdf';
            break;
        case 'capaian_output':
            fileName = 'Rangkuman_Capaian_Output.pdf';
            break;
        default:
            fileName = 'Export_' + activeMenu + '.pdf';
    }

    // 1. Tampilkan Overlay Loading
    const overlay = document.getElementById('pdf-loading-overlay');
    overlay.classList.remove('hidden');
    // Beri sedikit jeda agar display:block teraplikasi sebelum merubah opacity untuk efek fade
    setTimeout(() => {
        overlay.classList.remove('opacity-0');
        overlay.classList.add('opacity-100');
    }, 10);

    // 2. Berikan waktu untuk membiarkan UI Loading tampil optimal sebelum thread JS terkunci oleh rendering PDF
    setTimeout(() => {
        // Tentukan Area yang akan di-ekspor
        const element = document.getElementById('pdf-export-target') || document.querySelector('.main-content') || document.querySelector('.flex-1.overflow-y-auto');
        
        // Tambahkan class khusus untuk styling ekspor PDF
        element.classList.add('pdf-exporting');

        // Sembunyikan elemen sementara sebelum dirender (scrollbars dll)
        const originalOverflow = element.style.overflow;
        element.style.overflow = 'visible';

        // Konfigurasi html2pdf
        const opt = {
            margin:       [0.5, 0.5, 0.5, 0.5],
            filename:     fileName,
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true, logging: false },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
        };

        // Mulai Proses Generate PDF
        html2pdf().set(opt).from(element).save().then(() => {
            // Sukses: Kembalikan State
            element.classList.remove('pdf-exporting');
            overlay.classList.remove('opacity-100');
            overlay.classList.add('opacity-0');
            element.style.overflow = originalOverflow;
            setTimeout(() => overlay.classList.add('hidden'), 300);
        }).catch(err => {
            // Error Handling
            console.error("PDF Export Error: ", err);
            alert("Terjadi kesalahan saat mengekspor PDF.");
            element.classList.remove('pdf-exporting');
            overlay.classList.remove('opacity-100');
            overlay.classList.add('opacity-0');
            element.style.overflow = originalOverflow;
            setTimeout(() => overlay.classList.add('hidden'), 300);
        });
        
    }, 800); // Tunggu sebentar
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
/* PDF Export Styles (Applied only when exporting via JS) */
.pdf-exporting {
    height: auto !important;
    overflow: visible !important;
    position: static !important;
    background: white !important;
    margin: 0 !important;
    padding: 20px !important;
}

.pdf-exporting .sidebar, .pdf-exporting #sidebar, .pdf-exporting .navbar, .pdf-exporting .no-print, .pdf-exporting .filter-section, .pdf-exporting .btn-refresh, .pdf-exporting .btn-export-pdf {
    display: none !important;
    visibility: hidden !important;
}

.pdf-exporting .main-content, .pdf-exporting main, .pdf-exporting .content-wrapper, .pdf-exporting #content {
    margin: 0 !important;
    padding: 0 !important;
    width: 100% !important;
    position: static !important;
    display: block !important;
    left: 0 !important;
}

.pdf-exporting .glass-card, .pdf-exporting .bg-slate-800\/50, .pdf-exporting .card, .pdf-exporting .chart-card, .pdf-exporting .bg-white\/80 {
    break-inside: avoid !important;
    page-break-inside: avoid !important;
    background: white !important;
    color: black !important;
    border: 1px solid #ddd !important;
    margin-bottom: 30px !important;
    display: block !important;
    position: relative !important;
    width: 100% !important;
    overflow: hidden !important; 
    box-shadow: none !important;
}

.pdf-exporting .chart-container, .pdf-exporting .apexcharts-canvas, .pdf-exporting svg, .pdf-exporting canvas {
    width: 100% !important;
    max-width: 100% !important;
    height: 350px !important;
    display: block !important;
    position: relative !important;
}

.pdf-exporting table {
    width: 100% !important;
    border-collapse: collapse !important;
}

.pdf-exporting h1, .pdf-exporting h2, .pdf-exporting h3, .pdf-exporting h4, .pdf-exporting p, .pdf-exporting span, .pdf-exporting td, .pdf-exporting th, .pdf-exporting .text-white, .pdf-exporting .text-slate-400, .pdf-exporting .text-teal-400, .pdf-exporting .text-orange-400, .pdf-exporting .text-purple-400, .pdf-exporting .text-blue-400 {
    color: black !important;
}

.pdf-exporting .grid {
    display: block !important;
}

.pdf-exporting .grid > div {
    margin-bottom: 2rem !important;
    page-break-inside: avoid !important;
}
</style>