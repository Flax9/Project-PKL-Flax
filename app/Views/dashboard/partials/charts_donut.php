<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="glass-card p-6">
        <h3 class="font-semibold text-white mb-2 text-center">Kategori Capaian Bulan</h3>
        <div class="relative h-56 flex justify-center">
            <canvas id="chartBulan"></canvas> 
            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                <span class="text-3xl font-bold text-white"><?= $total_iku ?></span>
                <span class="text-xs text-slate-500">Total IKU</span>
            </div>
        </div>
        <div id="legendBulan" class="mt-6 grid grid-cols-2 gap-3"></div>
    </div>

    <div class="glass-card p-6">
        <h3 class="font-semibold text-white mb-2 text-center">Kategori Capaian Tahun</h3>
        <div class="relative h-56 flex justify-center">
            <canvas id="chartTahun"></canvas> 
            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                <span class="text-3xl font-bold text-white"><?= $total_iku ?></span>
                <span class="text-xs text-slate-500">Total IKU</span>
            </div>
        </div>
        <div id="legendTahun" class="mt-6 grid grid-cols-2 gap-3"></div>
    </div>
</div>