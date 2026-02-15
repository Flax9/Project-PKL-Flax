<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="flex-1 flex flex-col h-screen overflow-hidden text-slate-300 relative">

    <!-- Header -->
    <!-- Header -->
    <?= $this->include('dashboard/partials/header') ?>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto p-8 relative z-10">
        <div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Column: Identity & Photo -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Profile Card -->
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-b from-teal-500/20 to-transparent blur-2xl -z-10 rounded-[3rem]"></div>
                    <div class="glass-card p-8 flex flex-col items-center text-center border border-white/5 bg-slate-900/60 backdrop-blur-xl relative overflow-hidden">
                        
                        <!-- Photo Container -->
                        <div class="relative w-40 h-40 mb-6 group/photo cursor-pointer">
                            <div class="absolute inset-0 bg-teal-500 rounded-full blur opacity-20 group-hover/photo:opacity-40 transition-opacity duration-500"></div>
                            
                            <div id="profilePhotoContainer" class="w-full h-full rounded-full bg-slate-800 border-4 border-slate-700 group-hover/photo:border-teal-400/50 transition-all duration-300 overflow-hidden flex items-center justify-center relative z-10 shadow-2xl">
                                <?php if(!empty($user['photo'])): ?>
                                    <img src="<?= base_url('uploads/profile/' . $user['photo']) ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover/photo:scale-110">
                                <?php else: ?>
                                    <i class="fa-solid fa-user text-5xl text-slate-600"></i>
                                <?php endif; ?>
                            </div>

                            <!-- Overlay Update Icon -->
                            <label for="photoUpload" class="absolute inset-0 z-20 flex items-center justify-center bg-black/50 opacity-0 group-hover/photo:opacity-100 transition-all duration-300 rounded-full cursor-pointer backdrop-blur-sm">
                                <i class="fa-solid fa-camera text-2xl text-white drop-shadow-md"></i>
                            </label>
                            <input type="file" id="photoUpload" class="hidden" accept="image/*">
                        </div>

                        <h3 class="text-2xl font-bold text-white mb-0.5 tracking-tight"><?= esc($user['name'] ?? $user['username']) ?></h3>
                        <p class="text-sm text-slate-400 font-medium mb-4">@<?= esc($user['username']) ?></p>
                        
                        <div class="inline-flex px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/20 text-teal-400 text-[10px] font-bold uppercase tracking-wider mb-6">
                            <?= esc($user['role']) ?>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-4">
                            <div class="p-3 rounded-xl bg-slate-800/50 border border-slate-700/50 text-center">
                                <p class="text-[10px] text-slate-500 uppercase tracking-wider mb-1">Status Akun</p>
                                <p class="text-emerald-400 font-bold text-sm"><i class="fa-solid fa-circle-check mr-1"></i> Aktif</p>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-800/50 border border-slate-700/50 text-center">
                                <p class="text-[10px] text-slate-500 uppercase tracking-wider mb-1">Terdaftar</p>
                                <p class="text-white font-bold text-sm">2026</p>
                            </div>
                        </div>

                        <!-- Logout Button -->
                        <a href="<?= base_url('admin/entry/logout') ?>" class="w-full mt-6 py-3 rounded-xl border border-rose-500/30 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 font-bold text-sm transition-all flex items-center justify-center gap-2 group-hover:shadow-lg group-hover:shadow-rose-900/20">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Keluar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Edit Form -->
            <div class="lg:col-span-8">
                <div class="glass-card p-8 border border-white/5 bg-slate-900/60 backdrop-blur-xl h-full">
                    <div class="flex items-center justify-between mb-8 pb-6 border-b border-slate-800/50">
                        <div>
                            <h3 class="text-lg font-bold text-white">Informasi Pribadi</h3>
                            <p class="text-slate-500 text-sm mt-1">Perbarui informasi akun Anda di sini.</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-indigo-500/10 flex items-center justify-center text-indigo-400">
                            <i class="fa-solid fa-user-gear"></i>
                        </div>
                    </div>

                    <?php if(session()->getFlashdata('success')): ?>
                        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-3 text-emerald-400 animate-fade-in-down">
                            <i class="fa-solid fa-circle-check text-lg"></i>
                            <span class="font-medium"><?= session()->getFlashdata('success') ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 rounded-xl flex items-center gap-3 text-rose-400 animate-fade-in-down">
                            <i class="fa-solid fa-circle-exclamation text-lg"></i>
                            <span class="font-medium"><?= session()->getFlashdata('error') ?></span>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/entry/update-profile') ?>" method="POST" class="space-y-6">
                        <?= csrf_field() ?>
                        
                        <!-- Read-only Username -->
                        <div class="group">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Username</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-at text-slate-600"></i>
                                </div>
                                <input type="text" value="<?= esc($user['username']) ?>" readonly 
                                    class="w-full bg-slate-950/50 border border-slate-800 text-slate-400 rounded-xl pl-10 pr-4 py-3.5 text-sm font-medium focus:outline-none cursor-not-allowed select-all">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-lock text-slate-600 text-xs" title="Tidak dapat diubah demi integritas data"></i>
                                </div>
                            </div>
                            <p class="text-[10px] text-slate-600 mt-1.5 ml-1">* Username tidak dapat diubah demi integritas data sistem.</p>
                        </div>

                        <!-- Full Name -->
                        <div class="group">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Nama Lengkap</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-id-card text-slate-500 group-focus-within:text-teal-400 transition-colors"></i>
                                </div>
                                <input type="text" name="name" value="<?= esc($user['name'] ?? $user['username']) ?>" required
                                    class="w-full bg-slate-800/50 border border-slate-700 text-white rounded-xl pl-10 pr-10 py-3.5 text-sm font-medium focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all placeholder:text-slate-600">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-pen text-slate-500 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="group">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Alamat Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-envelope text-slate-500 group-focus-within:text-teal-400 transition-colors"></i>
                                </div>
                                <input type="email" name="email" value="<?= esc($user['email'] ?? '') ?>" required
                                    class="w-full bg-slate-800/50 border border-slate-700 text-white rounded-xl pl-10 pr-10 py-3.5 text-sm font-medium focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all placeholder:text-slate-600">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-pen text-slate-500 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="pt-4 border-t border-slate-800/50">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Password Baru <span class="text-slate-600 font-normal normal-case">(Opsional)</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-key text-slate-500 group-focus-within:text-teal-400 transition-colors"></i>
                                </div>
                                <input type="password" name="password" placeholder="Biarkan kosong jika tidak ingin mengubah password"
                                    class="w-full bg-slate-800/50 border border-slate-700 text-white rounded-xl pl-10 pr-4 py-3.5 text-sm font-medium focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all placeholder:text-slate-600">
                            </div>
                        </div>

                        <div class="pt-6 flex justify-end gap-3">
                            <button type="button" onclick="history.back()" class="px-6 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-sm font-bold transition-all">
                                Batal
                            </button>
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-400 hover:to-emerald-400 text-white rounded-xl text-sm font-bold shadow-lg shadow-teal-500/20 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Scripts for Image Handling -->
<!-- OTP Verification Modal -->
<div id="otpModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm transition-opacity" onclick="closeOtpModal()"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-6">
        <div class="glass-card bg-slate-900 border border-slate-700 shadow-2xl rounded-2xl relative overflow-hidden">
            <!-- Modal Header -->
            <div class="text-center mb-6">
                <div class="w-16 h-16 rounded-full bg-teal-500/10 flex items-center justify-center mx-auto mb-4 animate-pulse">
                    <i class="fa-solid fa-envelope-circle-check text-3xl text-teal-400"></i>
                </div>
                <h3 class="text-xl font-bold text-white">Verifikasi Email</h3>
                <p class="text-slate-400 text-sm mt-2">Kode OTP telah dikirim ke email baru Anda.</p>
                <p class="text-xs text-slate-500 mt-1">(Cek console/alert untuk mode simulasi)</p>
            </div>

            <!-- OTP Input -->
            <div class="space-y-4">
                <input type="text" id="otpInput" maxlength="6" placeholder="Masukkan 6 Digit OTP" 
                    class="w-full bg-slate-950/50 border border-slate-700 text-white text-center text-2xl tracking-[0.5em] rounded-xl py-3 focus:ring-2 focus:ring-teal-500/50 focus:border-teal-500 outline-none transition-all placeholder:text-slate-700 placeholder:text-sm placeholder:tracking-normal">
                
                <button type="button" onclick="verifyOtp()" id="btnVerify"
                    class="w-full py-3.5 bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-400 hover:to-emerald-400 text-white font-bold rounded-xl shadow-lg shadow-teal-500/20 transition-all transform hover:-translate-y-0.5">
                    Verifikasi
                </button>
            </div>

            <!-- Close Button -->
            <button onclick="closeOtpModal()" class="absolute top-4 right-4 text-slate-500 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<!-- Scripts for Image Handling & Email Verification -->
