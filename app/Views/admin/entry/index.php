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
        <div class="tab-pane block" id="iku-content" role="tabpanel">
            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6 shadow-xl">
                <?= view('admin/entry/tabs/iku') ?>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>