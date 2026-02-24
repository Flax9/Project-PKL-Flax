window.initDonutChart = function (elementId, dataJson, legendIdPlaceholder, absoluteTotal = null) {
    const el = document.getElementById(elementId);
    if (!el) return;

    el.innerHTML = ''; // bersihkan apabila dipanggil ulang

    const labels = dataJson.map(item => item.label || 'Belum Ada Data');
    const values = dataJson.map(item => parseInt(item.jumlah) || 0);

    // Pemetaan warna berdasarkan labels dari constants.js
    const colors = labels.map(label => window.ChartColors.bulan[label] || '#64748b');
    // Saring data null, kosong, atau #DIV/0!
    const validData = [];
    for (let i = 0; i < labels.length; i++) {
        const lbl = labels[i];
        if (lbl && lbl.trim() !== '' && lbl !== 'null' && lbl !== '#DIV/0!' && lbl !== 'Belum Ada Data') {
            validData.push({
                label: lbl,
                value: values[i],
                color: colors[i]
            });
        }
    }

    const filteredLabels = validData.map(d => d.label);
    const filteredValues = validData.map(d => d.value);
    const filteredColors = validData.map(d => d.color);

    // Jika data kosong setelah disaring, tampilkan chart "kosong" agar tidak hilang
    const chartSeries = filteredValues.length > 0 ? filteredValues : [1];
    const chartLabels = filteredLabels.length > 0 ? filteredLabels : ['Data Kosong'];
    const chartColors = filteredColors.length > 0 ? filteredColors : ['#e2e8f0'];

    const options = {
        chart: {
            type: 'donut',
            height: 320,
            background: 'transparent',
            animations: { enabled: true }
        },
        series: chartSeries,
        labels: chartLabels,
        colors: chartColors,
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '75%',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '14px',
                            color: '#64748b',
                            offsetY: -10
                        },
                        value: {
                            show: true,
                            fontSize: '22px',
                            fontWeight: 'bold',
                            color: '#64748b',
                            offsetY: 10,
                            formatter: (val) => val
                        },
                        total: {
                            show: true,
                            label: 'Total IKU',
                            color: '#64748b',
                            formatter: (w) => (absoluteTotal !== null) ? absoluteTotal : w.globals.seriesTotals.reduce((a, b) => a + b, 0)
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
            show: false // Dimatikan karena menggunakan legend kustom HTML di bawah
        },
        tooltip: { theme: 'dark' }
    };

    const chart = new ApexCharts(el, options);
    chart.render();

    // Custom HTML Legend Builder (seperti desain Chart.js sebelumnya)
    const legendDiv = document.getElementById(legendIdPlaceholder);
    if (!legendDiv) return;
    legendDiv.innerHTML = '';

    if (filteredValues.length > 0) {
        const total = filteredValues.reduce((a, b) => a + b, 0);
        validData.forEach((item) => {
            const percentage = ((item.value / total) * 100).toFixed(1);
            legendDiv.innerHTML += `
                <div class="flex justify-between items-center text-xs">
                    <span class="flex items-center gap-3 text-slate-400 dark:text-slate-300 transition-colors">
                        <span class="w-2.5 h-2.5 rounded-full" style="background-color: ${item.color}"></span> 
                        ${item.label}
                    </span>
                    <span class="font-bold text-slate-800 dark:text-white transition-colors">${percentage}%</span>
                </div>`;
        });
    }
}