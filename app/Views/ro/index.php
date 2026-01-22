<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
    <?= $this->include('ro/partials/header_ro') ?>

    <div class="flex-1 overflow-y-auto p-8 z-10">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
            
            <div class="glass-card p-4 border-b-2 border-blue-500">
                <p class="text-[10px] uppercase text-slate-400 font-semibold mb-1">Total Target</p>
                <h3 class="text-xl font-bold text-white"><?= number_format($summary->total_target ?? 0, 0, ',', '.') ?></h3>
            </div>

            <div class="glass-card p-4 border-b-2 border-blue-400">
                <p class="text-[10px] uppercase text-slate-400 font-semibold mb-1">Realisasi</p>
                <h3 class="text-xl font-bold text-white"><?= number_format($summary->total_realisasi ?? 0, 0, ',', '.') ?></h3>
            </div>

            <div class="glass-card p-4 border-b-2 border-indigo-500">
                <p class="text-[10px] uppercase text-slate-400 font-semibold mb-1">Realisasi Kumulatif</p>
                <h3 class="text-xl font-bold text-white"><?= number_format($summary->total_kumulatif ?? 0, 0, ',', '.') ?></h3>
            </div>

            <div class="glass-card p-4 border-b-2 border-teal-500">
                <p class="text-[10px] uppercase text-slate-400 font-semibold mb-1">% Realisasi Kumulatif</p>
                <h3 class="text-xl font-bold text-teal-400"><?= number_format($summary->persen_kumulatif ?? 0, 2) ?>%</h3>
            </div>

            <div class="glass-card p-4 border-b-2 border-purple-500 bg-gradient-to-br from-purple-500/5 to-transparent">
                <p class="text-[10px] uppercase text-slate-400 font-semibold mb-1">Indeks Capaian</p>
                <h3 class="text-xl font-bold text-white"><?= number_format($summary->indeks_capaian ?? 0, 2) ?>%</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="glass-card p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-tags text-teal-400 text-sm"></i>
                    <h4 class="text-sm font-bold text-white">Kategori Jenis Belanja</h4>
                </div>
                <div class="relative h-64">
                    <canvas id="chartBelanja"></canvas>
                </div>
            </div>

            <div class="glass-card p-6">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-chart-bar text-blue-400 text-sm"></i>
                    <h4 class="text-sm font-bold text-white">Peringkat RO Tertinggi</h4>
                </div>
                <div class="relative h-64">
                    <canvas id="chartRankRo"></canvas>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        window.RoData = {
            // Mengubah array PHP menjadi JSON agar terbaca oleh JavaScript
            belanja: <?= json_encode($grafik_belanja ?? []) ?>,
            ranking: <?= json_encode($grafik_rank ?? []) ?>
        };
    </script>
<?= $this->endSection() ?>