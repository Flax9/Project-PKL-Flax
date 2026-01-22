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
    // 1. DATA DARI PHP
    const rawData = <?= $output_data['chart_bar'] ?>;
    const dataLow = <?= $output_data['rank_low'] ?>;
    const dataHigh = <?= $output_data['rank_high'] ?>;
    const trendRaw = <?= $output_data['chart_trend'] ?>;
    const dataKatCapaian = <?= $output_data['chart_kat'] ?>;
    const dataKatBelanja = <?= $output_data['chart_bel'] ?>;

    // 2. CONFIG: 100% STACKED BAR
    const optionsBar = {
        series: [
            { name: 'Target', data: rawData.map(item => item.target) },
            { name: 'Realisasi', data: rawData.map(item => item.realisasi) }
        ],
        chart: {
            type: 'bar', height: 380, stacked: true, stackType: '100%', background: 'transparent',
            toolbar: {
                show: true,
                tools: {
                    download: false, selection: false, zoom: false, zoomin: false, zoomout: false, pan: true,
                    reset: '<i class="fa-solid fa-rotate-right" style="font-size: 16px; color: #94a3b8;"></i>'
                },
                autoSelected: 'pan'
            }
        },
        plotOptions: { bar: { horizontal: false, columnWidth: '60%', borderRadius: 2 } },
        colors: ['#1e3a8a', '#f59e0b'],
        dataLabels: { enabled: true, style: { fontSize: '10px', colors: ['#fff'] }, formatter: (val) => Math.round(val) + '%' },
        xaxis: {
            categories: rawData.map(item => 'RO ' + item.no),
            min: 1, max: 8,
            labels: { style: { colors: '#94a3b8', fontSize: '11px' } },
            tickPlacement: 'on'
        },
        yaxis: { max: 100, labels: { formatter: (val) => val + "%", style: { colors: '#94a3b8' } } },
        legend: {
            show: true, position: 'bottom', labels: { colors: '#f8fafc' },
            markers: { width: 12, height: 12, shape: 'circle', radius: 12, offsetX: -5 }
        }
    };
    new ApexCharts(document.querySelector("#chartOutputBulanan"), optionsBar).render();

   /* // 3. CONFIG: STATUS DONUT (INDEKS)
    const optionsStatus = {
        series: [<?= $output_data['scoreboard']->indeks_capaian ?? 0 ?>, 100 - <?= $output_data['scoreboard']->indeks_capaian ?? 0 ?>],
        labels: ['Tercapai', 'Belum Tercapai'],
        chart: { type: 'donut', height: 320, background: 'transparent' },
        colors: ['#14b8a6', '#ef4444'],
        plotOptions: {
            pie: {
                donut: {
                    size: '75%',
                    labels: {
                        show: true,
                        value: { color: '#ffffff', fontSize: '20px', fontWeight: 'bold' },
                        total: { show: true, label: 'Indeks', color: '#94a3b8', formatter: (w) => w.globals.seriesTotals[0] + '%' }
                    }
                }
            }
        },
        stroke: { show: false },
        legend: { position: 'bottom', labels: { colors: '#94a3b8' }, markers: { radius: 12, shape: 'circle' } }
    };
    new ApexCharts(document.querySelector("#chartStatusOutput"), optionsStatus).render();*/

    // 4. CONFIG: HORIZONTAL RANKING
    const horizontalOptions = {
        chart: { type: 'bar', height: 300, background: 'transparent', toolbar: { show: false } },
        plotOptions: { bar: { horizontal: true, barHeight: '60%', borderRadius: 4, dataLabels: { position: 'right' } } },
        dataLabels: { enabled: true, formatter: (val) => val + "%", offsetX: 10, style: { fontSize: '11px', colors: ['#fff'] } },
        xaxis: { labels: { show: false }, axisBorder: { show: false }, axisTicks: { show: false } },
        yaxis: { labels: { style: { colors: '#94a3b8' } } },
        grid: { show: false },
        tooltip: { theme: 'dark' }
    };

    new ApexCharts(document.querySelector("#chartPeringkatTerendah"), {
        ...horizontalOptions,
        series: [{ name: 'Capaian', data: dataLow.map(d => parseFloat(d.nilai)) }],
        xaxis: { ...horizontalOptions.xaxis, categories: dataLow.map(d => 'RO ' + d.no) },
        colors: ['#ef4444']
    }).render();

    new ApexCharts(document.querySelector("#chartPeringkatTertinggi"), {
        ...horizontalOptions,
        series: [{ name: 'Capaian', data: dataHigh.map(d => parseFloat(d.nilai)) }],
        xaxis: { ...horizontalOptions.xaxis, categories: dataHigh.map(d => 'RO ' + d.no) },
        colors: ['#1e3a8a']
    }).render();

    // 5. CONFIG: TREND AREA CHART
    const optionsTrend = {
        series: [{ name: 'Realisasi Kumulatif', data: trendRaw.map(item => parseFloat(item.kumulatif)) }],
        chart: { type: 'area', height: 350, background: 'transparent', toolbar: { show: false } },
        colors: ['#ef4444'],
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.2, stops: [0, 90, 100] } },
        stroke: { curve: 'smooth', width: 3 },
        xaxis: { categories: trendRaw.map(item => item.Bulan), labels: { style: { colors: '#94a3b8' } } },
        yaxis: { labels: { style: { colors: '#94a3b8' }, formatter: (val) => val.toLocaleString('id-ID') } },
        grid: { borderColor: '#334155', strokeDashArray: 4 },
        tooltip: { theme: 'dark' }
    };
    new ApexCharts(document.querySelector("#chartTrendRealisasi"), optionsTrend).render();

    // 6. CONFIG: CATEGORY CHARTS (Donut & Pie) - REVISI ANTI-ERROR
       const donutTemplateOptions = {
        chart: {
            type: 'donut',
            height: 320, // Menyesuaikan tinggi template
            background: 'transparent',
            animations: { enabled: true }
        },
        stroke: { 
            show: true,
            width: 2,
            colors: ['#0f172a'] // Memberi jarak antar slice seperti template
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '75%', // Ketebalan ring sesuai template
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '14px',
                            color: '#94a3b8',
                            offsetY: -10
                        },
                        value: {
                            show: true,
                            fontSize: '22px',
                            fontWeight: 'bold',
                            color: '#ffffff',
                            offsetY: 10,
                            formatter: (val) => val // Menampilkan angka riil
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            color: '#94a3b8',
                            formatter: (w) => w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: true,
            style: {
                fontSize: '11px',
                fontWeight: 'bold'
            },
            dropShadow: { enabled: false }
        },
        legend: {
            show: true,
            position: 'bottom',
            horizontalAlign: 'center',
            fontSize: '12px',
            labels: { colors: '#94a3b8' },
            markers: {
                width: 12,
                height: 12,
                radius: 12,
                shape: 'circle',
                offsetX: -5
            },
            itemMargin: { horizontal: 10, vertical: 5 }
        },
        tooltip: { theme: 'dark' }
    };

    // --- Render Chart 1: Kategori Capaian ---
    if (document.querySelector("#chartKategoriCapaian") && dataKatCapaian.length > 0) {
        new ApexCharts(document.querySelector("#chartKategoriCapaian"), {
            ...donutTemplateOptions,
            series: dataKatCapaian.map(d => parseInt(d.jumlah) || 0),
            labels: dataKatCapaian.map(d => d.label || "N/A"),
            colors: ['#14b8a6', '#ef4444', '#f59e0b', '#3b82f6'] // Skema warna template
        }).render();
    }

    // --- Render Chart 2: Kategori Jenis Belanja ---
    if (document.querySelector("#chartKategoriBelanja") && dataKatBelanja.length > 0) {
        new ApexCharts(document.querySelector("#chartKategoriBelanja"), {
            ...donutTemplateOptions,
            series: dataKatBelanja.map(d => parseInt(d.jumlah) || 0),
            labels: dataKatBelanja.map(d => d.label || "N/A"),
            colors: ['#f59e0b', '#ef4444', '#14b8a6', '#3b82f6']
        }).render();
    }
</script>
<?= $this->endSection() ?>