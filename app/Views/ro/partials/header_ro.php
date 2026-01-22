<header class="h-20 border-b border-slate-800 flex items-center justify-between px-8 bg-slate-900/80 backdrop-blur-md z-10">
    <div class="flex items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-white tracking-tight">Rincian Output (RO)</h2>
            <p class="text-[10px] text-slate-500 uppercase tracking-widest italic">Monitoring Capaian & Anggaran</p>
        </div>
    </div>
    
    <div class="flex items-center gap-3">
        <select id="filterRo" class="bg-slate-800 border border-slate-700 text-slate-300 text-[11px] rounded-lg focus:ring-teal-500 focus:border-teal-500 block p-2 max-w-[200px] cursor-pointer">
            <option value="">Semua Keterangan RO</option>
            <?php if (!empty($filter_ro)): foreach($filter_ro as $ro): ?>
                <option value="<?= $ro['Keterangan RO'] ?>" <?= (request()->getGet('keterangan_ro') == $ro['Keterangan RO']) ? 'selected' : '' ?>>
                    <?= $ro['Keterangan RO'] ?>
                </option>
            <?php endforeach; endif; ?>
        </select>

        <select id="filterFungsi" class="bg-slate-800 border border-slate-700 text-slate-300 text-[11px] rounded-lg focus:ring-teal-500 focus:border-teal-500 block p-2 cursor-pointer">
            <option value="">Semua Fungsi</option>
            <?php if (!empty($filter_fungsi)): foreach($filter_fungsi as $f): ?>
                <option value="<?= $f['Fungsi'] ?>" <?= (request()->getGet('fungsi') == $f['Fungsi']) ? 'selected' : '' ?>>
                    <?= $f['Fungsi'] ?>
                </option>
            <?php endforeach; endif; ?>
        </select>

        <select id="filterBulanRo" class="bg-slate-800 border border-slate-700 text-slate-300 text-[11px] rounded-lg focus:ring-teal-500 focus:border-teal-500 block p-2 cursor-pointer">
            <option value="">Bulan</option>
            <?php if (!empty($filter_bulan)): foreach($filter_bulan as $b): ?>
                <option value="<?= $b['Bulan'] ?>" <?= (request()->getGet('bulan') == $b['Bulan']) ? 'selected' : '' ?>>
                    <?= $b['Bulan'] ?>
                </option>
            <?php endforeach; endif; ?>
        </select>

        <a href="<?= base_url('ro') ?>" class="p-2 text-slate-500 hover:text-white transition-colors" title="Reset Filter">
            <i class="fa-solid fa-rotate-left"></i>
        </a>

        <div class="w-px h-8 bg-slate-800 mx-1"></div>

        <button id="btnExportRo" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-xs font-semibold transition-all shadow-lg shadow-blue-500/20 active:scale-95 flex items-center gap-2">
            <i class="fa-solid fa-file-pdf"></i>
            <span class="hidden lg:inline">Cetak RO</span>
        </button>
    </div>
</header>