<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    
    <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700/50 shadow-lg backdrop-blur-sm group hover:border-teal-500/30 transition-all cursor-default relative overflow-hidden">
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-slate-400 text-[10px] font-medium uppercase tracking-wider">Indeks Capaian Bulan</p>
                <h3 class="text-xl font-bold text-white mt-2">
                    <?= number_format($summary->avg_bulan, 2, ',', '.') ?><span class="text-sm text-teal-400 ml-1">%</span>
                </h3>
            </div>
            <div class="p-3 bg-teal-500/10 rounded-xl text-teal-400 group-hover:bg-teal-500 group-hover:text-white transition-colors">
                <i class="fa-solid fa-chart-pie text-lg"></i>
            </div>
        </div>
    </div>

    <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700/50 shadow-lg backdrop-blur-sm group hover:border-blue-500/30 transition-all cursor-default relative overflow-hidden">
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-slate-400 text-[10px] font-medium uppercase tracking-wider">Nilai Kinerja Organisasi (NKO)</p>
                <?php if ($bulanDipilih): ?>
                    <h3 class="text-xl font-bold text-white mt-2">
                        <?= number_format($summary->nko, 2, ',', '.') ?>
                    </h3>
                <?php else: ?>
                    <p class="text-sm text-slate-500 mt-3 italic">Silahkan memilih bulan terlebih dahulu</p>
                <?php endif; ?>
            </div>
            <div class="p-3 bg-blue-500/10 rounded-xl text-blue-400 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                <i class="fa-solid fa-chart-line text-lg"></i>
            </div>
        </div>
    </div>

    <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700/50 shadow-lg backdrop-blur-sm group hover:border-purple-500/30 transition-all cursor-default relative overflow-hidden">
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-slate-400 text-[10px] font-medium uppercase tracking-wider">Indeks Capaian Tahun</p>
                <h3 class="text-xl font-bold text-white mt-2"><?= number_format($summary->avg_tahun, 2, ',', '.') ?>%</h3>
                <p class="text-[10px] text-slate-500 mt-1 italic">Akumulasi Tahunan</p>
            </div>
            <div class="p-3 bg-purple-500/10 rounded-xl text-purple-400 group-hover:bg-purple-500 group-hover:text-white transition-colors">
                <i class="fa-solid fa-calendar-check text-lg"></i>
            </div>
        </div>
    </div>

    <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700/50 shadow-lg backdrop-blur-sm group hover:border-rose-500/30 transition-all cursor-default relative overflow-hidden">
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-slate-400 text-[10px] font-medium uppercase tracking-wider">IKU Atensi</p>
                <h3 class="text-xl font-bold text-white mt-2">2</h3>
                <p class="text-[10px] text-slate-500 mt-1 italic">Perlu Perhatian Khusus</p>
            </div>
            <div class="p-3 bg-rose-500/10 rounded-xl text-rose-400 group-hover:bg-rose-500 group-hover:text-white transition-colors">
                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
            </div>
        </div>
    </div>

</div>