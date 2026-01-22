/**
 * Render Horizontal Bar Chart untuk Ranking
 */
window.initRankChart = function(canvasId, dataJson, barColor) {
    const ctx = document.getElementById(canvasId).getContext('2d');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dataJson.map(row => row.no),
            datasets: [{
                data: dataJson.map(row => row.nilai),
                backgroundColor: barColor,
                borderRadius: 5
            }]
        },
        options: {
            indexAxis: 'y', // Membuat bar menjadi horizontal
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: '#94a3b8' }, grid: { color: '#1e293b' } },
                y: { ticks: { color: '#94a3b8' }, grid: { display: false } }
            }
        }
    });
}