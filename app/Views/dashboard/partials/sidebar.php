<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 transition-colors duration-300 transform -translate-x-full md:translate-x-0 md:static md:inset-auto flex flex-col shrink-0">
    <a href="<?= base_url('dashboard') ?>" class="p-6 flex items-center gap-4 group hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors duration-300">
        <div class="w-16 h-16 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-full flex items-center justify-center shadow-md backdrop-blur-sm group-hover:border-teal-500/30 group-hover:shadow-teal-500/20 transition-all duration-300">
            <img src="<?= base_url('assets/img/logo_bpom_1.png') ?>" 
                alt="Logo BPOM" 
                class="w-10 h-10 object-contain group-hover:scale-110 transition-transform duration-300"> 
        </div>
        
        <div>
            <h1 class="font-bold text-slate-800 dark:text-white text-base tracking-widest leading-tight group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors duration-300">INSIGHT</h1>
            <p class="text-[10px] font-medium text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] mt-1 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors duration-300">BBPOM Surabaya</p>
        </div>
    </a>

    <nav class="flex-1 px-4 py-4 space-y-2">
        
        <a href="<?= base_url('dashboard') ?>" 
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group <?= ($activeMenu ?? '') == 'dashboard' ? 'bg-slate-100 dark:bg-slate-800 text-teal-600 dark:text-teal-400 border border-slate-200 dark:border-slate-700 shadow-sm dark:shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white' ?>">
            <i class="fa-solid fa-gauge-high <?= ($activeMenu ?? '') == 'dashboard' ? 'text-teal-600 dark:text-teal-400' : 'group-hover:text-slate-800 dark:group-hover:text-white' ?>"></i>
            <span class="font-medium text-sm">Indikator Kinerja Utama</span>
        </a>

        <!--
        <a href="#" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group <?= ($activeMenu == 'perjanjian_kinerja') ? 'bg-slate-100 dark:bg-slate-800 text-teal-600 dark:text-teal-400 border border-slate-200 dark:border-slate-700 shadow-sm dark:shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white' ?>">
            <i class="fa-solid fa-file-signature <?= ($activeMenu == 'perjanjian_kinerja') ? 'text-teal-600 dark:text-teal-400' : 'group-hover:text-slate-800 dark:group-hover:text-white' ?>"></i>
            <span class="font-medium text-sm">Perjanjian Kinerja</span>
        </a>
        -->

        <a href="<?= base_url('capaianoutput') ?>" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group <?= ($activeMenu == 'capaian_output') ? 'bg-slate-100 dark:bg-slate-800 text-teal-600 dark:text-teal-400 border border-slate-200 dark:border-slate-700 shadow-sm dark:shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white' ?>">
            <i class="fa-solid fa-list-check <?= ($activeMenu == 'capaian_output') ? 'text-teal-600 dark:text-teal-400' : 'group-hover:text-slate-800 dark:group-hover:text-white' ?>"></i>
            <span class="font-medium text-sm">Capaian Output</span>
        </a>

        <!--
        <a href="#" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group <?= ($activeMenu == 'database_capaian') ? 'bg-slate-100 dark:bg-slate-800 text-teal-600 dark:text-teal-400 border border-slate-200 dark:border-slate-700 shadow-sm dark:shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white' ?>">
            <i class="fa-solid fa-database <?= ($activeMenu == 'database_capaian') ? 'text-teal-600 dark:text-teal-400' : 'group-hover:text-slate-800 dark:group-hover:text-white' ?>"></i>
            <span class="font-medium text-sm">Database Capaian</span>
        </a>
        -->

        <a href="<?= base_url('anggaran') ?>" 
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group <?= ($activeMenu == 'anggaran') ? 'bg-slate-100 dark:bg-slate-800 text-teal-600 dark:text-teal-400 border border-slate-200 dark:border-slate-700 shadow-sm dark:shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white' ?>">
                <i class="fa-solid fa-money-bill-wave <?= ($activeMenu == 'anggaran') ? 'text-teal-600 dark:text-teal-400' : 'group-hover:text-slate-800 dark:group-hover:text-white' ?>"></i>
                <span class="font-medium text-sm">Anggaran</span>
        </a>

        <a href="<?= base_url('admin/entry/verify') ?>"
        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group <?= (url_is('admin/entry*')) ? 'bg-slate-100 dark:bg-slate-800 text-teal-600 dark:text-teal-400 border border-slate-200 dark:border-slate-700 shadow-sm dark:shadow-md' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-800 dark:hover:text-white' ?>">
            <div class="flex items-center justify-center w-5">
                <i class="fa-solid fa-pen-to-square text-sm"></i>
            </div>
            <span class="text-sm font-medium">Data Manajemen</span>
        </a>
    </nav>

    <!-- Theme Toggle -->
    <div class="px-4 py-4 mt-auto  transition-colors duration-300">
        <label for="themeToggleCheckbox" class="flex items-center justify-between px-4 py-3 rounded-2xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/40 dark:hover:bg-slate-800/60 transition-all duration-300 group cursor-pointer">
            <div class="flex flex-col items-start gap-0.5">
                <span class="text-xs font-bold text-slate-800 dark:text-white transition-colors" id="themeToggleText">Light Mode</span>
                <span class="text-[9px] text-slate-500 dark:text-slate-400">Tema Tampilan</span>
            </div>
            
            <div class="relative flex-shrink-0 w-[52px] h-7 rounded-full transition-colors duration-500 bg-orange-500 dark:bg-slate-900 shadow-inner overflow-hidden flex items-center group-hover:shadow-[0_0_8px_rgba(249,115,22,0.3)] dark:group-hover:shadow-[0_0_8px_rgba(0,0,0,0.5)] border border-orange-400 dark:border-black">
                <!-- Hidden Checkbox -->
                <input type="checkbox" id="themeToggleCheckbox" class="hidden" />

                <!-- Sun Icon (Light Mode - Kiri) -->
                <i class="fa-solid fa-sun absolute left-2 text-white text-[13px] opacity-100 dark:opacity-0 transition-opacity duration-300"></i>
                
                <!-- Moon Icon (Dark Mode - Kanan) -->
                <i class="fa-solid fa-moon absolute right-2 text-white text-[13px] opacity-0 dark:opacity-100 transition-opacity duration-300 transform -scale-x-100"></i>
                
                <!-- Sliding Knob (Always White) -->
                <div class="absolute w-[22px] h-[22px] rounded-full shadow-md bg-white transition-all duration-500 transform translate-x-[26px] dark:translate-x-[2px]"></div>
            </div>
        </label>
    </div>

    <div class="p-4 border-t border-slate-200 dark:border-slate-800 transition-colors duration-300">
        <div class="flex items-center w-full min-w-0">
            <a href="<?= base_url('admin/profile') ?>" class="flex items-center gap-3 px-2 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-300 group flex-1 overflow-hidden" style="min-width: 0;">
            <?php if (session()->get('photo')): ?>
                <img src="<?= base_url('uploads/profile/' . session()->get('photo')) ?>" class="w-8 h-8 rounded-full object-cover border border-slate-300 dark:border-slate-600 shadow-sm group-hover:border-teal-500/50 group-hover:shadow-teal-500/20 transition-all shrink-0">
            <?php else: ?>
                <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-teal-600 dark:text-teal-400 border border-slate-300 dark:border-slate-600 shadow-sm group-hover:border-teal-500/50 group-hover:shadow-teal-500/20 transition-all shrink-0">
                    <i class="fa-solid fa-user-tie text-xs"></i>
                </div>
            <?php endif; ?>
                <div class="overflow-hidden text-left flex-1 min-w-0">
                    <?php if (session()->get('isLoggedIn')): ?>
                        <p class="text-xs text-slate-800 dark:text-white font-medium truncate group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors"><?= esc(session()->get('name') ?? session()->get('username')) ?></p>
                        <p class="text-[9px] text-slate-500 uppercase tracking-widest truncate"><?= esc(session()->get('role')) ?></p>
                    <?php else: ?>
                        <p class="text-xs text-slate-800 dark:text-white font-medium truncate group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">Admin BBPOM</p>
                        <p class="text-[9px] text-slate-500 uppercase tracking-widest truncate">Dashboard View</p>
                    <?php endif; ?>
                </div>
            </a>

            <!-- Notification Bell Component -->
            <div class="relative flex-none ml-1">
                <button type="button" id="notif-btn" class="relative p-2 text-slate-500 hover:text-teal-600 dark:text-slate-400 dark:hover:text-teal-400 focus:outline-none transition-colors rounded-full hover:bg-slate-100 dark:hover:bg-slate-800">
                    <i class="fa-solid fa-bell"></i>
                    <!-- Notif Badge -->
                    <span id="notif-badge" class="absolute top-1 right-1 flex items-center justify-center w-4 h-4 text-[9px] font-bold text-white bg-red-500 rounded-full border border-white dark:border-slate-800 hidden">
                        0
                    </span>
                </button>

                <!-- Dropdown -->
                <div id="notif-dropdown" class="absolute bottom-full mb-2 left-0 w-72 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 hidden z-50 overflow-hidden transform scale-95 opacity-0 transition-all duration-200 origin-bottom-left">
                    <div class="p-3 border-b border-slate-100 dark:border-slate-700/50 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
                        <h3 class="text-xs font-semibold text-slate-800 dark:text-white">Notifikasi</h3>
                    </div>
                    <div id="notif-list" class="max-h-64 overflow-y-auto w-full">
                        <?php if (session()->get('isLoggedIn')): ?>
                        <div class="p-4 text-center text-xs text-slate-500 dark:text-slate-400">
                            Memuat...
                        </div>
                        <?php else: ?>
                        <div class="p-6 text-center text-xs text-slate-500 dark:text-slate-400 flex flex-col items-center justify-center">
                            <i class="fa-solid fa-lock text-3xl mb-3 text-slate-300 dark:text-slate-600"></i>
                            <span class="block font-semibold mb-1">Akses Terkunci</span>
                            <span>Silakan login terlebih dahulu untuk melihat notifikasi Anda.</span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const notifBtn = document.getElementById('notif-btn');
                const notifDropdown = document.getElementById('notif-dropdown');
                const notifBadge = document.getElementById('notif-badge');
                const notifList = document.getElementById('notif-list');

                if (notifBtn) {
                    <?php if (session()->get('isLoggedIn')): ?>
                    // Fetch notifications initially and then every 30 seconds
                    fetchNotifications();
                    setInterval(fetchNotifications, 30000);
                    <?php endif; ?>

                    // Toggle Dropdown
                    notifBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        // Toggle classes for animation
                        if (notifDropdown.classList.contains('hidden')) {
                            notifDropdown.classList.remove('hidden');
                            setTimeout(() => {
                                notifDropdown.classList.remove('scale-95', 'opacity-0');
                                notifDropdown.classList.add('scale-100', 'opacity-100');
                            }, 10);
                        } else {
                            closeNotifDropdown();
                        }
                    });

                    // Close window on outside click
                    document.addEventListener('click', function(e) {
                        if (!notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
                            closeNotifDropdown();
                        }
                    });

                    function closeNotifDropdown() {
                        notifDropdown.classList.remove('scale-100', 'opacity-100');
                        notifDropdown.classList.add('scale-95', 'opacity-0');
                        setTimeout(() => {
                            notifDropdown.classList.add('hidden');
                        }, 200); // Matches transition duration
                    }

                    function fetchNotifications() {
                        fetch('<?= base_url('admin/notifications/unread') ?>', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                // Update Badge
                                if (data.unread_count > 0) {
                                    notifBadge.textContent = data.unread_count > 9 ? '9+' : data.unread_count;
                                    notifBadge.classList.remove('hidden');
                                } else {
                                    notifBadge.classList.add('hidden');
                                }

                                // Update List
                                if (data.notifications.length > 0) {
                                    let html = '';
                                    data.notifications.forEach(notif => {
                                        const isUnread = notif.is_read == 0;
                                        const date = new Date(notif.created_at).toLocaleDateString('id-ID', {
                                            day: 'numeric', month: 'short', hour: '2-digit', minute:'2-digit'
                                        });
                                        
                                        html += `
                                            <a href="<?= base_url('admin/notifications/read/') ?>${notif.id}" class="block p-3 border-b border-slate-100 dark:border-slate-700/50 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors ${isUnread ? 'bg-teal-50/30 dark:bg-teal-900/10' : ''}">
                                                <div class="flex items-start gap-3">
                                                    <div class="flex-shrink-0 mt-0.5">
                                                        <div class="w-2 h-2 mt-1 rounded-full ${isUnread ? 'bg-teal-500' : 'bg-transparent'}"></div>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-xs font-semibold text-slate-800 dark:text-white truncate ${isUnread ? '' : 'text-slate-600 dark:text-slate-300'}">
                                                            ${notif.title}
                                                        </p>
                                                        <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2 leading-snug">
                                                            ${notif.message}
                                                        </p>
                                                        <p class="text-[9px] text-slate-400 dark:text-slate-500 mt-1">
                                                            ${date}
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                        `;
                                    });
                                    notifList.innerHTML = html;
                                } else {
                                    notifList.innerHTML = `
                                        <div class="p-6 text-center flex flex-col items-center justify-center gap-2">
                                            <i class="fa-regular fa-bell-slash text-slate-300 dark:text-slate-600 text-2xl"></i>
                                            <p class="text-[10px] text-slate-500 dark:text-slate-400">Tidak ada notifikasi terkini</p>
                                        </div>
                                    `;
                                }
                            } else {
                                notifList.innerHTML = `
                                    <div class="p-6 text-center flex flex-col items-center justify-center gap-2">
                                        <i class="fa-regular fa-circle-xmark text-red-300 dark:text-red-900/50 text-2xl"></i>
                                        <p class="text-[10px] text-red-500 dark:text-red-400">${data.message || 'Gagal memuat notifikasi'}</p>
                                    </div>
                                `;
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching notifications:', error);
                            notifList.innerHTML = `
                                <div class="p-6 text-center flex flex-col items-center justify-center gap-2">
                                    <i class="fa-regular fa-circle-xmark text-red-300 dark:text-red-900/50 text-2xl"></i>
                                    <p class="text-[10px] text-red-500 dark:text-red-400">Gagal terhubung ke server</p>
                                </div>
                            `;
                        });
                    }
                }
            });
        </script>
    </div>
</aside>