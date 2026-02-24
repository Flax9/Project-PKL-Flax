/**
 * Render Bar Chart untuk Target vs Realisasi per IKU
 */
window.initBarIKUChart = function (canvasId, data) {
    const ctx = document.getElementById(canvasId).getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(row => row.no),
            datasets: [
                {
                    label: 'Target',
                    data: data.map(row => row.target),
                    backgroundColor: '#2e5091',
                    borderRadius: 4
                },
                {
                    label: 'Realisasi',
                    data: data.map(row => row.realisasi),
                    backgroundColor: '#f5a65b',
                    borderRadius: 4
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
                x: {
                    ticks: {
                        color: '#64748b',
                        font: { size: 10 },
                        maxRotation: 45,
                        minRotation: 45
                    },
                    grid: {
                        color: 'rgba(100, 116, 139, 0.2)'
                    }
                },
                y: {
                    ticks: { color: '#64748b' },
                    grid: { color: 'rgba(100, 116, 139, 0.2)' }
                }
            }
        }
    });
}