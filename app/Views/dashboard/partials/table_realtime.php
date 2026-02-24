<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="glass-card p-6">
        <h3 class="font-semibold text-slate-800 dark:text-white mb-6 transition-colors">Top 5 Indikator Terbaik</h3>
        <div class="space-y-4">
            <?php 
            $topData = json_decode($rank_high);
            foreach($topData as $rank): ?>
            <div>
                <div class="flex justify-between text-xs mb-1 relative group py-0.5 cursor-default">
                    <!-- Truncated Text -->
                    <span class="text-slate-600 dark:text-slate-300 truncate max-w-[230px] md:max-w-[250px] transition-colors relative z-10 pr-2">
                        <?= $rank->Bulan ?> - <?= $rank->nama ?>
                    </span>
                    
                    <!-- Custom Floating Tooltip -->
                    <div class="absolute bottom-full left-0 mb-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 w-max max-w-[480px] bg-slate-900/95 dark:bg-slate-800/95 text-white p-3 rounded-xl shadow-xl border border-slate-700/50 backdrop-blur-md">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1"><?= $rank->Bulan ?></div>
                        <div class="text-xs leading-relaxed font-medium">
                            <?= $rank->nama ?>
                        </div>
                        <div class="mt-2 text-teal-400 font-bold text-[13px] border-t border-slate-700/50 pt-1.5 flex justify-between items-center whitespace-nowrap">
                            <span>Realisasi Capaian:</span>
                            <span><?= number_format($rank->nilai, 2) ?>%</span>
                        </div>
                        <!-- Tooltip Arrow -->
                        <div class="absolute -bottom-1.5 left-6 w-3 h-3 bg-slate-900/95 dark:bg-slate-800/95 border-b border-r border-slate-700/50 rotate-45 transform pointer-events-none"></div>
                    </div>

                    <span class="text-teal-500 dark:text-teal-400 font-bold transition-colors relative z-10"><?= number_format($rank->nilai, 2) ?>%</span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-700/50 rounded-full h-2 transition-colors relative z-0">
                    <div class="bg-gradient-to-r from-teal-500 to-emerald-400 h-2 rounded-full" style="width: <?= min($rank->nilai, 100) ?>%"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="glass-card p-0 overflow-hidden">
        <div class="p-6 border-b border-slate-200 dark:border-slate-700/50 flex justify-between items-center transition-colors">
            <h3 class="font-semibold text-slate-800 dark:text-white transition-colors">Database IKU Realtime</h3>
            <a href="<?= base_url('dashboard/database') ?>" class="text-xs text-blue-500 dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 transition-colors">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 dark:text-slate-400 uppercase bg-slate-100 dark:bg-slate-800/50 transition-colors">
                    <tr>
                        <th class="px-6 py-3">No. IKU</th>
                        <th class="px-6 py-3 text-center">Target</th>
                        <th class="px-6 py-3 text-center">Realisasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50 transition-colors">
                    <?php 
                    $barData = json_decode($grafik_bar);
                    foreach(array_slice($barData, 0, 4) as $row): ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-800 dark:text-white transition-colors"><?= $row->no ?></td>
                        <td class="px-6 py-4 text-center"><?= number_format($row->target, 2) ?></td>
                        <td class="px-6 py-4 text-center font-bold text-teal-600 dark:text-teal-400 transition-colors"><?= number_format($row->realisasi, 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>