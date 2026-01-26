<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?= $this->include('dashboard/partials/header') ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<div class="flex-1 overflow-y-auto p-8 z-10 flex flex-col gap-6">

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
        <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700/50 shadow-lg backdrop-blur-sm group hover:border-teal-500/30 transition-all cursor-default">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-400 text-[10px] font-medium uppercase tracking-wider">Total Target</p>
                    <h3 class="text-xl font-bold text-white mt-2"><?= number_format($output_data['scoreboard']->total_target ?? 0, 0, ',', '.') ?></h3>
                </div>
                <div class="p-3 bg-teal-500/10 rounded-xl text-teal-400 group-hover:bg-teal-500 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-bullseye text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700/50 shadow-lg backdrop-blur-sm group hover:border-blue-500/30 transition-all cursor-default">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-400 text-[10px] font-medium uppercase tracking-wider">Realisasi</p>
                    <h3 class="text-xl font-bold text-white mt-2"><?= number_format($output_data['scoreboard']->total_realisasi ?? 0, 0, ',', '.') ?></h3>
                </div>
                <div class="p-3 bg-blue-500/10 rounded-xl text-blue-400 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-wallet text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700/50 shadow-lg backdrop-blur-sm group hover:border-purple-500/30 transition-all cursor-default">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-400 text-[10px] font-medium uppercase tracking-wider">Realisasi Kumulatif</p>
                    <h3 class="text-xl font-bold text-white mt-2"><?= number_format($output_data['scoreboard']->realisasi_kumulatif ?? 0, 0, ',', '.') ?></h3>
                </div>
                <div class="p-3 bg-purple-500/10 rounded-xl text-purple-400 group-hover:bg-purple-500 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-chart-line text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700/50 shadow-lg backdrop-blur-sm group hover:border-teal-400/30 transition-all cursor-default">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-400 text-[10px] font-medium uppercase tracking-wider">% Realisasi Kumulatif</p>
                    <h3 class="text-xl font-bold text-teal-400 mt-2"><?= number_format($output_data['scoreboard']->persen_realisasi_kumulatif ?? 0, 2, ',', '.') ?>%</h3>
                </div>
                <div class="p-3 bg-teal-400/10 rounded-xl text-teal-400 group-hover:bg-teal-400 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-percent text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700/50 shadow-lg backdrop-blur-sm group hover:border-orange-500/30 transition-all cursor-default">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-400 text-[10px] font-medium uppercase tracking-wider">Indeks Capaian</p>
                    <h3 class="text-xl font-bold text-orange-400 mt-2"><?= number_format($output_data['scoreboard']->indeks_capaian ?? 0, 2, ',', '.') ?>%</h3>
                </div>
                <div class="p-3 bg-orange-500/10 rounded-xl text-orange-400 group-hover:bg-orange-500 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-gauge-high text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700/50 shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-white font-semibold">Tren Realisasi Output Bulanan</h3>
            </div>
            <div id="chartOutputBulanan" class="w-full h-[380px]"></div>
        </div>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700/50 shadow-lg">
            <h3 class="text-white font-semibold text-center mb-4">Kategori Capaian</h3>
            <div id="chartKategoriCapaian" class="w-full h-[300px]"></div>
        </div>

        <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700/50 shadow-lg">
            <h3 class="text-white font-semibold text-center mb-4">Kategori Jenis Belanja</h3>
            <div id="chartKategoriBelanja" class="w-full h-[300px]"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700/50 shadow-lg">
            <h3 class="text-white font-semibold text-center mb-4">Peringkat Terendah</h3>
            <div id="chartPeringkatTerendah" class="w-full h-[300px]"></div>
        </div>

        <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700/50 shadow-lg">
            <h3 class="text-white font-semibold text-center mb-4">Peringkat Tertinggi</h3>
            <div id="chartPeringkatTertinggi" class="w-full h-[300px]"></div>
        </div>
    </div>

    <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700/50 shadow-lg">
        <h3 class="text-white font-semibold mb-4">Trend Realisasi Program/Kegiatan</h3>
        <div id="chartTrendRealisasi" class="w-full h-[350px]"></div>
    </div>

    

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    window.CapaianOutputData = {
        chart_bar: <?= $output_data['chart_bar'] ?>,
        rank_low: <?= $output_data['rank_low'] ?>,
        rank_high: <?= $output_data['rank_high'] ?>,
        chart_trend: <?= $output_data['chart_trend'] ?>,
        chart_kat: <?= $output_data['chart_kat'] ?>,
        chart_bel: <?= $output_data['chart_bel'] ?>,
        scoreboard_indeks: <?= $output_data['scoreboard']->indeks_capaian ?? 0 ?>
    };
</script>
<script src="<?= base_url('assets/js/capaian_output/index.js') ?>"></script>
<?= $this->endSection() ?>