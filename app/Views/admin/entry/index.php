<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?= $this->include('dashboard/partials/header') ?>

<div class="flex-1 overflow-y-auto p-8 z-10 flex flex-col gap-6">

    <div id="entryTabContent" class="mt-2">
        <div class="tab-pane block" id="iku-content" role="tabpanel">
            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6 shadow-xl">
                <?= view('admin/entry/tabs/iku') ?>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>