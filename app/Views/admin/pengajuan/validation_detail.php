<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?= $this->include('admin/pengajuan/partials/header', ['backUrl' => base_url('admin/pengajuan/validation'), 'backLabel' => 'Kembali ke Antrian Validasi']) ?>

<div class="flex-1 overflow-y-auto p-6 md:p-8">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- LEFT PANEL: USER REQUEST INFO (READ ONLY) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- IDENTITAS CARD -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 md:p-8">
                <h3 class="text-teal-400 font-bold uppercase tracking-wider text-sm flex items-center gap-2 mb-6">
                    <i class="fa-solid fa-circle-info"></i> Identitas Pengajuan
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-xs text-slate-500 uppercase font-bold">Fungsi Pengirim</label>
                        <div class="text-white font-medium"><?= esc($request['fungsi']) ?></div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs text-slate-500 uppercase font-bold">Waktu Pengajuan</label>
                        <div class="text-white font-medium"><?= date('d F Y, H:i', strtotime($request['created_at'])) ?> WIB</div>
                    </div>
                    <div class="md:col-span-2 space-y-1">
                        <label class="text-xs text-slate-500 uppercase font-bold">Indikator Kinerja Utama (IKU)</label>
                        <div class="text-amber-400 font-bold text-lg"><?= esc($request['no_iku']) ?></div>
                        <div class="text-slate-300 text-sm"><?= esc($request['nama_indikator']) ?></div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs text-slate-500 uppercase font-bold">Periode Data</label>
                        <div class="text-white font-medium"><?= esc($request['bulan']) ?> <?= esc($request['tahun']) ?></div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs text-slate-500 uppercase font-bold">Jenis Revisi</label>
                        <div class="inline-block px-3 py-1 rounded bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 text-xs font-bold">
                            <?= esc($request['jenis_revisi']) ?>
                        </div>
                    </div>
                </div>

                <!-- ALASAN / KETERANGAN -->
                <div class="mt-6 pt-6 border-t border-slate-800 space-y-2">
                    <label class="text-xs text-slate-500 uppercase font-bold">Keterangan / Alasan Revisi</label>
                    <div class="bg-slate-950/50 p-4 rounded-xl border border-slate-800 text-slate-300 text-sm italic">
                        "<?= esc($request['keterangan']) ?>"
                    </div>
                </div>
            </div>

            <!-- DETAIL PERUBAHAN (OLD VS NEW) -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 md:p-8">
                <h3 class="text-amber-500 font-bold uppercase tracking-wider text-sm flex items-center gap-2 mb-6">
                    <i class="fa-solid fa-right-left"></i> Rincian Perubahan Data
                </h3>

                <div class="overflow-x-auto rounded-xl border border-slate-800">
                    <table class="w-full text-sm text-left text-slate-400">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-950">
                            <tr>
                                <th class="px-4 py-3 min-w-[150px]">Data</th>
                                <th class="px-4 py-3 text-red-400 opacity-80 min-w-[200px]">Nilai Semula (Staging)</th>
                                <th class="px-4 py-3 text-emerald-400 min-w-[200px]">Nilai Menjadi (Pengajuan)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800 bg-slate-900/50">
                            <?php 
                                // DEFINISI 15 KOLOM AUDIT (Sesuai Gambar User)
                                $auditColumns = [
                                    ['label' => 'Tahun', 'db' => 'tahun', 'origin' => 'tahun'],
                                    ['label' => 'Bulan', 'db' => 'bulan', 'origin' => 'bulan'],
                                    ['label' => 'Fungsi / Pengirim', 'db' => 'fungsi', 'origin' => 'fungsi'],
                                    ['label' => 'No. IKU', 'db' => 'no_iku', 'origin' => 'no_iku'],
                                    ['label' => 'Nama Indikator', 'db' => 'nama_indikator', 'origin' => 'nama_indikator'],
                                    ['label' => 'No. Indikator', 'db' => 'no_indikator', 'origin' => 'no_indikator'],
                                    ['label' => 'No. Bulan', 'db' => 'no_bulan', 'origin' => 'no_bulan'],
                                    ['label' => 'Target', 'db' => 'target', 'origin' => 'target'],
                                    ['label' => 'Realisasi', 'db' => 'realisasi', 'origin' => 'realisasi'],
                                    ['label' => 'Performa % Capaian Bulan', 'db' => 'perf_bulan', 'origin' => 'perf_bulan'],
                                    ['label' => 'Kategori Capaian Bulan', 'db' => 'kat_bulan', 'origin' => 'kat_bulan'],
                                    ['label' => 'Performa % Capaian Tahun', 'db' => 'perf_tahun', 'origin' => 'perf_tahun'],
                                    ['label' => 'Kategori Capaian Tahun', 'db' => 'kat_tahun', 'origin' => 'kat_tahun'],
                                    ['label' => 'Capaian Normalisasi', 'db' => 'cap_norm', 'origin' => 'cap_norm'],
                                    ['label' => 'Capaian Normalisasi Angka', 'db' => 'cap_norm_angka', 'origin' => 'cap_norm_angka']
                                ];
                                
                                foreach($auditColumns as $col):
                                    // Get Old Value (From Staging/Capaian IKU) - Use 'origin' key if available, else 'db'
                                    $keyOrigin = $col['origin'] ?? $col['db']; 
                                    $oldVal = isset($original[$keyOrigin]) ? $original[$keyOrigin] : '-';

                                    // Get New Value (From Pengajuan Request)
                                    $keyNew = $col['db'];
                                    $newVal = isset($request[$keyNew]) ? $request[$keyNew] : '-';
                                    
                                    // Highlight logic: if diff and not identity
                                    $isIdentity = in_array($col['label'], ['Tahun','Bulan','Fungsi / Pengirim','No. IKU','Nama Indikator']);
                                    $isDiff = (!$isIdentity && strval($oldVal) !== strval($newVal));
                                    $highlightClass = $isDiff ? 'bg-amber-500/10 text-amber-300 font-bold border-l-2 border-amber-500 pl-3' : '';
                            ?>
                            <tr class="<?= $isDiff ? 'bg-amber-900/10' : '' ?>">
                                <td class="px-4 py-3 font-medium text-slate-300 <?= $highlightClass ?>">
                                    <?= $col['label'] ?>
                                </td>
                                
                                <!-- OLD VALUE -->
                                <td class="px-4 py-3 text-slate-500 <?= ($isDiff && $oldVal !== '-') ? 'line-through decoration-red-500/50 text-red-400/70' : '' ?>">
                                    <?= esc($oldVal) ?>
                                </td>
                                
                                <!-- NEW VALUE -->
                                <td class="px-4 py-3 <?= $isDiff ? 'text-emerald-400 font-bold' : 'text-slate-400' ?>">
                                    <?= esc($newVal) ?>
                                    <?php if($isDiff): ?>
                                        <i class="fa-solid fa-check ml-2 text-[10px] opacity-70"></i>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if(empty($original)): ?>
                <div class="mt-4 p-4 rounded-xl bg-slate-950 border border-slate-800 flex items-start gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-1"></i>
                    <div>
                        <div class="text-amber-400 font-bold text-xs mb-1">Data Asli Tidak Ditemukan</div>
                        <p class="text-xs text-slate-500">
                            Data "Nilai Semula" tidak dapat ditemukan di database staging. Ini mungkin karena data IKU telah dihapus atau ID IKU tidak cocok ("<?= esc($request['no_iku']) ?>"). Perbandingan di atas hanya menampilkan data dari pengajuan.
                        </p>
                    </div>
                </div>
                <?php endif; ?>

            </div>
            
            <!-- NOTA DINAS (USER FILE) -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 md:p-8">
                <h3 class="text-blue-400 font-bold uppercase tracking-wider text-sm flex items-center gap-2 mb-6">
                    <i class="fa-solid fa-file-pdf"></i> Dokumen Pendukung User
                </h3>
                
                <div class="flex items-center gap-4 bg-slate-950 p-4 rounded-xl border border-slate-800">
                    <div class="w-10 h-10 rounded bg-red-500/20 text-red-400 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="flex-1">
                        <div class="text-white font-bold text-sm">Nota Dinas Pengajuan.pdf</div>
                        <div class="text-xs text-slate-500">Diunggah: <?= date('d M Y H:i', strtotime($request['tgl_upload_nota'])) ?></div>
                    </div>
                    <?php if($request['file_nota_dinas']): ?>
                        <a href="<?= base_url($request['file_nota_dinas']) ?>" target="_blank" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white text-xs rounded-lg transition-colors border border-slate-700">
                            <i class="fa-solid fa-eye mr-1"></i> Lihat
                        </a>
                    <?php else: ?>
                        <span class="text-red-500 text-xs italic">File Tidak Ditemukan</span>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <!-- RIGHT PANEL: PLANNER ACTION TIMELINE -->
        <div class="lg:col-span-1">
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 md:p-8 sticky top-24">
                <h3 class="text-purple-400 font-bold uppercase tracking-wider text-sm flex items-center gap-2 mb-8">
                    <i class="fa-solid fa-timeline"></i> Timeline Validasi
                </h3>

                <div class="relative space-y-12 pl-4 border-l-2 border-slate-800">
                    
                    <!-- STEP 1: PENGAJUAN DIBUAT (START) -->
                    <div class="relative pl-8">
                        <!-- Dot -->
                        <div class="absolute -left-[21px] top-0 w-10 h-10 rounded-full border-4 border-slate-900 flex items-center justify-center bg-emerald-500 text-white">
                            <i class="fa-solid fa-paper-plane"></i>
                        </div>
                        
                        <h4 class="font-bold text-white mb-1">Pengajuan Dibuat</h4>
                        <p class="text-xs text-slate-500 mb-4">User membuat permohonan perubahan data.</p>

                        <div class="bg-slate-800/50 border border-slate-700/50 rounded-lg p-3 mb-2">
                             <div class="text-xs text-slate-400">Status Awal: <span class="text-amber-400 font-bold">Diajukan</span></div>
                             <div class="text-[10px] text-slate-500 mt-1"><?= date('d F Y, H:i', strtotime($request['created_at'])) ?></div>
                        </div>
                    </div>

                    <!-- STEP 2: DISPOSISI -->
                    <?php 
                        $step2Done = !empty($request['file_disposisi']);
                        $step2Active = ($request['status'] == 'diajukan'); // Active if user just submitted
                    ?>
                    <div class="relative pl-8">
                        <!-- Dot -->
                        <div class="absolute -left-[21px] top-0 w-10 h-10 rounded-full border-4 border-slate-900 flex items-center justify-center <?= $step2Done ? 'bg-emerald-500 text-white' : ($step2Active ? 'bg-amber-500 text-white animate-pulse' : 'bg-slate-800 text-slate-500') ?>">
                            <i class="fa-solid <?= $step2Done ? 'fa-check' : 'fa-1' ?>"></i>
                        </div>
                        
                        <h4 class="font-bold text-white mb-1">Disposisi Pimpinan</h4>
                        <p class="text-xs text-slate-500 mb-4">Upload bukti disposisi dari Kepala Balai / Ka TU.</p>

                        <?php if($step2Done): ?>
                            <!-- Done State -->
                            <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-lg p-3 mb-2">
                                <a href="<?= base_url($request['file_disposisi']) ?>" target="_blank" class="text-emerald-400 text-xs font-bold hover:underline flex items-center gap-2">
                                    <i class="fa-solid fa-file-check"></i> Lihat File Disposisi
                                </a>
                                <div class="text-[10px] text-emerald-600 mt-1"><?= date('d/m/y H:i', strtotime($request['tgl_upload_disposisi'])) ?></div>
                            </div>
                        <?php elseif($step2Active): ?>
                            <!-- Active Form -->
                            <form action="<?= base_url('admin/pengajuan/upload_disposisi/'.$request['id']) ?>" method="POST" enctype="multipart/form-data" class="space-y-3">
                                <?= csrf_field() ?>
                                <input type="file" name="file_disposisi" required class="block w-full text-xs text-slate-400 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-500 file:text-slate-900 hover:file:bg-amber-400 bg-slate-950 border border-slate-700 rounded-lg cursor-pointer">
                                <button type="submit" class="w-full py-2 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold rounded-lg transition-colors">
                                    Simpan Disposisi
                                </button>
                            </form>
                        <?php else: ?>
                            <!-- Locked State -->
                            <div class="text-xs text-slate-600 italic border border-slate-800 p-2 rounded">Menunggu langkah sebelumnya...</div>
                        <?php endif; ?>
                    </div>

                    <!-- STEP 3: SURAT ROREN -->
                    <?php 
                        $step3Done = !empty($request['file_surat_roren']);
                        $step3Active = ($step2Done && empty($step3Done));
                    ?>
                    <div class="relative pl-8">
                        <!-- Dot -->
                        <div class="absolute -left-[21px] top-0 w-10 h-10 rounded-full border-4 border-slate-900 flex items-center justify-center <?= $step3Done ? 'bg-emerald-500 text-white' : ($step3Active ? 'bg-amber-500 text-white animate-pulse' : 'bg-slate-800 text-slate-500') ?>">
                            <i class="fa-solid <?= $step3Done ? 'fa-check' : 'fa-3' ?>"></i>
                        </div>
                        
                        <h4 class="font-bold text-white mb-1">Surat ke Roren Pusat</h4>
                        <p class="text-xs text-slate-500 mb-4">Upload surat pengajuan perubahan ke Biro Perencanaan.</p>

                         <?php if($step3Done): ?>
                            <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-lg p-3 mb-2">
                                <a href="<?= base_url($request['file_surat_roren']) ?>" target="_blank" class="text-emerald-400 text-xs font-bold hover:underline flex items-center gap-2">
                                    <i class="fa-solid fa-file-check"></i> Lihat Surat Roren
                                </a>
                                <div class="text-[10px] text-emerald-600 mt-1"><?= date('d/m/y H:i', strtotime($request['tgl_upload_roren'])) ?></div>
                            </div>
                        <?php elseif($step3Active): ?>
                            <form action="<?= base_url('admin/pengajuan/upload_roren/'.$request['id']) ?>" method="POST" enctype="multipart/form-data" class="space-y-3">
                                <?= csrf_field() ?>
                                <input type="file" name="file_surat_roren" required class="block w-full text-xs text-slate-400 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-500 file:text-slate-900 hover:file:bg-amber-400 bg-slate-950 border border-slate-700 rounded-lg cursor-pointer">
                                <button type="submit" class="w-full py-2 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold rounded-lg transition-colors">
                                    Simpan Surat
                                </button>
                            </form>
                        <?php else: ?>
                            <div class="text-xs text-slate-600 italic border border-slate-800 p-2 rounded">Terkunci (Selesaikan Step 2)</div>
                        <?php endif; ?>
                    </div>

                    <!-- STEP 4: E-PERFORMANCE (FINAL) -->
                    <?php 
                        $step4Done = !empty($request['file_sc_eperformance']);
                        $step4Active = ($step3Done && empty($step4Done));
                    ?>
                    <div class="relative pl-8">
                        <!-- Dot -->
                        <div class="absolute -left-[21px] top-0 w-10 h-10 rounded-full border-4 border-slate-900 flex items-center justify-center <?= $step4Done ? 'bg-emerald-500 text-white' : ($step4Active ? 'bg-amber-500 text-white animate-pulse' : 'bg-slate-800 text-slate-500') ?>">
                            <i class="fa-solid <?= $step4Done ? 'fa-flag-checkered' : 'fa-4' ?>"></i>
                        </div>
                        
                        <h4 class="font-bold text-white mb-1">Update ePerformance</h4>
                        <p class="text-xs text-slate-500 mb-4">Upload bukti screenshot update data di aplikasi ePerformance.</p>

                        <?php if($step4Done): ?>
                            <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-lg p-3 mb-2">
                                <a href="<?= base_url($request['file_sc_eperformance']) ?>" target="_blank" class="text-emerald-400 text-xs font-bold hover:underline flex items-center gap-2">
                                    <i class="fa-solid fa-image"></i> Lihat Bukti Update
                                </a>
                                <div class="text-[10px] text-emerald-600 mt-1"><?= date('d/m/y H:i', strtotime($request['tgl_upload_eperformance'])) ?></div>
                            </div>
                            <div class="mt-4 text-center">
                                <span class="px-4 py-2 rounded-full bg-emerald-500 text-slate-900 font-bold text-xs uppercase shadow-lg shadow-emerald-500/20">
                                    Kasus Selesai
                                </span>
                            </div>
                        <?php elseif($step4Active): ?>
                            <form action="<?= base_url('admin/pengajuan/upload_eperformance/'.$request['id']) ?>" method="POST" enctype="multipart/form-data" class="space-y-3">
                                <?= csrf_field() ?>
                                <input type="file" name="file_sc_eperformance" required class="block w-full text-xs text-slate-400 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-500 file:text-slate-900 hover:file:bg-amber-400 bg-slate-950 border border-slate-700 rounded-lg cursor-pointer">
                                <button type="submit" class="w-full py-2 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold rounded-lg transition-colors">
                                    Selesai & Tutup Kasus
                                </button>
                            </form>
                        <?php else: ?>
                            <div class="text-xs text-slate-600 italic border border-slate-800 p-2 rounded">Terkunci (Selesaikan Step 3)</div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>
