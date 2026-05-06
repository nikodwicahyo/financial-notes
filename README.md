# 💰 Aplikasi Catatan Keuangan Sederhana

Aplikasi berbasis web untuk mencatat, mengelola, dan memantau pemasukan serta pengeluaran harian secara terstruktur. Dibangun menggunakan **CodeIgniter 4** dan **MySQL** dengan tampilan modern menggunakan **Bootstrap 5** dan visualisasi data interaktif dengan **Chart.js**.

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4-EF4223?logo=codeigniter)
![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?logo=mysql)

---

## ✨ Fitur Utama

- ✅ **Manajemen Transaksi (CRUD)** - Tambah, lihat, edit, dan hapus transaksi pemasukan/pengeluaran
- ✅ **Kategorisasi Transaksi** - Organisir transaksi berdasarkan kategori (Makan, Transport, Gaji, dll)
- ✅ **Dashboard Interaktif** - Ringkasan keuangan dengan total saldo, pemasukan, dan pengeluaran
- ✅ **Grafik Visualisasi** - Bar chart dan doughnut chart menggunakan Chart.js
- ✅ **Laporan Keuangan** - Filter transaksi berdasarkan periode dan kategori
- ✅ **Manajemen Kategori** - Kelola kategori transaksi dengan ikon Bootstrap Icons
- ✅ **Validasi Form** - Validasi sisi server untuk semua input
- ✅ **Keamanan** - CSRF protection dan XSS prevention
- ✅ **Responsive Design** - Tampilan menyesuaikan desktop dan tablet
- ✅ **Pagination** - Daftar transaksi dengan pagination (10 per halaman)

---

## 🛠️ Teknologi yang Digunakan

| Layer | Teknologi |
|-------|-----------|
| **Backend Framework** | CodeIgniter 4 (PHP 8.2+) |
| **Database** | MySQL 8.0+ |
| **Frontend UI** | Bootstrap 5.3 |
| **Grafik** | Chart.js 4.x |
| **Alert Dialog** | SweetAlert2 |
| **Icons** | Bootstrap Icons |

---

## 📋 Prasyarat

Sebelum instalasi, pastikan sistem Anda memiliki:

- **PHP 8.2 atau lebih tinggi** dengan ekstensi:
  - `intl`
  - `mbstring`
  - `curl`
  - `pdo_mysql`
  - `xml`
  - `json` (enabled by default)
- **MySQL 8.0 atau lebih tinggi**
- **Composer** (Dependency Manager untuk PHP)
- **Web Server** (Apache/Nginx) atau **Laragon/XAMPP**
- **Git** (untuk version control)

---

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/nikodwicahyo/financial-notes.git
cd financial-notes
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp env .env
```

Atau di Windows:

```bash
copy env .env
```

### 4. Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi database:

```env
# ENVIRONMENT
CI_ENVIRONMENT = development

# DATABASE
database.default.hostname = localhost
database.default.database = keuangan_db
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.port     = 3306
```

### 5. Buat Database

Buka phpMyAdmin atau MySQL CLI, lalu jalankan:

```sql
CREATE DATABASE keuangan_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Jalankan Migration

```bash
php spark migrate
```

Migration akan membuat 2 tabel:
- `categories` - Menyimpan kategori transaksi
- `transactions` - Menyimpan data transaksi

### 7. Jalankan Seeder (Data Awal)

```bash
php spark db:seed CategorySeeder
```

Seeder akan mengisi 8 kategori default:
- Makan & Minum
- Transport
- Belanja
- Kesehatan
- Hiburan
- Gaji
- Freelance
- Lainnya

### 8. Jalankan Aplikasi

Gunakan built-in PHP server:

```bash
php spark serve
```

Atau akses melalui web server lokal (Laragon/XAMPP):

```
http://localhost/keuangan-app/public
```

Aplikasi siap digunakan! 🎉

---

## 📁 Struktur Direktori

