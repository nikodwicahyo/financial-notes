# Product Requirements Document (PRD)
## Aplikasi Catatan Keuangan Sederhana
**Versi:** 1.0.0
**Tanggal:** 06 Mei 2026
**Framework:** CodeIgniter 4 + MySQL
**Status:** Draft

---

## 1. Ringkasan Produk

Aplikasi Catatan Keuangan Sederhana adalah aplikasi berbasis web yang memungkinkan pengguna mencatat, mengelola, dan memantau pemasukan serta pengeluaran harian secara terstruktur. Dibangun menggunakan CodeIgniter 4 sebagai backend framework dan MySQL sebagai database, aplikasi ini ditujukan untuk kebutuhan personal finance tracking yang ringan namun informatif.

---

## 2. Tujuan Produk

- Membantu pengguna mencatat transaksi keuangan (pemasukan & pengeluaran) secara mudah dan cepat.
- Memberikan gambaran visual kondisi keuangan melalui grafik interaktif.
- Menghitung saldo otomatis berdasarkan seluruh riwayat transaksi.
- Menyediakan kategorisasi transaksi agar laporan lebih terstruktur.

---

## 3. Ruang Lingkup (Scope)

### 3.1 Dalam Scope
- Manajemen transaksi (CRUD): tambah, lihat, edit, hapus.
- Kategorisasi transaksi (makan, transport, gaji, dll).
- Dashboard dengan total saldo, total pemasukan, total pengeluaran.
- Grafik pemasukan vs pengeluaran menggunakan Chart.js.
- Laporan transaksi dengan filter berdasarkan periode dan kategori.
- Manajemen kategori (CRUD kategori).

### 3.2 Luar Scope (v1.0)
- Autentikasi multi-user / login sistem.
- Export laporan ke PDF/Excel.
- Notifikasi / reminder.
- Integrasi dengan rekening bank.

---

## 4. Target Pengguna

| Segmen | Deskripsi |
|--------|-----------|
| Pengguna Primer | Individu yang ingin mencatat keuangan pribadi secara mandiri. |
| Pengguna Sekunder | Mahasiswa atau pelajar yang belajar CodeIgniter dengan studi kasus nyata. |

---

## 5. Kebutuhan Fungsional

### 5.1 Modul Transaksi

#### F-01: Tambah Transaksi
- Pengguna dapat menambahkan transaksi baru melalui form.
- Field: Judul transaksi, Jenis (Pemasukan / Pengeluaran), Jumlah (nominal), Kategori, Tanggal, Catatan (opsional).
- Validasi wajib: Judul, Jenis, Jumlah, Tanggal tidak boleh kosong.
- Jumlah harus berupa angka positif.

#### F-02: Lihat Daftar Transaksi
- Menampilkan daftar seluruh transaksi dalam bentuk tabel.
- Kolom: No, Tanggal, Judul, Kategori, Jenis, Jumlah, Aksi (Edit, Hapus).
- Mendukung filter berdasarkan: Jenis transaksi, Kategori, Rentang tanggal.
- Data diurutkan berdasarkan tanggal terbaru.
- Pagination untuk daftar yang panjang (10 data per halaman).

#### F-03: Edit Transaksi
- Pengguna dapat mengubah data transaksi yang sudah ada.
- Form pre-filled dengan data transaksi yang dipilih.
- Validasi sama dengan saat tambah transaksi.

#### F-04: Hapus Transaksi
- Pengguna dapat menghapus transaksi.
- Muncul konfirmasi dialog sebelum data dihapus (SweetAlert / confirm JS).
- Data yang terhapus tidak dapat dikembalikan (hard delete).

### 5.2 Modul Kategori

#### F-05: Manajemen Kategori
- Pengguna dapat menambah, mengedit, dan menghapus kategori.
- Kategori default tersedia saat instalasi: Makan & Minum, Transport, Belanja, Kesehatan, Hiburan, Gaji, Freelance, Lainnya.
- Kategori tidak dapat dihapus jika masih digunakan oleh transaksi.

### 5.3 Modul Dashboard

#### F-06: Ringkasan Keuangan
- Menampilkan kartu ringkasan:
  - Total Saldo (Pemasukan - Pengeluaran)
  - Total Pemasukan (bulan berjalan)
  - Total Pengeluaran (bulan berjalan)
- Warna saldo: hijau jika positif, merah jika negatif.

#### F-07: Grafik Keuangan (Chart.js)
- **Grafik 1 — Bar Chart:** Perbandingan pemasukan vs pengeluaran per bulan (12 bulan terakhir).
- **Grafik 2 — Doughnut/Pie Chart:** Distribusi pengeluaran berdasarkan kategori (bulan berjalan).
- Grafik bersifat interaktif (hover menampilkan detail nilai).

#### F-08: Transaksi Terbaru
- Menampilkan 5 transaksi terakhir pada dashboard sebagai quick view.

### 5.4 Modul Laporan

#### F-09: Laporan Transaksi
- Halaman khusus laporan dengan filter:
  - Filter bulan & tahun.
  - Filter jenis (semua / pemasukan / pengeluaran).
  - Filter kategori.
- Menampilkan tabel lengkap hasil filter.
- Menampilkan ringkasan: Total Pemasukan, Total Pengeluaran, Selisih (Saldo Periode).

---

## 6. Kebutuhan Non-Fungsional

