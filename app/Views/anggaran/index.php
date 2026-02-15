<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="flex-1 flex flex-col overflow-hidden relative">
    
    <?= $this->include('dashboard/partials/header') ?>

    <main class="flex-1 overflow-x-hidden overflow-y-auto p-8 custom-scrollbar">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700/50 shadow-lg backdrop-blur-sm group hover:border-blue-500/30 transition-all cursor-default">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-slate-400 text-[10px] font-medium uppercase tracking-wider">Total Pagu</p>
                        <h3 class="text-xl font-bold text-white mt-2">
                            Rp <?= number_format($anggaran_data['scoreboard']->total_pagu ?? 0, 0, ',', '.') ?>
                        </h3>
                    </div>
                    <div class="p-3 bg-blue-500/10 rounded-xl text-blue-400 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-wallet text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700/50 shadow-lg backdrop-blur-sm group hover:border-teal-500/30 transition-all cursor-default">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-slate-400 text-[10px] font-medium uppercase tracking-wider">Total Realisasi</p>
                        <h3 class="text-xl font-bold text-teal-400 mt-2">
                            Rp <?= number_format($anggaran_data['scoreboard']->total_realisasi ?? 0, 0, ',', '.') ?>
                        </h3>
                    </div>
                    <div class="p-3 bg-teal-500/10 rounded-xl text-teal-400 group-hover:bg-teal-500 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-money-bill-transfer text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700/50 shadow-lg backdrop-blur-sm group hover:border-orange-500/30 transition-all cursor-default">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-slate-400 text-[10px] font-medium uppercase tracking-wider">Sisa Anggaran</p>
                        <h3 class="text-xl font-bold text-orange-400 mt-2">
                            Rp <?= number_format($anggaran_data['scoreboard']->sisa_anggaran ?? 0, 0, ',', '.') ?>
                        </h3>
                    </div>
                    <div class="p-3 bg-orange-500/10 rounded-xl text-orange-400 group-hover:bg-orange-500 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-scale-balanced text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700/50 shadow-lg backdrop-blur-sm group hover:border-purple-500/30 transition-all cursor-default">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-slate-400 text-[10px] font-medium uppercase tracking-wider">% Serapan</p>
                        <h3 class="text-xl font-bold text-purple-400 mt-2">
                            <?= number_format($anggaran_data['scoreboard']->persentase_serapan ?? 0, 2, ',', '.') ?>%
                        </h3>
                    </div>
                    <div class="p-3 bg-purple-500/10 rounded-xl text-purple-400 group-hover:bg-purple-500 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-percent text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

       <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
    
            <div class="col-span-full bg-slate-800/50 p-6 rounded-2xl border border-slate-700/50 shadow-lg backdrop-blur-sm group hover:border-amber-500/30 transition-all cursor-default">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-slate-400 text-[10px] font-medium uppercase tracking-wider">Target per Triwulan</p>
                        <h3 class="text-xl font-bold text-amber-400 mt-2">
                            <?= number_format($summary->avg_target_tw ?? 0, 2, ',', '.') ?>%
                        </h3>
                        <p class="text-[10px] text-slate-500 mt-1 italic">Capaian Target Triwulan</p>
                    </div>
                    
                    <div class="p-3 bg-amber-500/10 rounded-xl text-amber-400 group-hover:bg-amber-500 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-bullseye text-lg"></i>
                    </div>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <div class="bg-slate-800/50 border border-slate-700/50 rounded-2xl shadow-lg flex flex-col h-[500px] overflow-hidden">
                <div class="p-6 pb-2 border-b border-slate-700/50">
                    <h4 class="text-white font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-chart-bar text-teal-400"></i>
                        Pagu vs Realisasi per Program
                    </h4>
                    <div class="flex gap-4 mt-2">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-blue-500 border-2 border-white/20"></span>
                            <span class="text-[10px] font-medium text-slate-400 uppercase">Pagu</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-teal-500 border-2 border-white/20"></span>
                            <span class="text-[10px] font-medium text-slate-400 uppercase">Realisasi</span>
                        </div>
                    </div>
                </div>
                <div class="flex-1 overflow-y-auto custom-scrollbar p-2">
                    <div id="chartProgram"></div>
                </div>
            </div>

            <div class="bg-slate-800/50 border border-slate-700/50 p-6 rounded-2xl shadow-lg">
                <h4 class="text-white font-semibold mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-chart-line text-blue-400"></i>
                    Tren Penyerapan Bulanan (<?= $tahun_label ?>)
                </h4>
                <div id="chartTrend" class="w-full h-[380px]"></div>
            </div>
        </div>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    window.AnggaranData = {
        chart_bar: <?= $anggaran_data['chart_bar'] ?>,
        chart_trend: <?= $anggaran_data['chart_trend'] ?>
    };
</script>
<script src="<?= base_url('assets/js/anggaran/index.js') ?>"></script>
<?= $this->endSection() ?>