```
keuangan-app/
├── app/
│   ├── Config/
│   │   ├── Routes.php          # Routing aplikasi
│   │   ├── Database.php        # Konfigurasi database
│   │   └── Security.php        # Konfigurasi keamanan
│   ├── Controllers/
│   │   ├── Dashboard.php       # Controller dashboard
│   │   ├── Transactions.php    # Controller transaksi
│   │   ├── Categories.php      # Controller kategori
│   │   └── Reports.php         # Controller laporan
│   ├── Models/
│   │   ├── TransactionModel.php # Model transaksi
│   │   └── CategoryModel.php    # Model kategori
│   ├── Views/
│   │   ├── layouts/
│   │   │   └── main.php        # Layout utama
│   │   ├── dashboard/
│   │   │   └── index.php       # View dashboard
│   │   ├── transactions/
│   │   │   ├── index.php       # Daftar transaksi
│   │   │   ├── create.php      # Form tambah transaksi
│   │   │   └── edit.php        # Form edit transaksi
│   │   ├── categories/
│   │   │   ├── index.php       # Daftar kategori
│   │   │   └── form.php        # Form kategori
│   │   └── reports/
│   │       └── index.php       # Laporan keuangan
│   └── Database/
│       ├── Migrations/
│       │   ├── CreateCategoriesTable.php
│       │   └── CreateTransactionsTable.php
│       └── Seeds/
│           └── CategorySeeder.php
├── public/
│   ├── assets/
│   │   └── css/
│   │       └── style.css       # Custom CSS
│   └── index.php               # Entry point
├── writable/                   # Cache, logs, session
├── .env                        # Environment config (tidak di-commit)
├── composer.json               # Dependencies
└── README.md                   # Dokumentasi ini
```

## 🔒 Keamanan

Aplikasi ini menerapkan praktik keamanan dasar:

- **CSRF Protection** - Semua form dilindungi CSRF token
- **XSS Prevention** - Output data menggunakan `esc()` helper
- **SQL Injection Prevention** - Query menggunakan Query Builder CI4
- **Input Validation** - Validasi sisi server untuk semua input
- **Password Hashing** - (Untuk fitur login di versi mendatang)

---

## 🧪 Testing

Aplikasi telah melalui testing manual meliputi:

✅ Functional testing semua modul (Dashboard, Transaksi, Kategori, Laporan)  
✅ Validasi form dan error handling  
✅ Filter dan pagination  
✅ Keamanan (CSRF, XSS)  
✅ Cross-browser testing (Chrome, Firefox, Edge)  
✅ Responsive design (Desktop & Tablet)

---

## 🗄️ Struktur Database

### Tabel `categories`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK, AI) | ID kategori |
| name | VARCHAR(100) | Nama kategori |
| icon | VARCHAR(50) | Ikon Bootstrap Icons |
| created_at | DATETIME | Tanggal dibuat |

### Tabel `transactions`

| Field | Type | Description |
|-------|------|-------------|
| id | INT (PK, AI) | ID transaksi |
| title | VARCHAR(150) | Judul transaksi |
| type | ENUM('income','expense') | Jenis transaksi |
| amount | DECIMAL(15,2) | Jumlah nominal |
| category_id | INT (FK) | ID kategori |
| transaction_date | DATE | Tanggal transaksi |
| note | TEXT | Catatan (opsional) |
| created_at | DATETIME | Tanggal dibuat |
| updated_at | DATETIME | Tanggal diupdate |

**Relasi:** `transactions.category_id` → `categories.id` (RESTRICT, CASCADE)

---

## 🔄 Reset Database

Jika ingin mereset database ke kondisi awal:

```bash
# Rollback semua migration
php spark migrate:rollback

# Jalankan ulang migration
php spark migrate

# Jalankan seeder
php spark db:seed CategorySeeder
```

## 👨‍💻 Kontributor

Dikembangkan sebagai studi kasus pembelajaran CodeIgniter 4 untuk manajemen keuangan personal.
Dibuat oleh Niko Dwicahyo Widiyanto

---

**Selamat mencatat keuangan! 💰📊**
