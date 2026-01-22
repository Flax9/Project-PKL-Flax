<?= $this->extend('layout/main') ?> <?= $this->section('content') ?>

<script>
    // Menyediakan warna yang diminta oleh index.js (window.ChartColors)
    window.ChartColors = {
        rank: {
            tinggi: '#3b82f6', // blue
            rendah: '#ef4444'  // red
        }
    };

    // Menyediakan Manager yang diminta oleh DashboardDataManager.getData()
    const DashboardDataManager = {
        data: {
            summary: <?= json_encode($summary) ?>,
            trend: <?= $grafik_trend_gabungan ?>,
            barIKU: <?= $grafik_bar ?>,
            katBulan: <?= $grafik_kat_bulan ?>,
            katTahun: <?= $grafik_kat_tahun ?>,
            rendah: <?= $grafik_rendah ?>,
            tinggi: <?= $grafik_tinggi ?>,
            topFive: <?= $rank_high ?>
        },
        getData: function() {
            return this.data;
        }
    };
</script>

<?= $this->include('dashboard/partials/header') ?>

<div class="flex-1 overflow-y-auto p-8 z-10 flex flex-col gap-4">
    
    <?= $this->include('dashboard/partials/summary_cards') ?>
    
    <?= $this->include('dashboard/partials/charts_trend') ?>
    
    <?= $this->include('dashboard/partials/charts_donut') ?>
    
    <?= $this->include('dashboard/partials/charts_rank') ?>

    <?= $this->include('dashboard/partials/table_realtime') ?>
</div>

<script src="<?= base_url('assets/js/dashboard/constants.js') ?>"></script>

<script src="<?= base_url('assets/js/dashboard/charts/trendChart.js') ?>"></script>
<script src="<?= base_url('assets/js/dashboard/charts/barIKU.js') ?>"></script>
<script src="<?= base_url('assets/js/dashboard/charts/donutChart.js') ?>"></script>
<script src="<?= base_url('assets/js/dashboard/charts/rankChart.js') ?>"></script>

<script src="<?= base_url('assets/js/dashboard/index.js') ?>"></script>

<?= $this->endSection() ?>