<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 border-r border-slate-800 transition-transform duration-300 transform -translate-x-full md:translate-x-0 md:static md:inset-auto flex flex-col shrink-0">
    <a href="<?= base_url('dashboard') ?>" class="p-6 flex items-center gap-4 group hover:bg-slate-800/50 transition-colors duration-300">
        <div class="w-16 h-16 bg-white/5 border border-white/10 rounded-full flex items-center justify-center shadow-md backdrop-blur-sm group-hover:border-teal-500/30 group-hover:shadow-teal-500/20 transition-all duration-300">
            <img src="<?= base_url('assets/img/logo_bpom_1.png') ?>" 
                alt="Logo BPOM" 
                class="w-10 h-10 object-contain group-hover:scale-110 transition-transform duration-300"> 
        </div>
        
        <div>
            <h1 class="font-bold text-white text-base tracking-widest leading-tight group-hover:text-teal-400 transition-colors duration-300">E-KINERJA</h1>
            <p class="text-[10px] font-medium text-slate-400 uppercase tracking-[0.2em] mt-1 group-hover:text-slate-300 transition-colors duration-300">BBPOM Surabaya</p>
        </div>
    </a>

    <nav class="flex-1 px-4 py-4 space-y-2">
        
        <a href="<?= base_url('dashboard') ?>" 
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group <?= ($activeMenu ?? '') == 'dashboard' ? 'bg-slate-800 text-teal-400 border border-slate-700 shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' ?>">
            <i class="fa-solid fa-gauge-high <?= ($activeMenu ?? '') == 'dashboard' ? 'text-teal-400' : 'group-hover:text-white' ?>"></i>
            <span class="font-medium text-sm">Indikator Kinerja Utama</span>
        </a>

        <a href="#" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group <?= ($activeMenu == 'perjanjian_kinerja') ? 'bg-slate-800 text-teal-400 border border-slate-700 shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' ?>">
            <i class="fa-solid fa-file-signature <?= ($activeMenu == 'perjanjian_kinerja') ? 'text-teal-400' : 'group-hover:text-white' ?>"></i>
            <span class="font-medium text-sm">Perjanjian Kinerja</span>
        </a>

        <a href="<?= base_url('capaianoutput') ?>" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group <?= ($activeMenu == 'capaian_output') ? 'bg-slate-800 text-teal-400 border border-slate-700 shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' ?>">
            <i class="fa-solid fa-list-check <?= ($activeMenu == 'capaian_output') ? 'text-teal-400' : 'group-hover:text-white' ?>"></i>
            <span class="font-medium text-sm">Capaian Output</span>
        </a>

        <a href="#" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group <?= ($activeMenu == 'database_capaian') ? 'bg-slate-800 text-teal-400 border border-slate-700 shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' ?>">
            <i class="fa-solid fa-database <?= ($activeMenu == 'database_capaian') ? 'text-teal-400' : 'group-hover:text-white' ?>"></i>
            <span class="font-medium text-sm">Database Capaian</span>
        </a>

        <a href="<?= base_url('anggaran') ?>" 
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group <?= ($activeMenu == 'anggaran') ? 'bg-slate-800 text-teal-400 border border-slate-700 shadow-md' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' ?>">
                <i class="fa-solid fa-money-bill-wave <?= ($activeMenu == 'anggaran') ? 'text-teal-400' : 'group-hover:text-white' ?>"></i>
                <span class="font-medium text-sm">Anggaran</span>
        </a>

        <a href="<?= base_url('admin/entry/verify') ?>"
        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group <?= (url_is('admin/entry*')) ? 'bg-slate-800 text-teal-400' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' ?>">
            <div class="flex items-center justify-center w-5">
                <i class="fa-solid fa-pen-to-square text-sm"></i>
            </div>
            <span class="text-sm font-medium">Data Entry</span>
        </a>
    </nav>

    <div class="p-4 border-t border-slate-800">
        <a href="<?= base_url('admin/profile') ?>" class="flex items-center gap-3 px-2 py-2 rounded-xl hover:bg-slate-800/50 transition-all duration-300 group">
        <?php if (session()->get('photo')): ?>
            <img src="<?= base_url('uploads/profile/' . session()->get('photo')) ?>" class="w-8 h-8 rounded-full object-cover border border-slate-600 shadow-sm group-hover:border-teal-500/50 group-hover:shadow-teal-500/20 transition-all">
        <?php else: ?>
            <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-teal-400 border border-slate-600 shadow-sm group-hover:border-teal-500/50 group-hover:shadow-teal-500/20 transition-all">
                <i class="fa-solid fa-user-tie text-xs"></i>
            </div>
        <?php endif; ?>
            <div class="overflow-hidden text-left flex-1">
                <?php if (session()->get('isLoggedIn')): ?>
                    <p class="text-xs text-white font-medium truncate group-hover:text-teal-400 transition-colors"><?= esc(session()->get('name') ?? session()->get('username')) ?></p>
                    <p class="text-[9px] text-slate-500 uppercase tracking-widest"><?= esc(session()->get('role')) ?></p>
                <?php else: ?>
                    <p class="text-xs text-white font-medium truncate group-hover:text-teal-400 transition-colors">Admin BBPOM</p>
                    <p class="text-[9px] text-slate-500 uppercase tracking-widest">Dashboard View</p>
                <?php endif; ?>
            </div>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-600 group-hover:text-teal-400 transition-all translate-x-1 opacity-0 group-hover:opacity-100 group-hover:translate-x-0"></i>
        </a>
    </div>
</aside>