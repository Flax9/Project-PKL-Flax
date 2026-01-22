<div class="glass-card p-6">
    <h3 class="font-semibold text-white mb-6">Top 5 Indikator Terbaik</h3>
    <div class="space-y-4">
        <?php 
        // Decode JSON data yang dikirim dari Controller
        $topData = json_decode($rank_high);
        
        if(!empty($topData)): 
            foreach($topData as $rank): 
        ?>
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-slate-300 truncate max-w-[250px]" title="<?= $rank->nama ?>">
                        <?= $rank->nama ?>
                    </span>
                    <span class="text-teal-400 font-bold"><?= number_format($rank->nilai, 2) ?>%</span>
                </div>
                <div class="w-full bg-slate-700/50 rounded-full h-2">
                    <div class="bg-gradient-to-r from-teal-500 to-emerald-400 h-2 rounded-full" style="width: <?= min($rank->nilai, 100) ?>%"></div>
                </div>
            </div>
        <?php 
            endforeach; 
        else:
        ?>
            <div class="text-slate-500 text-xs text-center py-4">Belum ada data tersedia</div>
        <?php endif; ?>
    </div>
</div>