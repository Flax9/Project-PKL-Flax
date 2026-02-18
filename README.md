# Panduan Instalasi & Konfigurasi E-Kinera BBPOM

Berikut adalah langkah-langkah untuk menjalankan aplikasi ini di lingkungan lokal (XAMPP).

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
│   │   └── ...
│   ├── Models
│   │   ├── Entry
│   │   │   ├── AnggaranEntryModel.php
│   │   │   ├── CapaianOutputEntryModel.php
│   │   │   ├── IkuEntryModel.php
│   │   │   └── NkoEntryModel.php
│   │   └── ...
│   └── Views
│       ├── admin
│       ├── dashboard
│       └── ...
├── public
│   └── assets
│       ├── css
│       ├── img
│       └── js
└── .env
```
