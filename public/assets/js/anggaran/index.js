document.addEventListener('DOMContentLoaded', function () {
    if (!window.AnggaranData) return;
    const { chart_bar, chart_trend } = window.AnggaranData;

    // 1. Chart Bar Program
    const dataBar = chart_bar || [];
    const optionsBar = {
        series: [
            { name: 'Pagu', data: dataBar.map(i => i.pagu) },
            { name: 'Realisasi', data: dataBar.map(i => i.realisasi) }
        ],
        chart: {
            type: 'bar',
            height: Math.max(400, dataBar.length * 45),
            toolbar: { show: false },
            background: 'transparent'
        },
        colors: ['#3b82f6', '#10b981'],
        plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: '70%' } },
        dataLabels: { enabled: false },
        legend: { show: false },
        xaxis: {
            categories: dataBar.map(i => i.program),
            position: 'top',
            labels: {
                style: { colors: '#94a3b8', fontSize: '10px' },
                formatter: (val) => val >= 1e9 ? (val / 1e9).toFixed(1) + " M" : val
            },
            axisBorder: { show: false }
        },
        yaxis: { labels: { maxWidth: 220, style: { colors: '#94a3b8', fontSize: '11px' } } },
        tooltip: { theme: 'dark', y: { formatter: (val) => "Rp " + val.toLocaleString('id-ID') } }
    };
    if (document.querySelector("#chartProgram")) {
        new ApexCharts(document.querySelector("#chartProgram"), optionsBar).render();
    }

    // 2. Chart Trend Bulanan (Gaya Area Smooth)
    const dataTrend = chart_trend || [];
    const optionsTrend = {
        series: [{ name: 'Realisasi', data: dataTrend.map(i => i.realisasi) }],
        chart: { type: 'area', height: 350, toolbar: { show: false }, background: 'transparent' },
        colors: ['#3b82f6'],

        // MENYEMBUNYIKAN KOTAK NILAI
        dataLabels: {
            enabled: false
        },

        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1 } },
        stroke: { curve: 'smooth', width: 3 },
        grid: { borderColor: '#1e293b', strokeDashArray: 4 },
        xaxis: {
            categories: dataTrend.map(i => i.Bulan),
            labels: { style: { colors: '#94a3b8', fontSize: '10px' } }
        },
        yaxis: {
            labels: {
                style: { colors: '#94a3b8' },
                formatter: (v) => (v / 1e6).toFixed(0) + "jt"
            }
        },
        tooltip: { theme: 'dark' }
    };
    if (document.querySelector("#chartTrend")) {
        new ApexCharts(document.querySelector("#chartTrend"), optionsTrend).render();
    }
});
