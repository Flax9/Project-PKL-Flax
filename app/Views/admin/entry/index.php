<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?= $this->include('dashboard/partials/header') ?>

<div class="flex-1 overflow-y-auto p-8 z-10 flex flex-col gap-6">
    <div class="mb-4">
        <a href="<?= base_url('admin/entry/selection') ?>" class="text-slate-400 hover:text-teal-400 text-sm transition-colors flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Pilihan Jalur
        </a>
    </div>

    <div id="entryTabContent" class="mt-2">
        <!-- TAB IKU -->
        <div class="tab-pane block" id="iku-content" role="tabpanel">
            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6 shadow-xl">
                <?= view('admin/entry/tabs/iku') ?>
            </div>
        </div>

        <!-- TAB NKO -->
        <div class="tab-pane hidden" id="nko-content" role="tabpanel">
            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6 shadow-xl">
                <?= view('admin/entry/tabs/nko') ?>
            </div>
        </div>

        <!-- TAB ANGGARAN -->
        <div class="tab-pane hidden" id="anggaran-content" role="tabpanel">
            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6 shadow-xl">
                <?= view('admin/entry/tabs/anggaran') ?>
            </div>
        </div>

        <!-- TAB CAPAIAN OUTPUT -->
        <div class="tab-pane hidden" id="capaian-output-content" role="tabpanel">
            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6 shadow-xl">
                <?= view('admin/entry/tabs/capaian_output') ?>
            </div>
        </div>
    </div>

</div>

<!-- TAB SWITCHING SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const triggerTabList = [].slice.call(document.querySelectorAll('#entryTabs button[data-bs-toggle="tab"]'));
    triggerTabList.forEach(function(triggerEl) {
        triggerEl.addEventListener('click', function(event) {
            event.preventDefault();
            
            // 1. Deactive all tabs
            triggerTabList.forEach(btn => {
                btn.classList.remove('border-teal-500', 'text-teal-400', 'active');
                btn.classList.add('border-transparent', 'text-slate-500');
                const targetSelector = btn.getAttribute('data-bs-target');
                document.querySelector(targetSelector).classList.add('hidden');
                document.querySelector(targetSelector).classList.remove('block');
            });

            // 2. Activate clicked tab
            this.classList.remove('border-transparent', 'text-slate-500');
            this.classList.add('border-teal-500', 'text-teal-400', 'active');
            
            const targetSelector = this.getAttribute('data-bs-target');
            const targetPane = document.querySelector(targetSelector);
            targetPane.classList.remove('hidden');
            targetPane.classList.add('block');
        });
    });
});
</script>

</div>

<?= $this->endSection() ?>