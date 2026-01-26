document.addEventListener('DOMContentLoaded', function () {
    if (!window.CapaianOutputData) return;

    const { chart_bar, rank_low, rank_high, chart_trend, chart_kat, chart_bel } = window.CapaianOutputData;

    // 1. DATA DARI PHP
    const rawData = chart_bar || [];
    const dataLow = rank_low || [];
    const dataHigh = rank_high || [];
    const trendRaw = chart_trend || [];
    const dataKatCapaian = chart_kat || [];
    const dataKatBelanja = chart_bel || [];

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
    if (document.querySelector("#chartOutputBulanan")) {
        new ApexCharts(document.querySelector("#chartOutputBulanan"), optionsBar).render();
    }

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

    if (document.querySelector("#chartPeringkatTerendah")) {
        new ApexCharts(document.querySelector("#chartPeringkatTerendah"), {
            ...horizontalOptions,
            series: [{ name: 'Capaian', data: dataLow.map(d => parseFloat(d.nilai)) }],
            xaxis: { ...horizontalOptions.xaxis, categories: dataLow.map(d => 'RO ' + d.no) },
            colors: ['#ef4444']
        }).render();
    }

    if (document.querySelector("#chartPeringkatTertinggi")) {
        new ApexCharts(document.querySelector("#chartPeringkatTertinggi"), {
            ...horizontalOptions,
            series: [{ name: 'Capaian', data: dataHigh.map(d => parseFloat(d.nilai)) }],
            xaxis: { ...horizontalOptions.xaxis, categories: dataHigh.map(d => 'RO ' + d.no) },
            colors: ['#1e3a8a']
        }).render();
    }

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
    if (document.querySelector("#chartTrendRealisasi")) {
        new ApexCharts(document.querySelector("#chartTrendRealisasi"), optionsTrend).render();
    }

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
});
