<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>


<!-- Main Content -->
<main class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50 dark:bg-[#0B1120] transition-colors duration-300">
    <!-- Header -->
    <?= $this->include('dashboard/partials/header') ?>

    <!-- Scrollable Content Area -->
    <div class="flex-1 overflow-y-auto p-4 md:p-8 z-10 flex flex-col gap-4">
        
        <!-- Page Action Header -->


        <div class="grid grid-cols-1 gap-6 items-start">
            
            <!-- Combined IKU & Anggaran Table Card -->
            <div class="glass-card p-0 overflow-hidden border border-slate-200 dark:border-slate-800/50 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-slate-900/50 flex flex-col">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 transition-colors bg-white dark:bg-slate-800/80">
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white transition-colors">Database IKU & Anggaran</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                            <i class="fa-solid fa-circle-info mr-1"></i> Menampilkan data kinerja dan anggaran berdasarkan Program/Kegiatan masing-masing IKU BBPOM Surabaya.
                        </p>
                    </div>
                </div>

                <!-- Table Body Section -->
                <div class="p-6 bg-white dark:bg-slate-800/50 flex-1">
                    <div class="rounded-xl border border-slate-200 dark:border-slate-700/50 w-full h-full max-h-[700px] overflow-auto">
                        <table class="w-full text-sm text-left whitespace-nowrap">
                            <thead class="text-xs text-slate-500 dark:text-slate-400 uppercase bg-slate-100 dark:bg-slate-900 transition-colors sticky top-0 z-20 shadow-md">
                                <tr>
                                        <th class="px-6 py-4 font-bold tracking-wider text-center w-16">No.</th>
                                        <th class="px-6 py-4 font-bold tracking-wider text-center w-32">ID</th>
                                        <th class="px-6 py-4 font-bold tracking-wider">Indikator Kinerja</th>
                                        <th class="px-6 py-4 font-bold tracking-wider text-center">Bulan</th>
                                        <th class="px-6 py-4 font-bold tracking-wider text-center">Tahun</th>
                                        <th class="px-6 py-4 font-bold tracking-wider text-center">Target</th>
                                        <th class="px-6 py-4 font-bold tracking-wider text-center">Realisasi Kinerja</th>
                                        <th class="px-6 py-4 font-bold tracking-wider text-center">% Kinerja</th>
                                        <th class="px-6 py-4 font-bold tracking-wider text-right">Pagu Anggaran</th>
                                        <th class="px-6 py-4 font-bold tracking-wider text-right">Realisasi Anggaran</th>
                                        <th class="px-6 py-4 font-bold tracking-wider text-center">% Capaian</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50 transition-colors">
                                    <?php if(isset($list_combined) && count($list_combined) > 0): ?>
                                        <?php foreach($list_combined as $row): ?>
                                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition-colors group">
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-block px-2 py-1 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded text-xs font-bold border border-slate-200 dark:border-slate-700">
                                                    <?= $row['id_transaksi'] !== null ? $row['id_transaksi'] : '-' ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-block px-3 py-1 bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 rounded-lg text-xs font-bold border border-teal-200 dark:border-teal-800/50 whitespace-nowrap">
                                                    <?= esc($row['no_indikator']) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-normal min-w-[250px] md:min-w-[400px] leading-relaxed">
                                                <?= esc($row['nama_indikator_kinerja']) ?>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-block px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-lg text-xs font-semibold border border-slate-200 dark:border-slate-700">
                                                    <?= esc($row['bulan'] ?? '-') ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-block px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-lg text-xs font-semibold border border-slate-200 dark:border-slate-700">
                                                    <?= esc($row['tahun'] ?? '-') ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <?= $row['target'] !== null ? number_format($row['target'], 2) : '-' ?>
                                            </td>
                                            <td class="px-6 py-4 text-center font-bold text-teal-600 dark:text-teal-400">
                                                <?= $row['realisasi_kinerja'] !== null ? number_format($row['realisasi_kinerja'], 2) : '-' ?>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-block px-2 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded text-xs font-bold">
                                                    <?= $row['persen_kinerja'] !== null ? number_format($row['persen_kinerja'], 2) . '%' : '-' ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                Rp <?= $row['pagu_anggaran'] !== null ? number_format($row['pagu_anggaran'], 0, ',', '.') : '-' ?>
                                            </td>
                                            <td class="px-6 py-4 text-right font-medium text-blue-600 dark:text-blue-400">
                                                Rp <?= $row['realisasi_anggaran'] !== null ? number_format($row['realisasi_anggaran'], 0, ',', '.') : '-' ?>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-block px-2 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded text-xs font-bold">
                                                    <?= $row['persen_anggaran'] !== null ? number_format($row['persen_anggaran'], 2) . '%' : '-' ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="11" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                                        <i class="fa-solid fa-folder-open text-2xl text-slate-400 dark:text-slate-500"></i>
                                                    </div>
                                                    <p class="font-medium">Tidak ada data IKU & Anggaran</p>
                                                    <p class="text-xs mt-1 opacity-70">Silahkan ubah parameter filter Anda</p>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                    </div>
                </div>
                
                <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-700/50 text-xs text-slate-500 dark:text-slate-400 text-center flex justify-between items-center">
                    <span>Sumber Data: Master & Transaksi Anggaran IKU</span>
                    <span class="font-medium px-2 py-1 bg-white dark:bg-slate-800 rounded-md border border-slate-200 dark:border-slate-700">Total: <?= isset($list_combined) ? count($list_combined) : 0 ?> Data</span>
                </div>
            </div>
            
            <div class="bg-white/80 dark:bg-slate-800/50 p-6 rounded-2xl border border-slate-200 dark:border-slate-700/50 shadow-md dark:shadow-lg transition-colors mt-6 mb-6">
                <h3 class="text-slate-800 dark:text-white font-semibold transition-colors">Tren Kinerja IKU Realtime</h3>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 italic print:hidden">
                    <i class="fa-solid fa-circle-info mr-1"></i> Grafik dapat digeser menggunakan gesture mouse trackpad, tekan lalu geser (drag & drop), maupun di-scroll.
                </p>
                <div id="chartTrenKinerja" class="w-full h-[400px] mt-4"></div>
            </div>
        </div>

    </div>
</main>
<?= $this->endSection() ?>