<?= $this->section('scripts') ?>
<script>
let originalEmail = "<?= esc($user['email'] ?? '') ?>";
let isEmailVerified = false;

$(document).ready(function() {
    // 1. Photo Upload Logic
    $('#photoUpload').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#profilePhotoContainer').html(`<img src="${e.target.result}" class="w-full h-full object-cover">`);
            }
            reader.readAsDataURL(file);

            const formData = new FormData();
            formData.append('photo', file);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            const loadingHtml = `<div class="absolute inset-0 bg-black/50 flex items-center justify-center z-50"><i class="fa-solid fa-spinner fa-spin text-white"></i></div>`;
            $('#profilePhotoContainer').append(loadingHtml);

            $.ajax({
                url: '<?= base_url('admin/entry/upload-photo') ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status === 'success') {
                        $('.sidebar-profile-photo').attr('src', res.photo); 
                        $('#profilePhotoContainer').html(`<img src="${res.photo}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 flex items-center justify-center bg-emerald-500/80 animate-fade-out pointer-events-none">
                                <i class="fa-solid fa-check text-white text-3xl"></i>
                            </div>
                        `);
                    } else {
                        alert(res.message || 'Gagal mengunggah foto');
                        location.reload();
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan koneksi.');
                    location.reload();
                }
            });
        }
    });

    // 2. Profile Form Interception
    $('form[action*="update-profile"]').on('submit', function(e) {
        const currentEmail = $('input[name="email"]').val();

        // If email changed and NOT yet verified -> Prevent Submit & Start OTP Flow
        if (currentEmail !== originalEmail && !isEmailVerified) {
            e.preventDefault();
            requestVerification(currentEmail);
        }
        // If email same OR already verified -> Allow Form Submit (to update name/password)
    });

    // Add CSS animations
    $('head').append(`
        <style>
            @keyframes fade-out { 0% { opacity: 1; } 100% { opacity: 0; } }
            .animate-fade-out { animation: fade-out 2s forwards; }
        </style>
    `);
});

