<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="glass-card p-6">
        <h3 class="font-semibold text-white mb-6">Top 5 Indikator Terbaik</h3>
        <div class="space-y-4">
            <?php 
            $topData = json_decode($rank_high);
            foreach($topData as $rank): ?>
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-slate-300 truncate max-w-[250px]"><?= $rank->nama ?></span>
                    <span class="text-teal-400 font-bold"><?= number_format($rank->nilai, 2) ?>%</span>
                </div>
                <div class="w-full bg-slate-700/50 rounded-full h-2">
                    <div class="bg-gradient-to-r from-teal-500 to-emerald-400 h-2 rounded-full" style="width: <?= min($rank->nilai, 100) ?>%"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="glass-card p-0 overflow-hidden">
        <div class="p-6 border-b border-slate-700/50 flex justify-between items-center">
            <h3 class="font-semibold text-white">Database Realtime</h3>
            <a href="<?= base_url('dashboard/database') ?>" class="text-xs text-blue-400 hover:text-blue-300">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-400 uppercase bg-slate-800/50">
                    <tr>
                        <th class="px-6 py-3">No. IKU</th>
                        <th class="px-6 py-3 text-center">Target</th>
                        <th class="px-6 py-3 text-center">Realisasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    <?php 
                    $barData = json_decode($grafik_bar);
                    foreach(array_slice($barData, 0, 4) as $row): ?>
                    <tr class="hover:bg-slate-700/30 transition">
                        <td class="px-6 py-4 font-medium text-white"><?= $row->no ?></td>
                        <td class="px-6 py-4 text-center"><?= number_format($row->target, 2) ?></td>
                        <td class="px-6 py-4 text-center font-bold text-teal-400"><?= number_format($row->realisasi, 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>