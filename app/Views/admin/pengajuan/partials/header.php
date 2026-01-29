<!-- STICKY HEADER: TITLE ONLY -->
<header class="h-auto border-b border-slate-800 bg-slate-900/80 backdrop-blur-md z-10 sticky top-0">
    <div class="px-8 py-4">
        <h2 class="text-2xl font-bold text-white mb-1">Data Management System</h2>
        <p class="text-sm text-slate-500">Silakan pilih kategori data yang ingin diinput atau diperbarui.</p>
    </div>
</header>

<!-- BACK BUTTON SECTION (NON-STICKY) -->
<div class="px-8 py-6">
    <?php 
        $backUrl = isset($backUrl) ? $backUrl : base_url('admin/entry/selection');
        $backLabel = isset($backLabel) ? $backLabel : 'Kembali ke Pilihan Jalur';
    ?>
    <a href="<?= $backUrl ?>" class="flex items-center gap-2 text-slate-400 hover:text-teal-400 text-sm transition-colors">
        <i class="fa-solid fa-arrow-left"></i> <?= $backLabel ?>
    </a>
</div>
