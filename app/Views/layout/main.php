<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard IKU 2025' ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard/base.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard/charts.css') ?>">

    <?= $this->renderSection('styles') ?>

</head>
<body class="bg-slate-950 text-slate-300 flex h-screen overflow-hidden relative">
    
        
    <?= $this->include('dashboard/partials/sidebar') ?>

    <!-- Mobile Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-40 hidden transition-opacity duration-300"></div>

    <div class="flex-1 flex flex-col min-w-0 relative">
        <!-- Ambient Background -->
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-teal-500/10 blur-[150px] rounded-full -translate-y-1/2 translate-x-1/2 pointer-events-none z-0"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-indigo-500/10 blur-[150px] rounded-full translate-y-1/2 -translate-x-1/2 pointer-events-none z-0"></div>

        <!-- Content Area -->
        <div class="flex-1 flex flex-col relative z-10 overflow-hidden">
            <!-- Mobile Header Bar -->
            <div class="md:hidden flex items-center justify-between p-4 bg-slate-900 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <img src="<?= base_url('assets/img/logo_bpom_1.png') ?>" alt="Logo" class="h-8 w-auto object-contain">
                    <span class="font-bold text-white text-sm tracking-widest">E-KINERJA</span>
                </div>
                <button id="sidebarToggle" class="p-2 text-slate-400 hover:text-white transition-colors focus:outline-none">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>
            
            <?= $this->renderSection('content') ?>
        </div>

    </div>

    <!-- jQuery: required by some pages' inline scripts (no SRI to avoid blocking during local dev) -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function() {
            const $sidebar = $('#sidebar');
            const $overlay = $('#sidebarOverlay');
            const $toggle = $('#sidebarToggle');

            function toggleSidebar() {
                $sidebar.toggleClass('-translate-x-full');
                $overlay.toggleClass('hidden');
            }

            $toggle.on('click', function(e) {
                e.stopPropagation();
                toggleSidebar();
            });

            $overlay.on('click', toggleSidebar);

            // Close sidebar when clicking links on mobile
            $sidebar.find('a').on('click', function() {
                if ($(window).width() < 768) {
                    toggleSidebar();
                }
            });

            // Handle Resize
            $(window).on('resize', function() {
                if ($(window).width() >= 768) {
                    $sidebar.removeClass('-translate-x-full');
                    $overlay.addClass('hidden');
                } else {
                    $sidebar.addClass('-translate-x-full');
                }
            });
        });
    </script>

    <?= $this->renderSection('scripts') ?>

</body>
</html>