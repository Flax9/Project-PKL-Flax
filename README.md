# E-Kinerja BBPOM - Web Monitoring System

Aplikasi Web Monitoring E-Kinerja BBPOM adalah platform terpadu yang digunakan untuk mengelola, memantau, dan memverifikasi capaian kinerja organisasi yang meliputi IKU (Indikator Kinerja Utama), NKO (Nilai Kinerja Organisasi), Anggaran, dan Capaian Output.

## 🌟 Fitur Utama Aplikasi

### 1. Dashboard Monitoring Interaktif
- Visualisasi raihan target kinerja, anggaran, dan capaian realisasi (Output) ke dalam diagram, scorecard, dan grafik interaktif.
- Pemfilteran data secara dinamis berdasarkan periode waktu filter (Bulan/Triwulan) atau nama indikator spesifik.

### 2. Modul Entri & Manajemen Data (Admin)
- **Smart Data Entry:** Penambahan atau modifikasi data (IKU, NKO, Anggaran, Capaian Output) dengan fitur *auto-fill* berbasis AJAX (misal: input Nomor IKU otomatis mengisi Nama Indikator yang sesuai).
- **Import Massal Excel/CSV:** Dukungan *import* data berjumlah banyak menggunakan antarmuka modern yang memvalidasi data lewat *staging-area* sebelum masuk ke penyimpanan permanen (*batch insert*).
- **Modifikasi Data Rutin:** Penyesuaian revisi data operasional harian yang terkendali.

### 3. Sistem Pengajuan Perubahan Data (Approval Workflow)
- Proses dan alur pengajuan resmi jika entitas sub-konteks memerlukan penyesuaian/revisi terhadap data yang telah dilaporkan di bulan sebelumnya.
- Validasi multi-level (Pemeriksaan & Validasi oleh tim Perencana).
- Fasilitas unggah dokumen wajib sebagai otentikasi perubahan (Draft/Bahan Roren, Disposisi, bukti E-Performance).

### 4. Keamanan Cerdas & Kontrol Akses
- **Gateway Authenticator:** Pengamanan ketat menggunakan `AuthFilter` yang melindungi rute sensitif/admin, memitigasi kemungkinan *Broken Access Control* atau IDOR.
- **Email OTP Service:** Integrasi layanan validasi keamanan *One-Time Password* (OTP) yang dikirimkan melewati email untuk verifikasi ekstra atau update krusial profil.

### 5. Notifikasi Terpusat & Integrasi
- **In-App Notification:** Notifikasi penanda status *real-time* kepada user atas status persetujuan pengajuan (apakah disetujui, diproses, atau ditolak).
- **Bot Telegram Terintegrasi (Webhook):** Kapabilitas untuk menerima notifikasi sistem instan memotong latensi respons via Telegram App.

### 6. Pengaturan Profil
- Kelola profil pengguna dengan fitur update data diri lengkap dengan fasilitas penggantian foto profil.

---

## ⚙️ Panduan Instalasi & Konfigurasi

Berikut adalah langkah-langkah untuk menjalankan aplikasi ini di lingkungan lokal (XAMPP/Laragon).

## 1. Konfigurasi PHP (XAMPP)

Aplikasi ini memerlukan beberapa ekstensi PHP aktif. Silakan buka file `php.ini` pada konfigurasi Apache di XAMPP Anda, lalu **hilangkan tanda titik koma (;)** di depan baris-baris berikut:

### a. Database & Persistence
Digunakan untuk komunikasi dengan server database.
```ini
extension=mysqli      ; Driver utama untuk koneksi ke MySQL
extension=pdo_mysql   ; Interface PDO untuk akses database MySQL
extension=pdo_sqlite  ; (Opsional) Jika sistem memerlukan database file-based
```

### b. Security & Networking
Penting untuk enkripsi data dan integrasi API.
```ini
extension=openssl     ; Menangani protokol HTTPS dan enkripsi
extension=curl        ; Library untuk HTTP Request (API)
extension=ftp         ; (Opsional) Operasi transfer file
```

### c. Data Processing & Localization
Menangani string, format angka, dan bahasa.
```ini
extension=mbstring    ; String multibita (krusial untuk validasi)
extension=intl        ; Format angka, mata uang, dan tanggal
extension=gettext     ; Fitur lokalisasi/multibahasa
extension=bz2         ; Kompresi file
extension=zip         ; Ekstraksi file arsip
```

### d. Media & Metadata
Untuk pengelolaan gambar dan dashboard.
```ini
extension=gd          ; Pemrosesan gambar (resize, crop, chart)
extension=fileinfo    ; Deteksi tipe file (MIME type) amam
extension=exif        ; Membaca metadata gambar
```

---

## 2. Setup Database

1.  Pastikan MySQL/MariaDB sudah berjalan di XAMPP.
2.  Buat database baru (misal: `db_monitoring_bpom`).
3.  **Import** file SQL bawaan: `db_monitoring_bpom.sql` ke dalam database tersebut.
4.  *(Opsional)* Admin sudah menyertakan file migrasi untuk struktur terbaru.

## 3. Menjalankan Aplikasi

Buka terminal di root project, lalu jalankan perintah:

```bash
php spark serve --port 8081
```

Akses website di: [http://localhost:8081](http://localhost:8081)

---

## 4. Struktur Project

Berikut adalah struktur folder utama aplikasi:

```text
.
├── app
│   ├── Config
│   │   └── DataMapping.php
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
│   │   └── Migrations
│   ├── Models
│   │   ├── Entry
│   │   │   ├── AnggaranEntryModel.php
│   │   │   ├── CapaianOutputEntryModel.php
│   │   │   ├── IkuEntryModel.php
│   │   │   └── NkoEntryModel.php
│   │   ├── AnggaranModel.php
│   │   ├── CapaianOutputModel.php
│   │   ├── IkuModel.php
│   │   └── PengajuanModel.php
│   └── Views
│       ├── admin
│       ├── anggaran
│       ├── capaian_output
│       ├── dashboard
│       └── layout
├── public
│   └── assets
│       ├── css
│       ├── img
│       └── js
└── .env
```
