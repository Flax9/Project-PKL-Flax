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

    <style>
        body { 
            font-family: 'Inter', sans-serif; 
        }
        /* Menghilangkan scrollbar default untuk tampilan dashboard yang clean */
        ::-webkit-scrollbar {
            width: 5px;
        }
        ::-webkit-scrollbar-track {
            background: #0f172a;
        }
        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-300 flex h-screen overflow-hidden">

    <?= $this->include('dashboard/partials/sidebar') ?>

    <div class="flex-1 flex flex-col min-w-0 relative">
        
        <?= $this->renderSection('content') ?>

    </div>

    <!-- jQuery: required by some pages' inline scripts (no SRI to avoid blocking during local dev) -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <?= $this->renderSection('scripts') ?>

</body>
</html>