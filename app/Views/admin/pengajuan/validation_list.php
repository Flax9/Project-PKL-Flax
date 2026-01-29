<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?= $this->include('admin/pengajuan/partials/header', ['backUrl' => base_url('admin/pengajuan'), 'backLabel' => 'Kembali ke Menu Perubahan Data']) ?>

<div class="flex-1 overflow-y-auto p-6 md:p-8">
    <div class="max-w-7xl mx-auto">
        
        <!-- HEADER SECTION -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                    <i class="fa-solid fa-list-check text-amber-500"></i>
                    Antrian Validasi Perubahan Data
                </h3>
                <p class="text-slate-400 mt-1">Daftar pengajuan perubahan data yang menunggu tindak lanjut dari Perencana.</p>
            </div>
            
            <!-- FILTER (Optional) -->
            <div class="flex gap-3">
                <select id="statusFilter" class="bg-slate-900 border border-slate-700 text-slate-300 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block p-2.5">
                    <option value="all">Semua Status</option>
                    <option value="diajukan" selected>Menunggu Disposisi</option>
                    <option value="disposisi">Proses Roren</option>
                    <option value="proses_roren">Finalisasi (ePerf)</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
        </div>

        <!-- TABLE CARD -->
        <div class="bg-slate-900/50 border border-slate-800 rounded-3xl overflow-hidden shadow-xl backdrop-blur-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-400">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-950 border-b border-slate-800">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold">Waktu Pengajuan</th>
                            <th scope="col" class="px-6 py-4 font-bold">Fungsi / Pengirim</th>
                            <th scope="col" class="px-6 py-4 font-bold">IKU & Indikator</th>
                            <th scope="col" class="px-6 py-4 font-bold">Jenis Revisi</th>
                            <th scope="col" class="px-6 py-4 font-bold">Status</th>
                            <th scope="col" class="px-6 py-4 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50">
                        <?php if (empty($requests)) : ?>
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500 italic">
                                    <div class="flex flex-col items-center gap-3">
                                        <i class="fa-regular fa-folder-open text-3xl opacity-50"></i>
                                        <span>Belum ada pengajuan perubahan data saat ini.</span>
                                    </div>
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($requests as $req) : ?>
                                <tr class="hover:bg-slate-800/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="text-white font-medium"><?= date('d M Y', strtotime($req['created_at'])) ?></div>
                                        <div class="text-xs text-slate-500"><?= date('H:i', strtotime($req['created_at'])) ?> WIB</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                            <?= esc($req['fungsi']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs">
                                        <div class="font-bold text-slate-200 mb-1"><?= esc($req['no_iku']) ?></div>
                                        <div class="text-xs text-slate-500 line-clamp-2" title="<?= esc($req['nama_indikator']) ?>">
                                            <?= esc($req['nama_indikator']) ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-amber-400 font-medium"><?= esc($req['jenis_revisi']) ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php 
                                            // Status Badge Logic
                                            $statusColors = [
                                                'diajukan' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                                'disposisi' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                                'proses_roren' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                                                'selesai' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                                'ditolak' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                            ];
                                            $statusLabels = [
                                                'diajukan' => 'Menunggu Disposisi',
                                                'disposisi' => 'Sudah Disposisi',
                                                'proses_roren' => 'Proses Roren',
                                                'selesai' => 'Selesai',
                                                'ditolak' => 'Ditolak',
                                            ];
                                            $cls = $statusColors[$req['status']] ?? 'bg-slate-500/10 text-slate-400';
                                            $lbl = $statusLabels[$req['status']] ?? ucfirst($req['status']);
                                        ?>
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold border <?= $cls ?>">
                                            <?= $lbl ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="<?= base_url('admin/pengajuan/detail/' . $req['id']) ?>" 
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-teal-500/20 text-teal-400 hover:bg-teal-500 hover:text-white transition-all shadow-lg hover:shadow-teal-500/20"
                                           title="Proses Validasi">
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- PAGINATION (Placeholder) -->
            <div class="px-6 py-4 border-t border-slate-800 flex justify-between items-center text-xs text-slate-500">
                <span>Menampilkan <?= count($requests) ?> data</span>
                <!-- Pagination links could go here -->
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>
