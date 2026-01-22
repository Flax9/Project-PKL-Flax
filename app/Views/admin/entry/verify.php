<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Admin | BBPOM Surabaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-900 font-sans antialiased">

    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full">
            
            <div class="bg-slate-800 rounded-3xl shadow-2xl border border-slate-700 overflow-hidden">
                <div class="p-8 text-center">
                    
                    <div class="w-20 h-20 bg-slate-700 rounded-2xl flex items-center justify-center text-teal-400 mx-auto mb-6 border border-slate-600 shadow-inner">
                        <i class="fa-solid fa-shield-halved text-3xl"></i>
                    </div>

                    <h3 class="text-2xl font-bold text-white mb-2">Akses Terbatas</h3>
                    <p class="text-slate-400 text-sm mb-8 leading-relaxed">
                        Halaman <strong>Data Entry</strong> memerlukan otorisasi tambahan. Silakan masukkan kode admin untuk melanjutkan.
                    </p>

                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="mb-6 p-3 bg-red-500/10 border border-red-500/50 rounded-xl text-red-400 text-xs flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/entry/check-auth') ?>" method="POST" autocomplete="off">
                        <?= csrf_field() ?>
                        
                        <div class="mb-6 relative group">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-teal-400 transition-colors">
                                <i class="fa-solid fa-key text-sm"></i>
                            </span>
                            <input type="password" 
                                   name="auth_code" 
                                   class="w-full bg-slate-900 border border-slate-700 rounded-2xl pl-12 pr-4 py-4 text-white text-center text-lg tracking-[0.5em] placeholder:tracking-normal placeholder:text-slate-600 focus:ring-2 focus:ring-teal-500/50 focus:border-teal-500 focus:outline-none transition-all"
                                   placeholder="KODE ADMIN" 
                                   required 
                                   autofocus>
                        </div>

                        <button type="submit" class="w-full bg-teal-600 hover:bg-teal-500 text-white font-bold py-4 rounded-2xl shadow-lg shadow-teal-900/20 transition-all active:scale-[0.98]">
                            Buka Akses Penginputan
                        </button>
                    </form>

                </div>

                <div class="bg-slate-800/50 p-4 border-t border-slate-700/50 text-center">
                    <a href="<?= base_url('dashboard') ?>" class="text-xs text-slate-500 hover:text-teal-400 transition-colors">
                        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Dashboard Utama
                    </a>
                </div>
            </div>

            <p class="mt-8 text-center text-slate-600 text-[10px] uppercase tracking-widest">
                &copy; 2026 BBPOM Surabaya - Sistem Monitoring Internal
            </p>

        </div>
    </div>

</body>
</html>