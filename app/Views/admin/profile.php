<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-950 text-slate-300">
    <!-- Header -->
    <header class="h-16 bg-slate-900/50 border-b border-slate-800 flex items-center justify-between px-8 z-10 backdrop-blur-md">
        <div class="flex items-center gap-4">
            <a href="javascript:history.back()" class="text-slate-400 hover:text-white transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="text-xl font-bold text-white tracking-tight">Profil Pengguna</h2>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto p-8 relative">
        <!-- Background Glow -->
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-teal-500/5 blur-[120px] rounded-full -translate-y-1/2 translate-x-1/2"></div>
        
        <div class="max-w-4xl mx-auto space-y-8 relative z-10">
            <!-- Profile Card -->
            <div class="glass-card p-8 flex flex-col md:flex-row items-center gap-8">
                <div class="w-32 h-32 rounded-3xl bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center shadow-lg shadow-teal-500/20">
                    <i class="fa-solid fa-user-tie text-5xl text-white"></i>
                </div>
                
                <div class="flex-1 text-center md:text-left">
                    <h3 class="text-3xl font-bold text-white mb-1"><?= esc($user['username']) ?></h3>
                    <p class="text-teal-400 font-medium uppercase tracking-[0.2em] text-sm mb-4"><?= esc($user['role']) ?></p>
                    
                    <div class="flex flex-wrap justify-center md:justify-start gap-4">
                        <div class="px-4 py-2 bg-slate-800/50 border border-slate-700 rounded-xl text-xs flex items-center gap-2">
                            <i class="fa-solid fa-shield-halved text-teal-400"></i>
                            <span class="text-slate-400">Status Keamanan:</span>
                            <span class="text-emerald-400 font-semibold">Aktif</span>
                        </div>
                        <div class="px-4 py-2 bg-slate-800/50 border border-slate-700 rounded-xl text-xs flex items-center gap-2">
                            <i class="fa-solid fa-clock text-teal-400"></i>
                            <span class="text-slate-400">Sesi Berlaku</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <a href="<?= base_url('admin/entry/logout') ?>" class="px-6 py-3 bg-rose-500/10 hover:bg-rose-500 text-rose-500 hover:text-white border border-rose-500/20 rounded-xl transition-all duration-300 font-bold flex items-center justify-center gap-2">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </a>
                </div>
            </div>

            <!-- Details Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Account Info -->
                <div class="glass-card p-6">
                    <h4 class="text-sm font-bold text-white uppercase tracking-widest mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-teal-400"></i> Informasi Akun
                    </h4>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-slate-800/50">
                            <span class="text-slate-500 text-sm">Username</span>
                            <span class="text-slate-200 font-medium"><?= esc($user['username']) ?></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-800/50">
                            <span class="text-slate-500 text-sm">Hak Akses</span>
                            <span class="text-teal-400 font-bold uppercase text-[10px] tracking-wider"><?= esc($user['role']) ?></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-800/50">
                            <span class="text-slate-500 text-sm">Organisasi</span>
                            <span class="text-slate-200 font-medium">BBPOM Surabaya</span>
                        </div>
                    </div>
                </div>

                <!-- Security Info -->
                <div class="glass-card p-6">
                    <h4 class="text-sm font-bold text-white uppercase tracking-widest mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-lock text-teal-400"></i> Keamanan
                    </h4>
                    
                    <div class="space-y-4">
                        <p class="text-xs text-slate-400 leading-relaxed italic">
                            Password Anda saat ini dilindungi dengan enkripsi standar industri. Kami menyarankan untuk melakukan pembaruan password secara berkala.
                        </p>
                        
                        <div class="mt-4">
                            <button disabled class="w-full px-4 py-3 bg-slate-800/50 text-slate-500 border border-slate-700 rounded-xl text-sm font-bold cursor-not-allowed flex items-center justify-center gap-2">
                                <i class="fa-solid fa-key"></i> Ubah Password (Coming Soon)
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="text-center opacity-50 text-[10px] uppercase tracking-[0.3em] font-bold text-slate-500 pb-8">
                E-KINERJA BBPOM SURABAYA &copy; 2026
            </div>
        </div>
    </main>
</div>
<?= $this->endSection() ?>
