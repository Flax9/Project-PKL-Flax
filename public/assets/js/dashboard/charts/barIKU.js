/**
 * Render Bar Chart untuk Target vs Realisasi per IKU
 */
window.initBarIKUChart = function(canvasId, data) {
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
            scales: { 
                x: { 
                    ticks: { 
                        color: '#94a3b8', 
                        font: { size: 10 }, 
                        maxRotation: 45, 
                        minRotation: 45 
                    } 
                } 
            } 
        }
    });
}