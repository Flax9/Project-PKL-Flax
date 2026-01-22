/**
 * Dashboard Main Logic (IKU & RO)
 */
document.addEventListener('DOMContentLoaded', function() {
    // 1. Inisialisasi Data dari Manager
    const data = DashboardDataManager.getData();
    if (!data) {
        console.warn("Data dashboard tidak ditemukan.");
        return;
    }

    // 2. Inisialisasi Grafik
    const charts = [
        { id: 'trendChartGabungan', init: () => initTrendChart('trendChartGabungan', data.trend) },
        { id: 'barChartIKU', init: () => initBarIKUChart('barChartIKU', data.barIKU) },
        { id: 'chartBulan', init: () => initDonutChart('chartBulan', data.katBulan, 'legendBulan') },
        { id: 'chartTahun', init: () => initDonutChart('chartTahun', data.katTahun, 'legendTahun') },
        { id: 'chartTinggi', init: () => initRankChart('chartTinggi', data.tinggi, window.ChartColors.rank.tinggi) },
        { id: 'chartRendah', init: () => initRankChart('chartRendah', data.rendah, window.ChartColors.rank.rendah) }
    ];

    charts.forEach(chart => {
        const el = document.getElementById(chart.id);
        if (el) chart.init();
    });

    // 3. Logika Filter Dinamis IKU
    const ikuFilterIds = ['filterIndikator', 'filterBulan', 'filterTahun'];
    ikuFilterIds.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('change', function() {
                const vIndikator = document.getElementById('filterIndikator')?.value || '';
                const vBulan = document.getElementById('filterBulan')?.value || '';
                const vTahun = document.getElementById('filterTahun')?.value || '';

                let url = new URL(window.location.href);
                vIndikator ? url.searchParams.set('indikator', vIndikator) : url.searchParams.delete('indikator');
                vBulan ? url.searchParams.set('bulan', vBulan) : url.searchParams.delete('bulan');
                vTahun ? url.searchParams.set('tahun', vTahun) : url.searchParams.delete('tahun');
                window.location.href = url.href;
            });
        }
    });

    // 4. Logika Filter Dinamis RO (Koreksi Null Check)
    const roFilterIds = ['filterRo', 'filterFungsi', 'filterBulanRo'];
    roFilterIds.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('change', function() {
                // Gunakan optional chaining (?.) agar tidak error jika elemen tidak ada
                const vRo = document.getElementById('filterRo')?.value || '';
                const vFungsi = document.getElementById('filterFungsi')?.value || '';
                const vBulan = document.getElementById('filterBulanRo')?.value || '';

                let url = new URL(window.location.href);
                vRo ? url.searchParams.set('keterangan_ro', vRo) : url.searchParams.delete('keterangan_ro');
                vFungsi ? url.searchParams.set('fungsi', vFungsi) : url.searchParams.delete('fungsi');
                vBulan ? url.searchParams.set('bulan', vBulan) : url.searchParams.delete('bulan');
                window.location.href = url.href;
            });
        }
    });

    // 5. Sidebar & Export (Global)
    const sidebarLinks = document.querySelectorAll('aside nav a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            sidebarLinks.forEach(l => l.classList.remove('bg-slate-800', 'text-teal-400'));
            this.classList.add('bg-slate-800', 'text-teal-400');
        });
    });

    const btnExport = document.getElementById('btnExport') || document.getElementById('btnExportRo');
    if (btnExport) {
        btnExport.addEventListener('click', (e) => {
            e.preventDefault();
            window.print();
        });
    }
});