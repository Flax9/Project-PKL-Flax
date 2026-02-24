/**
 * Render Horizontal Bar Chart untuk Ranking (menggunakan ApexCharts)
 */
window.initRankChart = function (elementId, dataJson, barColor) {
    const el = document.getElementById(elementId);
    if (!el) return;

    // Bersihkan kontainer jika dipanggil ulang
    el.innerHTML = '';

    const options = {
        chart: {
            type: 'bar',
            height: '100%',
            background: 'transparent',
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                horizontal: true,
                barHeight: '60%',
                borderRadius: 4,
                dataLabels: { position: 'top' }
            }
        },
        series: [{
            name: 'Realisasi',
            data: dataJson.map(row => parseFloat(row.nilai))
        }],
        colors: [barColor],
        dataLabels: {
            enabled: true,
            formatter: (val) => (Math.round(val * 100) / 100) + "%",
            offsetX: -18,
            style: {
                fontSize: '11px',
                colors: ['#ffffff']
            }
        },
        xaxis: {
            categories: dataJson.map(row => row.no),
            labels: { show: false },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#64748b'
                }
            }
        },
        grid: {
            show: false,
            padding: { top: 0, right: 0, bottom: 10, left: 0 }
        },
        tooltip: { theme: 'dark' }
    };

    const chart = new ApexCharts(el, options);
    chart.render();
}