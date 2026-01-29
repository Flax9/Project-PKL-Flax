<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?= $this->include('admin/pengajuan/partials/header', ['backUrl' => base_url('admin/entry/selection'), 'backLabel' => 'Kembali ke Pilihan Jalur']) ?>

<div class="flex-1 overflow-y-auto p-6 md:p-8">
    
    <!-- SELECTION CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto mt-12">
        
        <!-- CARD 1: PENGAJUAN (User) -->
        <a href="<?= base_url('admin/pengajuan/submission') ?>" class="block p-8 rounded-3xl bg-slate-900 hover:bg-slate-800 transition-colors border border-slate-800">
            <div class="w-12 h-12 rounded-xl bg-teal-500/20 text-teal-400 flex items-center justify-center text-xl mb-6">
                <i class="fa-solid fa-right-left"></i>
            </div>
            
            <h3 class="text-xl font-bold text-white mb-3">Pengajuan Perubahan Data</h3>
            <p class="text-slate-400 text-sm leading-relaxed">
                Menu pengelolaan perubahan data. Termasuk pengajuan perubahan (User) dan validasi perubahan (Perencana).
            </p>
        </a>

        <!-- CARD 2: VALIDASI (Perencana) -->
        <?php 
            // Allow 'perencana' OR 'admin' for easier testing/access
            $currentRole = session()->get('role');
            $isPerencana = ($currentRole === 'perencana' || $currentRole === 'admin');  
            $link = $isPerencana ? base_url('admin/pengajuan/validation') : '#';
            $opacity = $isPerencana ? '' : 'opacity-50 cursor-not-allowed';
        ?>
        <a href="<?= $link ?>" class="block p-8 rounded-3xl bg-slate-900 hover:bg-slate-800 transition-colors border border-slate-800 <?= $opacity ?>">
             <div class="w-12 h-12 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-xl mb-6">
                <i class="fa-solid fa-clipboard-check"></i>
            </div>
            
            <h3 class="text-xl font-bold text-white mb-3">Validasi Pengubahan Data</h3>
            <p class="text-slate-400 text-sm leading-relaxed">
                (Khusus Perencana) Validasi pengajuan perubahan data, upload disposisi, dan update data ePerformance.
            </p>
        </a>

    </div>

</div>
<?= $this->endSection() ?>