// FUNCTIONS

function requestVerification(email) {
    // Show loading state on button
    const btnParam = $('button[type="submit"]');
    const originalText = btnParam.text();
    btnParam.html('<i class="fa-solid fa-spinner fa-spin"></i> Memproses...').prop('disabled', true);

    $.ajax({
        url: '<?= base_url('admin/entry/request-verification') ?>',
        type: 'POST',
        data: {
            email: email,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        },
        success: function(res) {
            btnParam.html(originalText).prop('disabled', false);
            
            if (res.status === 'success') {
                // Show Modal
                $('#otpModal').removeClass('hidden');
                
                // SIMULATION MODE ALERT
                alert("SIMULASI OTP: Kode Anda adalah " + res.debug_otp);
                console.log("OTP Code:", res.debug_otp);
            } else {
                alert(res.message);
            }
        },
        error: function() {
            btnParam.html(originalText).prop('disabled', false);
            alert('Gagal menghubungi server.');
        }
    });
}

function verifyOtp() {
    const otp = $('#otpInput').val();
    const btn = $('#btnVerify');
    
    if (otp.length < 6) {
        alert('Masukkan 6 digit kode OTP');
        return;
    }

    btn.html('<i class="fa-solid fa-spinner fa-spin"></i>').prop('disabled', true);

    $.ajax({
        url: '<?= base_url('admin/entry/verify-otp') ?>',
        type: 'POST',
        data: {
            otp: otp,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        },
        success: function(res) {
            btn.html('Verifikasi').prop('disabled', false);

            if (res.status === 'success') {
                alert(res.message);
                isEmailVerified = true; // Mark as verified
                closeOtpModal();
                
                // Submit main form to update Session Name & Password if any
                $('form[action*="update-profile"]').unbind('submit').submit();
            } else {
                alert(res.message);
            }
        },
        error: function() {
            btn.html('Verifikasi').prop('disabled', false);
            alert('Gagal verifikasi OTP.');
        }
    });
}

function closeOtpModal() {
    $('#otpModal').addClass('hidden');
    $('#otpInput').val('');
}
</script>
<?= $this->endSection() ?>
