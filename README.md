***Penyesuaian file php.ini pada config app Xampp***
1. Buka file 'php.ini' pada config apache di xampp
2. 🧩 Required PHP Extensions
Aplikasi ini memerlukan beberapa ekstensi PHP aktif untuk menangani database, autentikasi, dan pemrosesan media. Pastikan baris-baris berikut tidak dikomentari (tanpa tanda ;) pada file php.ini Anda:

a. Database & Persistence
Digunakan untuk komunikasi dengan server database (MySQL/MariaDB) dan database lokal (SQLite).

extension=mysqli — Driver utama untuk koneksi ke MySQL.

extension=pdo_mysql — Interface PDO untuk akses database MySQL yang lebih aman.

extension=pdo_sqlite — Digunakan jika sistem memerlukan database file-based ringan.

b. Security & Networking
Penting untuk integrasi API eksternal dan enkripsi data.

extension=php_openssl.dll — Menangani protokol HTTPS dan enkripsi.

extension=curl — Library untuk melakukan HTTP Request (penting untuk konsumsi API).

extension=php_ftp.dll — Digunakan untuk operasi transfer file antar server.

c. Data Processing & Localization
Menangani manipulasi string kompleks, multibahasa, dan format data.

extension=mbstring — Menangani string multibita (sangat krusial untuk validasi input).

extension=intl — Internationalization support (format angka, mata uang, dan tanggal).

extension=gettext — Digunakan untuk fitur lokalisasi atau multibahasa.

extension=bz2 & extension=zip — Untuk kompresi dan ekstraksi file arsip.

d. Media & Metadata
Untuk pengelolaan dashboard yang melibatkan upload gambar atau pembacaan file.

extension=gd — Library pemrosesan gambar (resize, crop, atau generate chart).

extension=fileinfo — Mendeteksi tipe file (MIME type) secara akurat untuk keamanan upload.

extension=exif — Membaca metadata dari gambar (seperti data lokasi atau model kamera).

***DB yang Digunakan***
1. import data dummy terlebih dahulu ke dalam xampp anda dimana nama file merupakan "db_monitoring_bpom.sql"
2. admin sudah menambahkan file migrasi untuk mempermudah

***Start web CI4***
1. Pada terminal vscode bisa ketikkan "php spark serve --port 8081", routes sudah disesuaikan

***Tree Folder***
.
├── app
│   ├── Config
│   │   └── DataMapping.php  <-- Konfigurasi terpusat Nama Tabel & Header
│   ├── Controllers
│   │   ├── Admin
│   │   │   ├── Entry.php
│   │   │   └── Pengajuan.php
│   │   ├── Anggaran.php
│   │   ├── BaseController.php
│   │   ├── CapaianOutput.php
│   │   ├── Dashboard.php
│   │   └── Home.php
│   ├── Database
│   │   └── Migrations       <-- Skema Database
│   ├── Models
│   │   ├── Entry            <-- Model Khusus Import Data
│   │   │   ├── AnggaranEntryModel.php
│   │   │   ├── CapaianOutputEntryModel.php
│   │   │   ├── IkuEntryModel.php
│   │   │   └── NkoEntryModel.php
│   │   ├── AnggaranModel.php
│   │   ├── CapaianOutputModel.php
│   │   ├── IkuModel.php
│   │   └── PengajuanModel.php
│   └── Views
│       ├── admin            <-- View Halaman Admin
│       ├── anggaran
│       ├── capaian_output
│       ├── dashboard
│       └── layout           <-- Template Utama
├── public
│   └── assets
│       ├── css
│       ├── img
│       └── js
└── .env                     <-- Konfigurasi Environment
