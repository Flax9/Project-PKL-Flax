window.initDonutChart = function(canvasId, dataJson, legendId) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    
    const labels = dataJson.map(item => item.label);
    const values = dataJson.map(item => parseInt(item.jumlah)); // Konversi ke Integer
    const colors = labels.map(label => window.ChartColors.bulan[label] || '#64748b');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{ data: values, backgroundColor: colors, borderWidth: 0 }]
        },
        options: { cutout: '75%', plugins: { legend: { display: false } }, maintainAspectRatio: false }
    });

    const legendDiv = document.getElementById(legendId);
    const total = values.reduce((a, b) => a + b, 0);
    
    if (legendDiv) {
        legendDiv.innerHTML = '';
        dataJson.forEach((item, index) => {
            const percentage = ((parseInt(item.jumlah) / total) * 100).toFixed(1);
            legendDiv.innerHTML += `
                <div class="flex justify-between items-center text-[10px]">
                    <span class="flex items-center gap-2 text-slate-300">
                        <span class="w-2 h-2 rounded-full" style="background-color: ${colors[index]}"></span> 
                        ${item.label}
                    </span>
                    <span class="font-bold text-white">${percentage}%</span>
                </div>`;
        });
    }
}