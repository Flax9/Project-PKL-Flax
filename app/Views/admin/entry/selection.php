<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="p-8 min-h-screen bg-slate-950 flex flex-col items-center justify-center">
    <div class="text-center mb-12">
        <h1 class="text-3xl font-bold text-white mb-2">Pilih Jalur Pengelolaan Data</h1>
        <p class="text-slate-400">Pilih tindakan yang ingin Anda lakukan pada database kinerja.</p>
    </div>

    <div class="grid md:grid-cols-2 gap-8 max-w-5xl w-full">
        

        <a href="<?= base_url('admin/entry/rutin') ?>" class="group bg-slate-900 border border-slate-800 p-8 rounded-3xl hover:border-teal-500/50 hover:bg-slate-800/50 transition-all shadow-2xl">
            <div class="w-16 h-16 bg-teal-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-calendar-plus text-teal-400 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-3">Input Realisasi Rutin</h3>
            <p class="text-sm text-slate-400 leading-relaxed">Gunakan untuk menginput capaian IKU bulanan yang belum terisi sama sekali. Data akan langsung diperbarui ke dashboard utama.</p>
        </a>

        <a href="<?= base_url('admin/pengajuan') ?>" class="group bg-slate-900 border border-slate-800 p-8 rounded-3xl hover:border-teal-500/50 hover:bg-slate-800/50 transition-all shadow-2xl">
            <div class="w-16 h-16 bg-teal-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-right-left text-teal-400 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-3">Perubahan Data</h3>
            <p class="text-sm text-slate-400 leading-relaxed">Menu pengelolaan perubahan data. Termasuk pengajuan perubahan (User) dan validasi perubahan (Perencana).</p>
        </a>
    </div>
</div>
<?= $this->endSection() ?>