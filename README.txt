Tree Folder
app/
├── Controllers/
│   ├── Dashboard.php       <-- (Fokus ke IKU)
│   └── Ro.php              <-- (Baru: Fokus ke Rincian Output)
├── Views/
│   ├── layout/
│   │   └── main.php        <-- (Template Utama: Sidebar & Scripts)
│   ├── dashboard/          <-- (Folder khusus IKU)
│   │   ├── index.php
│   │   └── partials/
│   │       ├── header.php
│   │       ├── sidebar.php
│   │       ├── summary_cards.php
│   │       ├── charts_trend.php
│   │       ├── charts_donut.php
│   │       ├── charts_rank.php
│   │       └── table_realtime.php
│   └── ro/                 <-- (Baru: Folder khusus Rincian Output)
│       ├── index.php       <-- (View Utama RO)
│       └── partials/       <-- (Komponen khusus RO)
│           ├── header_ro.php
│           ├── summary_cards_ro.php
│           └── charts_accumulative.php
public/
└── assets/
    └── js/
        └── dashboard/
            ├── index.js
            ├── ro_logic.js <-- (Baru: Logika khusus RO)
            └── charts/
                ├── trendChart.js
                ├── accumulativeChart.js <-- (Baru: Untuk RO)
                └── ...