| ID | Kebutuhan | Detail |
|----|-----------|--------|
| NF-01 | Performa | Halaman dashboard memuat dalam < 3 detik pada koneksi lokal. |
| NF-02 | Kompatibilitas | Berjalan di browser modern: Chrome, Firefox, Edge (versi terbaru). |
| NF-03 | Responsif | Tampilan menyesuaikan layar desktop dan tablet (Bootstrap 5). |
| NF-04 | Keamanan dasar | Input di-sanitasi untuk mencegah XSS dan SQL Injection (menggunakan Query Builder CI4). |
| NF-05 | Maintainability | Kode mengikuti struktur MVC CodeIgniter 4 secara konsisten. |

---

## 7. Arsitektur Teknis

### 7.1 Stack Teknologi

| Layer | Teknologi |
|-------|-----------|
| Backend Framework | CodeIgniter 4 (PHP 8.1+) |
| Database | MySQL 8.0+ |
| Frontend UI | Bootstrap 5 |
| Grafik | Chart.js 4.x |
| Alert Dialog | SweetAlert2 |
| Icons | Bootstrap Icons / Font Awesome |

### 7.2 Struktur Database

#### Tabel `categories`
```sql
CREATE TABLE categories (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    icon        VARCHAR(50),
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

#### Tabel `transactions`
```sql
CREATE TABLE transactions (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    title           VARCHAR(150) NOT NULL,
    type            ENUM('income', 'expense') NOT NULL,
    amount          DECIMAL(15, 2) NOT NULL,
    category_id     INT NOT NULL,
    transaction_date DATE NOT NULL,
    note            TEXT,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);
```

### 7.3 Struktur Direktori CI4
```
app/
├── Controllers/
│   ├── Dashboard.php
│   ├── Transactions.php
│   ├── Categories.php
│   └── Reports.php
├── Models/
│   ├── TransactionModel.php
│   └── CategoryModel.php
└── Views/
    ├── layouts/
    │   └── main.php
    ├── dashboard/
    │   └── index.php
    ├── transactions/
    │   ├── index.php
    │   ├── create.php
    │   └── edit.php
    ├── categories/
    │   ├── index.php
    │   └── form.php
    └── reports/
        └── index.php
```

### 7.4 Routing

```php
// app/Config/Routes.php
$routes->get('/',                          'Dashboard::index');
$routes->resource('transactions');         // CRUD otomatis
$routes->resource('categories');           // CRUD otomatis
$routes->get('reports',                   'Reports::index');
```

---

## 8. Alur Pengguna (User Flow)

```
[Buka Aplikasi]
       │
       ▼
[Dashboard] ──── Lihat saldo, grafik, transaksi terbaru
       │
       ├──► [Transaksi] ──► Tambah ──► Form Input ──► Simpan ──► Kembali ke Daftar
       │                ├──► Edit  ──► Form Edit  ──► Update
       │                └──► Hapus ──► Konfirmasi  ──► Hapus
       │
       ├──► [Kategori] ──► Tambah / Edit / Hapus Kategori
       │
       └──► [Laporan]  ──► Filter Periode & Kategori ──► Lihat Tabel & Ringkasan
```

---

## 9. UI / UX Guidelines

- **Tema warna:** Hijau (#198754) untuk pemasukan, Merah (#dc3545) untuk pengeluaran, Biru (#0d6efd) untuk aksi utama.
- **Layout:** Sidebar navigasi di kiri, konten utama di kanan.
- **Tipografi:** Font default Bootstrap (Inter / system-ui).
- **Feedback:** Setiap aksi CRUD menampilkan flash message sukses / error.
- **Konfirmasi hapus:** Menggunakan SweetAlert2 agar tidak accidental delete.
- **Form:** Menggunakan floating labels Bootstrap 5 untuk tampilan modern.

---

## 10. Milestones & Estimasi Pengerjaan

| No | Milestone | Estimasi |
|----|-----------|----------|
| 1 | Setup project CI4 + konfigurasi database | 0.5 hari |
| 2 | CRUD Kategori | 0.5 hari |
| 3 | CRUD Transaksi (model, controller, view) | 1.5 hari |
| 4 | Dashboard + Total Saldo Otomatis | 0.5 hari |
| 5 | Integrasi Chart.js (bar + doughnut) | 1 hari |
| 6 | Modul Laporan + Filter | 0.5 hari |
| 7 | Styling, polish UI, testing | 0.5 hari |
| **Total** | | **~5 hari kerja** |

---

## 11. Kriteria Penerimaan (Acceptance Criteria)

- [ ] Pengguna dapat menambah transaksi pemasukan dan pengeluaran.
- [ ] Pengguna dapat mengedit dan menghapus transaksi dengan konfirmasi.
- [ ] Total saldo di dashboard terupdate otomatis setelah setiap perubahan transaksi.
- [ ] Grafik bar chart menampilkan data 12 bulan terakhir dengan benar.
- [ ] Grafik pie/doughnut menampilkan distribusi pengeluaran per kategori bulan berjalan.
- [ ] Filter pada halaman laporan menghasilkan data yang akurat.
- [ ] Semua form memiliki validasi sisi server.
- [ ] Aplikasi berjalan tanpa error di lingkungan lokal (XAMPP / Laragon).

---

## 12. Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
|--------|--------|----------|
| Versi PHP tidak kompatibel | Aplikasi tidak bisa dijalankan | Dokumentasikan requirement PHP 8.1+ |
| Data transaksi besar menyebabkan lambat | Performa menurun | Implementasi pagination sejak awal |
| Kategori yang digunakan dihapus | Relasi rusak | Tambahkan pengecekan sebelum hapus kategori |

---

*Dokumen ini merupakan acuan pengembangan versi 1.0. Perubahan fitur di luar scope memerlukan revisi PRD.*
