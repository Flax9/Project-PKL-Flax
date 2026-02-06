<?php 
    // Dynamic Back Button Logic
    $uri = service('uri'); // Get URI service
    $seg3 = $uri->getTotalSegments() >= 3 ? $uri->getSegment(3) : '';
    $isSelectionPage = ($seg3 === 'selection');

    // Default: Kembali ke Pilihan Jalur
    $dynamicBackUrl   = base_url('admin/entry/selection');
    $dynamicBackLabel = 'Kembali ke Pilihan Jalur';

    if ($seg3 === 'validation') {
        $dynamicBackUrl   = base_url('admin/pengajuan');
        $dynamicBackLabel = 'Kembali ke Menu Perubahan Data';
    } elseif ($seg3 === 'detail') {
        $dynamicBackUrl   = base_url('admin/pengajuan/validation');
        $dynamicBackLabel = 'Kembali ke Antrian Validasi';
    } elseif ($seg3 === 'submission') {
        $dynamicBackUrl   = base_url('admin/pengajuan');
        $dynamicBackLabel = 'Kembali ke Menu Perubahan Data';
    }

    $backUrl = isset($backUrl) ? $backUrl : $dynamicBackUrl;
    $backLabel = isset($backLabel) ? $backLabel : $dynamicBackLabel;
?>
<!-- STICKY HEADER: TITLE & USER INFO -->
<header class="h-auto border-b border-slate-800 bg-slate-900/80 backdrop-blur-md z-10 sticky top-0">
    <div class="px-8 py-4 flex flex-col md:flex-row justify-between items-center gap-4">
        <!-- LEFT: TITLE -->
        <div>
            <h2 class="text-2xl font-bold text-white mb-1">Data Management System</h2>
            <p class="text-sm text-slate-500">Silakan pilih kategori data yang ingin diinput atau diperbarui.</p>
        </div>

        <!-- RIGHT: USER INFO -->
        <div class="flex items-center gap-4 bg-slate-950 border border-slate-800 p-2 pr-4 pl-4 rounded-full shadow-lg">
            <div class="flex flex-col text-right mr-2 hidden md:block">
                <span class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">Role Access</span>
                <span class="text-xs text-white font-bold capitalize"><?= session()->get('role') ?? 'Guest' ?></span>
            </div>
            <div class="h-8 w-[1px] bg-slate-800 hidden md:block"></div>
            <a href="<?= base_url('admin/entry/logout') ?>" class="flex items-center gap-2 text-red-400 hover:text-red-300 transition-colors text-xs font-bold uppercase tracking-wider">
                <i class="fa-solid fa-power-off"></i> Logout
            </a>
        </div>
    </div>

    <!-- TABS SECTION (Only for Entry Routine) -->
    <?php if ($seg3 === 'rutin'): ?>
    <div class="px-8 border-t border-slate-800/50">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="entryTabs" role="tablist">
            <li class="me-2" role="presentation">
                <button class="inline-flex items-center p-4 border-b-2 border-teal-500 text-teal-400 rounded-t-lg active" 
                        id="iku-tab" data-bs-toggle="tab" data-bs-target="#iku-content" type="button" role="tab">
                    <i class="fa-solid fa-chart-line me-2"></i>Capaian IKU
                </button>
            </li>
            <li class="me-2" role="presentation">
                <button class="inline-flex items-center p-4 border-b-2 border-transparent text-slate-500 hover:text-teal-400 hover:border-teal-400 rounded-t-lg transition-all" 
                        id="nko-tab" data-bs-toggle="tab" data-bs-target="#nko-content" type="button" role="tab">
                    <i class="fa-solid fa-chart-pie me-2"></i>NKO
                </button>
            </li>
            <li class="me-2" role="presentation">
                <button class="inline-flex items-center p-4 border-b-2 border-transparent text-slate-500 rounded-t-lg opacity-50 cursor-not-allowed" disabled>
                    <i class="fa-solid fa-file-contract me-2"></i>Perjanjian Kinerja
                </button>
            </li>
            <li class="me-2" role="presentation">
                <button class="inline-flex items-center p-4 border-b-2 border-transparent text-slate-500 hover:text-teal-400 hover:border-teal-400 rounded-t-lg transition-all" 
                        id="capaian-output-tab" data-bs-toggle="tab" data-bs-target="#capaian-output-content" type="button" role="tab">
                    <i class="fa-solid fa-chart-line me-2"></i>Capaian Output
                </button>
            </li>
            <li class="me-2" role="presentation">
                <button class="inline-flex items-center p-4 border-b-2 border-transparent text-slate-500 hover:text-teal-400 hover:border-teal-400 rounded-t-lg transition-all" 
                        id="anggaran-tab" data-bs-toggle="tab" data-bs-target="#anggaran-content" type="button" role="tab">
                    <i class="fa-solid fa-coins me-2"></i>Anggaran
                </button>
            </li>
            <li class="me-2" role="presentation">
                <button class="inline-flex items-center p-4 border-b-2 border-transparent text-slate-500 rounded-t-lg opacity-50 cursor-not-allowed" disabled>
                    <i class="fa-solid fa-database me-2"></i>Master Database
                </button>
            </li>
        </ul>
    </div>
    <?php endif; ?>
</header>
<!-- BACK BUTTON SECTION (Conditional) -->




<?php if (!$isSelectionPage && empty($hideBack)): ?>
<div class="px-8 py-6">
    <a href="<?= $backUrl ?>" class="flex items-center gap-2 text-slate-400 hover:text-teal-400 text-sm transition-colors">
        <i class="fa-solid fa-arrow-left"></i> <?= $backLabel ?>
    </a>
</div>
<?php endif; ?>
