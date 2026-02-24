window.initTrendChart = function (canvasId, data) {
    const ctx = document.getElementById(canvasId).getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(row => row.Bulan),
            datasets: [
                {
                    label: 'Bulanan',
                    // Gunakan parseFloat untuk memastikan data adalah angka
                    data: data.map(row => parseFloat(row.rata_bulan)),
                    borderColor: window.ChartColors.trend.bulanan,
                    backgroundColor: window.ChartColors.trend.bgBulanan,
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Tahunan',
                    data: data.map(row => parseFloat(row.rata_tahun)),
                    borderColor: window.ChartColors.trend.tahunan,
                    backgroundColor: window.ChartColors.trend.bgTahunan,
                    fill: true,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { labels: { color: '#64748b' } }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: '#64748b' },
                    grid: { color: 'rgba(100, 116, 139, 0.2)' }
                },
                x: {
                    ticks: { color: '#64748b' },
                    grid: { color: 'rgba(100, 116, 139, 0.2)' }
                }
            }
        }
    });
}