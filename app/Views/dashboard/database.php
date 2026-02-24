<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>


<!-- Main Content -->
<main class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50 dark:bg-[#0B1120] transition-colors duration-300">
    <!-- Header -->
    <?= $this->include('dashboard/partials/header') ?>

    <!-- Scrollable Content Area -->
    <div class="flex-1 overflow-y-auto p-4 md:p-8 z-10 flex flex-col gap-4">
        
        <!-- Page Action Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h3 class="font-bold text-xl text-slate-800 dark:text-white transition-colors">Database Realtime</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Seluruh Rincian Indikator Kinerja Utama & Anggaran BBPOM Surabaya</p>
            </div>
            <a href="<?= base_url('dashboard') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-slate-50 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-medium transition-colors shadow-sm border border-slate-200 dark:border-slate-700/50">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 items-start">
            
            <!-- IKU Table Card -->
            <div class="glass-card p-0 overflow-hidden border border-slate-200 dark:border-slate-800/50 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-slate-900/50 flex flex-col">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 transition-colors bg-white dark:bg-slate-800/80">
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white transition-colors">Database IKU</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                            <i class="fa-solid fa-circle-info mr-1"></i> Data realisasi dan target dihitung berdasarkan <span class="font-semibold text-slate-700 dark:text-slate-300">rata-rata (average)</span> per tahun untuk setiap indikator, kecuali jika filter bulan spesifik dipilih.
                        </p>
                    </div>
                </div>

                <!-- Table Body Section -->
                <div class="p-6 bg-white dark:bg-slate-800/50 flex-1">
                    <div class="rounded-xl border border-slate-200 dark:border-slate-700/50 overflow-hidden w-full h-full max-h-[600px] overflow-y-auto">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left whitespace-nowrap">
                                <thead class="text-xs text-slate-500 dark:text-slate-400 uppercase bg-slate-50 dark:bg-slate-900/50 transition-colors sticky top-0 z-10">
                                    <tr>
                                        <th class="px-6 py-4 font-bold tracking-wider">No. IKU</th>
                                        <th class="px-6 py-4 font-bold tracking-wider text-center w-32">Target</th>
                                        <th class="px-6 py-4 font-bold tracking-wider text-center w-32">Realisasi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50 transition-colors">
                                    <?php if(isset($list_iku) && count($list_iku) > 0): ?>
                                        <?php foreach($list_iku as $row): ?>
                                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition-colors group">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 font-bold text-xs group-hover:bg-teal-50 group-hover:text-teal-600 dark:group-hover:bg-teal-900/30 dark:group-hover:text-teal-400 transition-colors">
                                                        <?= esc(str_replace('IKU ', '', $row['no'])) ?>
                                                    </div>
                                                    <span class="font-medium text-slate-700 dark:text-slate-300 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">
                                                        IKU <?= esc(str_replace('IKU ', '', $row['no'])) ?>
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-block px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-lg text-xs font-semibold border border-slate-200 dark:border-slate-700">
                                                    <?= number_format($row['target'], 2) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-block px-3 py-1 bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 rounded-lg text-xs font-bold border border-teal-200 dark:border-teal-800/50">
                                                    <?= number_format($row['realisasi'], 2) ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                                        <i class="fa-solid fa-folder-open text-2xl text-slate-400 dark:text-slate-500"></i>
                                                    </div>
                                                    <p class="font-medium">Tidak ada data IKU</p>
                                                    <p class="text-xs mt-1 opacity-70">Silahkan ubah parameter filter Anda</p>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-700/50 text-xs text-slate-500 dark:text-slate-400 text-center flex justify-between items-center">
                    <span>Sumber Data: Database IKU</span>
                    <span class="font-medium px-2 py-1 bg-white dark:bg-slate-800 rounded-md border border-slate-200 dark:border-slate-700">Total: <?= isset($list_iku) ? count($list_iku) : 0 ?> IKU</span>
                </div>
            </div>

            <!-- Anggaran Table Card -->
            <div class="glass-card p-0 overflow-hidden border border-slate-200 dark:border-slate-800/50 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-slate-900/50 flex flex-col">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 transition-colors bg-white dark:bg-slate-800/80">
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white transition-colors">Database Anggaran</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                            <i class="fa-solid fa-circle-info mr-1"></i> Data nominal pagu dan realisasi merupakan hasil <span class="font-semibold text-slate-700 dark:text-slate-300">akumulasi total (sum)</span> dari seluruh Rincian Output (RO) pada masing-masing Program/Kegiatan.
                        </p>
                    </div>
                </div>

                <!-- Table Body Section -->
                <div class="p-6 bg-white dark:bg-slate-800/50 flex-1">
                    <div class="rounded-xl border border-slate-200 dark:border-slate-700/50 overflow-hidden w-full h-full max-h-[600px] overflow-y-auto">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left whitespace-nowrap">
                                <thead class="text-xs text-slate-500 dark:text-slate-400 uppercase bg-slate-50 dark:bg-slate-900/50 transition-colors sticky top-0 z-10">
                                    <tr>
                                        <th class="px-6 py-4 font-bold tracking-wider">Program / Kegiatan</th>
                                        <th class="px-6 py-4 font-bold tracking-wider text-right w-40">Pagu (Rp)</th>
                                        <th class="px-6 py-4 font-bold tracking-wider text-right w-40">Realisasi (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50 transition-colors">
                                    <?php if(isset($list_anggaran) && count($list_anggaran) > 0): ?>
                                        <?php foreach($list_anggaran as $row): ?>
                                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition-colors group">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 font-bold text-xs group-hover:bg-blue-50 group-hover:text-blue-600 dark:group-hover:bg-blue-900/30 dark:group-hover:text-blue-400 transition-colors">
                                                        <i class="fa-solid fa-coins"></i>
                                                    </div>
                                                    <span class="font-medium text-slate-700 dark:text-slate-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors truncate max-w-[250px]" title="<?= esc($row['program']) ?>">
                                                        <?= esc($row['program']) ?>
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <span class="inline-block px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-lg text-xs font-semibold border border-slate-200 dark:border-slate-700">
                                                    <?= number_format($row['pagu'], 0, ',', '.') ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <span class="inline-block px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg text-xs font-bold border border-blue-200 dark:border-blue-800/50">
                                                    <?= number_format($row['realisasi'], 0, ',', '.') ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                                        <i class="fa-solid fa-folder-open text-2xl text-slate-400 dark:text-slate-500"></i>
                                                    </div>
                                                    <p class="font-medium">Tidak ada data Anggaran</p>
                                                    <p class="text-xs mt-1 opacity-70">Silahkan ubah parameter filter Anda</p>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-700/50 text-xs text-slate-500 dark:text-slate-400 text-center flex justify-between items-center">
                    <span>Sumber Data: Database Anggaran</span>
                    <span class="font-medium px-2 py-1 bg-white dark:bg-slate-800 rounded-md border border-slate-200 dark:border-slate-700">Total: <?= isset($list_anggaran) ? count($list_anggaran) : 0 ?> Program</span>
                </div>
            </div>
            
        </div>

    </div>
</main>
<?= $this->endSection() ?>
