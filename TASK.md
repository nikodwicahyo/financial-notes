# TASK.md — Development Checklist
## Aplikasi Catatan Keuangan Sederhana
**Framework:** CodeIgniter 4 + MySQL
**Versi:** 1.0.0
**Total Estimasi:** ~5 Hari Kerja

> **Panduan penggunaan:**
> - `[ ]` = Belum dikerjakan
> - `[x]` = Selesai
> - Setiap task diberi kode unik (TASK-XXX) sebagai referensi.
> - Kerjakan secara berurutan — setiap fase bergantung pada fase sebelumnya.

---

## FASE 0 — PERSIAPAN LINGKUNGAN
> Estimasi: **2–3 jam**

### 0.1 Tools & Environment
- [x] **TASK-001** — Install Laragon atau XAMPP (PHP 8.1+, MySQL 8.0+).
- [x] **TASK-002** — Pastikan ekstensi PHP aktif: `intl`, `mbstring`, `curl`, `pdo_mysql`, `xml`.
- [x] **TASK-003** — Install Composer (dependency manager PHP).
- [x] **TASK-004** — Install Git untuk version control.
- [x] **TASK-005** — Siapkan code editor (VS Code + ekstensi PHP Intelephense).

### 0.2 Inisialisasi Project CodeIgniter 4
- [x] **TASK-006** — Jalankan perintah instalasi CI4 via Composer:
  ```bash
  composer create-project codeigniter4/appstarter keuangan-app
  cd keuangan-app
  ```
- [x] **TASK-007** — Salin file `.env.example` menjadi `.env`:
  ```bash
  cp env .env
  ```
- [x] **TASK-008** — Set `CI_ENVIRONMENT = development` di file `.env`.
- [x] **TASK-009** — Verifikasi instalasi berhasil dengan membuka `http://localhost/keuangan-app/public` di browser — halaman welcome CI4 harus muncul.
- [x] **TASK-010** — Inisialisasi Git repository:
  ```bash
  git init
  git add .
  git commit -m "chore: initial CodeIgniter 4 setup"
  ```

---

## FASE 1 — KONFIGURASI DATABASE
> Estimasi: **1–2 jam**

### 1.1 Buat Database
- [x] **TASK-011** — Buka phpMyAdmin atau MySQL CLI, buat database baru:
  ```sql
  CREATE DATABASE keuangan_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  ```
- [x] **TASK-012** — Konfigurasi koneksi database di file `.env`:
  ```
  database.default.hostname = localhost
  database.default.database = keuangan_db
  database.default.username = root
  database.default.password =
  database.default.DBDriver = MySQLi
  database.default.port     = 3306
  ```

### 1.2 Buat Migration
- [x] **TASK-013** — Generate file migration untuk tabel `categories`:
  ```bash
  php spark make:migration CreateCategoriesTable
  ```
- [x] **TASK-014** — Isi migration `CreateCategoriesTable` dengan skema:
  ```php
  $this->forge->addField([
      'id'         => ['type' => 'INT', 'auto_increment' => true],
      'name'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
      'icon'       => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
      'created_at' => ['type' => 'DATETIME', 'null' => true],
  ]);
  $this->forge->addKey('id', true);
  $this->forge->createTable('categories');
  ```
- [x] **TASK-015** — Generate file migration untuk tabel `transactions`:
  ```bash
  php spark make:migration CreateTransactionsTable
  ```
- [x] **TASK-016** — Isi migration `CreateTransactionsTable` dengan skema:
  ```php
  $this->forge->addField([
      'id'               => ['type' => 'INT', 'auto_increment' => true],
      'title'            => ['type' => 'VARCHAR', 'constraint' => 150],
      'type'             => ['type' => 'ENUM', 'constraint' => ['income','expense']],
      'amount'           => ['type' => 'DECIMAL', 'constraint' => '15,2'],
      'category_id'      => ['type' => 'INT'],
      'transaction_date' => ['type' => 'DATE'],
      'note'             => ['type' => 'TEXT', 'null' => true],
      'created_at'       => ['type' => 'DATETIME', 'null' => true],
      'updated_at'       => ['type' => 'DATETIME', 'null' => true],
  ]);
  $this->forge->addKey('id', true);
  $this->forge->addForeignKey('category_id', 'categories', 'id', 'RESTRICT', 'CASCADE');
  $this->forge->createTable('transactions');
  ```
- [x] **TASK-017** — Jalankan semua migration:
  ```bash
  php spark migrate
  ```
- [x] **TASK-018** — Verifikasi tabel `categories` dan `transactions` terbuat di phpMyAdmin.

### 1.3 Buat Seeder (Data Awal)
- [x] **TASK-019** — Generate file seeder untuk kategori default:
  ```bash
  php spark make:seeder CategorySeeder
  ```
- [x] **TASK-020** — Isi `CategorySeeder` dengan 8 kategori default:
  ```php
  $data = [
      ['name' => 'Makan & Minum', 'icon' => 'bi-cup-hot'],
      ['name' => 'Transport',     'icon' => 'bi-car-front'],
      ['name' => 'Belanja',       'icon' => 'bi-bag'],
      ['name' => 'Kesehatan',     'icon' => 'bi-heart-pulse'],
      ['name' => 'Hiburan',       'icon' => 'bi-controller'],
      ['name' => 'Gaji',          'icon' => 'bi-cash-coin'],
      ['name' => 'Freelance',     'icon' => 'bi-laptop'],
      ['name' => 'Lainnya',       'icon' => 'bi-three-dots'],
  ];
  $this->db->table('categories')->insertBatch($data);
  ```
- [x] **TASK-021** — Daftarkan `CategorySeeder` di `DatabaseSeeder.php`.
- [x] **TASK-022** — Jalankan seeder:
  ```bash
  php spark db:seed CategorySeeder
  ```
- [x] **TASK-023** — Verifikasi 8 kategori berhasil masuk ke tabel `categories`.
- [x] **TASK-024** — Commit progress:
  ```bash
  git add . && git commit -m "feat: database migration & seeder setup"
  ```

---

## FASE 2 — LAYOUT & STRUKTUR TAMPILAN
> Estimasi: **2–3 jam**

### 2.1 Persiapan Aset Frontend
- [x] **TASK-025** — Tambahkan CDN di layout utama (via tag `<head>`):
  - Bootstrap 5.3 CSS
  - Bootstrap Icons
  - Chart.js 4.x
  - SweetAlert2
- [x] **TASK-026** — Tambahkan CDN JS di bagian bawah `<body>`:
  - Bootstrap 5.3 JS Bundle
  - SweetAlert2 JS

### 2.2 Buat Layout Utama (Master Template)
- [x] **TASK-027** — Buat file `app/Views/layouts/main.php` sebagai template dasar dengan struktur:
  - `<head>` berisi meta, title, CDN CSS.
  - `<body>` berisi sidebar + konten area + CDN JS.
  - Gunakan `$this->renderSection('content')` untuk slot konten.
- [x] **TASK-028** — Buat komponen **Sidebar** di dalam layout dengan navigasi:
  - 🏠 Dashboard (link ke `/`)
  - 💸 Transaksi (link ke `/transactions`)
  - 🏷️ Kategori (link ke `/categories`)
  - 📊 Laporan (link ke `/reports`)
- [x] **TASK-029** — Tambahkan **active state** pada menu sidebar berdasarkan URL saat ini menggunakan helper `current_url()`.
- [x] **TASK-030** — Buat komponen **flash message** di layout untuk menampilkan notifikasi sukses/error dari session.
- [x] **TASK-031** — Buat file `app/Views/layouts/header.php` untuk topbar (nama aplikasi, tanggal hari ini).
- [x] **TASK-032** — Test layout dengan membuka halaman `http://localhost/keuangan-app/public` — sidebar dan topbar harus tampil.

### 2.3 Konfigurasi Routing
- [x] **TASK-033** — Buka `app/Config/Routes.php`, hapus/nonaktifkan auto-routing.
- [x] **TASK-034** — Tambahkan routing manual:
  ```php
  $routes->get('/', 'Dashboard::index');
  $routes->get('transactions', 'Transactions::index');
  $routes->get('transactions/create', 'Transactions::create');
  $routes->post('transactions/store', 'Transactions::store');
  $routes->get('transactions/edit/(:num)', 'Transactions::edit/$1');
  $routes->post('transactions/update/(:num)', 'Transactions::update/$1');
  $routes->get('transactions/delete/(:num)', 'Transactions::delete/$1');
  $routes->get('categories', 'Categories::index');
  $routes->get('categories/create', 'Categories::create');
  $routes->post('categories/store', 'Categories::store');
  $routes->get('categories/edit/(:num)', 'Categories::edit/$1');
  $routes->post('categories/update/(:num)', 'Categories::update/$1');
  $routes->get('categories/delete/(:num)', 'Categories::delete/$1');
  $routes->get('reports', 'Reports::index');
  ```
- [x] **TASK-035** — Commit progress:
  ```bash
  git add . && git commit -m "feat: layout template & routing setup"
  ```

---

## FASE 3 — MODUL KATEGORI (CRUD)
> Estimasi: **3–4 jam**

### 3.1 Model Kategori
- [x] **TASK-036** — Generate model:
  ```bash
  php spark make:model CategoryModel
  ```
- [x] **TASK-037** — Konfigurasi `CategoryModel`:
  ```php
  protected $table      = 'categories';
  protected $primaryKey = 'id';
  protected $allowedFields = ['name', 'icon'];
  protected $useTimestamps = false;
  ```
- [x] **TASK-038** — Tambahkan method `isUsedByTransaction($id)` untuk mengecek apakah kategori sedang digunakan sebelum dihapus.

### 3.2 Controller Kategori
- [x] **TASK-039** — Generate controller:
  ```bash
  php spark make:controller Categories
  ```
- [x] **TASK-040** — Implementasi method `index()`: ambil semua kategori, kirim ke view.
- [x] **TASK-041** — Implementasi method `create()`: tampilkan form tambah kategori.
- [x] **TASK-042** — Implementasi method `store()`: validasi input, simpan ke DB, redirect dengan flash message.
  - Validasi: `name` required, max 100 karakter.
  - Validasi: `icon` opsional.
- [x] **TASK-043** — Implementasi method `edit($id)`: ambil data kategori by ID, tampilkan form edit pre-filled.
- [x] **TASK-044** — Implementasi method `update($id)`: validasi input, update data, redirect dengan flash message.
- [x] **TASK-045** — Implementasi method `delete($id)`: cek apakah kategori digunakan transaksi, jika ya tampilkan error, jika tidak hapus dan redirect.

### 3.3 View Kategori
- [x] **TASK-046** — Buat `app/Views/categories/index.php`:
  - Tabel daftar kategori (No, Icon, Nama, Aksi).
  - Tombol "+ Tambah Kategori".
  - Tombol Edit dan Hapus di setiap baris.
  - Konfirmasi SweetAlert2 sebelum hapus.
- [x] **TASK-047** — Buat `app/Views/categories/create.php`:
  - Form: field Nama Kategori, field Icon (text input, contoh: `bi-cup-hot`).
  - Tombol Simpan dan Batal.
- [x] **TASK-048** — Buat `app/Views/categories/edit.php`:
  - Sama seperti form create, dengan data pre-filled.
- [x] **TASK-049** — Test manual CRUD Kategori:
  - [x] Tambah kategori baru → berhasil muncul di daftar.
  - [x] Edit kategori → data berubah.
  - [x] Hapus kategori yang tidak dipakai → berhasil.
  - [x] Hapus kategori yang sedang dipakai transaksi → muncul pesan error (akan diuji setelah transaksi dibuat).
- [x] **TASK-050** — Commit progress:
  ```bash
  git add . && git commit -m "feat: CRUD kategori selesai"
  ```

---

## FASE 4 — MODUL TRANSAKSI (CRUD)
> Estimasi: **1 hari penuh**

### 4.1 Model Transaksi
- [x] **TASK-051** — Generate model:
  ```bash
  php spark make:model TransactionModel
  ```
- [x] **TASK-052** — Konfigurasi `TransactionModel`:
  ```php
  protected $table      = 'transactions';
  protected $primaryKey = 'id';
  protected $allowedFields = ['title','type','amount','category_id','transaction_date','note'];
  protected $useTimestamps = true;
  ```
- [x] **TASK-053** — Tambahkan method `getTotalIncome()`: SUM amount WHERE type = 'income'.
- [x] **TASK-054** — Tambahkan method `getTotalExpense()`: SUM amount WHERE type = 'expense'.
- [x] **TASK-055** — Tambahkan method `getBalance()`: selisih total income - total expense.
- [x] **TASK-056** — Tambahkan method `getMonthlyData($year)`: data income & expense per bulan untuk 1 tahun (dipakai grafik bar chart).
- [x] **TASK-057** — Tambahkan method `getExpenseByCategory($month, $year)`: data pengeluaran per kategori bulan ini (dipakai grafik doughnut).
- [x] **TASK-058** — Tambahkan method `getRecent($limit = 5)`: ambil N transaksi terbaru dengan JOIN ke tabel categories.
- [x] **TASK-059** — Tambahkan method `getFiltered($filters)`: ambil transaksi dengan filter jenis, kategori, rentang tanggal, dengan pagination.

### 4.2 Controller Transaksi
- [x] **TASK-060** — Generate controller:
  ```bash
  php spark make:controller Transactions
  ```
- [x] **TASK-061** — Implementasi method `index()`:
  - Ambil parameter filter dari query string (`$_GET`).
  - Panggil `getFiltered()` dari model.
  - Kirim data transaksi + daftar kategori ke view.
  - Implementasi pagination (10 per halaman).
- [x] **TASK-062** — Implementasi method `create()`: tampilkan form dengan dropdown kategori.
- [x] **TASK-063** — Implementasi method `store()` dengan validasi:
  - `title`: required, max 150 karakter.
  - `type`: required, harus `income` atau `expense`.
  - `amount`: required, angka, minimal 1.
  - `category_id`: required, harus ada di tabel categories.
  - `transaction_date`: required, format tanggal valid.
  - Jika valid: simpan, redirect ke index dengan flash sukses.
  - Jika tidak valid: kembalikan ke form dengan pesan error & input lama.
- [x] **TASK-064** — Implementasi method `edit($id)`: ambil transaksi by ID, tampilkan form pre-filled + dropdown kategori.
- [x] **TASK-065** — Implementasi method `update($id)`: validasi sama seperti store, update data, redirect.
- [x] **TASK-066** — Implementasi method `delete($id)`: hapus transaksi by ID, redirect dengan flash message.

### 4.3 View Transaksi
- [x] **TASK-067** — Buat `app/Views/transactions/index.php`:
  - **Panel Filter** di bagian atas:
    - Dropdown Jenis (Semua / Pemasukan / Pengeluaran).
    - Dropdown Kategori.
    - Input tanggal mulai & tanggal akhir.
    - Tombol Filter dan Reset.
  - **Tabel transaksi**: No, Tanggal, Judul, Kategori, Jenis (badge warna), Jumlah, Aksi (Edit, Hapus).
  - Badge hijau untuk pemasukan, merah untuk pengeluaran.
  - Konfirmasi SweetAlert2 sebelum hapus.
  - Pagination di bawah tabel.
  - Tombol "+ Tambah Transaksi" di atas tabel.
- [x] **TASK-068** — Buat `app/Views/transactions/create.php`:
  - Field: Judul, Jenis (radio button atau select), Jumlah (input number), Kategori (dropdown), Tanggal, Catatan (textarea).
  - Tampilkan error validasi di bawah setiap field jika ada.
  - Tombol Simpan dan Batal.
- [x] **TASK-069** — Buat `app/Views/transactions/edit.php`:
  - Sama seperti form create, dengan data transaksi pre-filled.
- [x] **TASK-070** — Format tampilan nominal (Rupiah): gunakan helper PHP `number_format()` dengan separator titik, awalan "Rp".
- [x] **TASK-071** — Test manual CRUD Transaksi:
  - [x] Tambah transaksi pemasukan → muncul di daftar dengan badge hijau.
  - [x] Tambah transaksi pengeluaran → muncul di daftar dengan badge merah.
  - [x] Edit transaksi → data berubah sesuai.
  - [x] Filter transaksi by jenis → hasil sesuai.
  - [x] Filter transaksi by kategori → hasil sesuai.
  - [x] Hapus transaksi → hilang dari daftar.
  - [x] Submit form kosong → muncul pesan validasi.
  - [x] Coba hapus kategori yang sudah dipakai transaksi → muncul pesan error.
- [x] **TASK-072** — Commit progress:
  ```bash
  git add . && git commit -m "feat: CRUD transaksi selesai"
  ```

---

## FASE 5 — MODUL DASHBOARD
> Estimasi: **3–4 jam**

### 5.1 Controller Dashboard
- [x] **TASK-073** — Generate controller:
  ```bash
  php spark make:controller Dashboard
  ```
- [x] **TASK-074** — Implementasi method `index()`:
  - Panggil `getTotalIncome()` dari `TransactionModel`.
  - Panggil `getTotalExpense()` dari `TransactionModel`.
  - Hitung saldo: income - expense.
  - Panggil `getRecent(5)` untuk transaksi terbaru.
  - Panggil `getMonthlyData(date('Y'))` untuk data bar chart.
  - Panggil `getExpenseByCategory(date('m'), date('Y'))` untuk data doughnut.
  - Kirim semua data ke view `dashboard/index.php`.

### 5.2 View Dashboard
- [x] **TASK-075** — Buat `app/Views/dashboard/index.php` dengan struktur berikut:

  **Section A — Kartu Ringkasan (3 kartu horizontal):**
  - [x] **TASK-076** — Kartu Total Saldo: tampilkan selisih income - expense. Warna teks hijau jika positif, merah jika negatif.
  - [x] **TASK-077** — Kartu Total Pemasukan (bulan berjalan): warna hijau, ikon panah naik.
  - [x] **TASK-078** — Kartu Total Pengeluaran (bulan berjalan): warna merah, ikon panah turun.

  **Section B — Grafik (2 grafik berdampingan):**
  - [x] **TASK-079** — **Bar Chart** (kiri, 60% lebar): Pemasukan vs Pengeluaran per bulan 12 bulan terakhir.
    - Dataset 1: Pemasukan (warna hijau `#198754`).
    - Dataset 2: Pengeluaran (warna merah `#dc3545`).
    - Label sumbu X: nama bulan (Jan, Feb, ... Des).
    - Data di-pass dari controller sebagai JSON menggunakan `json_encode()`.
  - [x] **TASK-080** — **Doughnut Chart** (kanan, 40% lebar): Distribusi pengeluaran berdasarkan kategori bulan berjalan.
    - Label: nama kategori.
    - Data: total pengeluaran per kategori.
    - Tampilkan "Belum ada data pengeluaran bulan ini" jika kosong.

  **Section C — Transaksi Terbaru:**
  - [x] **TASK-081** — Tabel mini 5 transaksi terbaru: Tanggal, Judul, Kategori, Jenis (badge), Jumlah.
  - [x] **TASK-082** — Tambahkan link "Lihat Semua →" menuju halaman transaksi.

- [x] **TASK-083** — Inisialisasi Chart.js di `<script>` bagian bawah view:
  ```javascript
  const barCtx = document.getElementById('barChart').getContext('2d');
  const barChart = new Chart(barCtx, { type: 'bar', data: {...}, options: {...} });

  const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
  const doughnutChart = new Chart(doughnutCtx, { type: 'doughnut', data: {...} });
  ```
- [x] **TASK-084** — Test dashboard:
  - [x] Kartu saldo update ketika transaksi baru ditambah.
  - [x] Bar chart menampilkan data sesuai transaksi yang ada.
  - [x] Doughnut chart tampil ketika ada transaksi pengeluaran bulan ini.
  - [x] Tabel transaksi terbaru menampilkan 5 data terakhir.
- [x] **TASK-085** — Commit progress:
  ```bash
  git add . && git commit -m "feat: dashboard dengan grafik Chart.js selesai"
  ```

---

## FASE 6 — MODUL LAPORAN
> Estimasi: **2–3 jam**

### 6.1 Controller Laporan
- [x] **TASK-086** — Generate controller:
  ```bash
  php spark make:controller Reports
  ```
- [x] **TASK-087** — Implementasi method `index()`:
  - Baca parameter filter dari query string: `bulan`, `tahun`, `jenis`, `kategori_id`.
  - Default: bulan & tahun saat ini jika tidak ada parameter.
  - Panggil `getFiltered()` dari `TransactionModel` dengan filter yang diterima.
  - Hitung subtotal: total pemasukan, total pengeluaran, dan selisih dari hasil filter.
  - Kirim data + daftar kategori ke view.

### 6.2 View Laporan
- [x] **TASK-088** — Buat `app/Views/reports/index.php`:

  **Panel Filter:**
  - [x] **TASK-089** — Dropdown bulan (Januari–Desember).
  - [x] **TASK-090** — Input tahun (number input, default tahun ini).
  - [x] **TASK-091** — Dropdown jenis (Semua / Pemasukan / Pengeluaran).
  - [x] **TASK-092** — Dropdown kategori (opsional).
  - [x] **TASK-093** — Tombol "Tampilkan" (submit GET form).

  **Kartu Ringkasan Periode (di atas tabel):**
  - [x] **TASK-094** — Total Pemasukan periode yang difilter.
  - [x] **TASK-095** — Total Pengeluaran periode yang difilter.
  - [x] **TASK-096** — Selisih (Saldo Periode), dengan warna dinamis.

  **Tabel Hasil:**
  - [x] **TASK-097** — Tabel lengkap dengan kolom: No, Tanggal, Judul, Kategori, Jenis, Jumlah, Catatan.
  - [x] **TASK-098** — Tampilkan pesan "Tidak ada transaksi pada periode ini" jika hasil kosong.
  - [x] **TASK-099** — Tampilkan judul laporan dinamis, contoh: "Laporan Keuangan — Mei 2026".

- [x] **TASK-100** — Test modul laporan:
  - [x] Filter bulan & tahun → data sesuai periode.
  - [x] Filter jenis pemasukan → hanya tampil pemasukan.
  - [x] Filter kategori → hanya tampil transaksi kategori tersebut.
  - [x] Kombinasi filter → hasil akurat.
  - [x] Periode tanpa transaksi → tampil pesan kosong + ringkasan Rp 0.
- [x] **TASK-101** — Commit progress:
  ```bash
  git add . && git commit -m "feat: modul laporan dengan filter selesai"
  ```

---

## FASE 7 — POLISH UI & UX
> Estimasi: **3–4 jam**

### 7.1 Konsistensi Visual
- [ ] **TASK-102** — Tambahkan custom CSS di `public/assets/css/style.css`:
  - Style sidebar (lebar 250px, background gelap, active state).
  - Style kartu ringkasan (shadow, border radius).
  - Style badge income/expense.
  - Transisi hover pada tombol dan baris tabel.
- [ ] **TASK-103** — Pastikan semua halaman menggunakan layout `main.php` secara konsisten.
- [ ] **TASK-104** — Pastikan sidebar menampilkan active state yang benar di setiap halaman.
- [ ] **TASK-105** — Pastikan flash message (sukses/error) tampil di semua halaman setelah aksi CRUD.

### 7.2 Konfirmasi Hapus dengan SweetAlert2
- [ ] **TASK-106** — Implementasi konfirmasi SweetAlert2 untuk hapus transaksi:
  ```javascript
  function confirmDelete(url) {
      Swal.fire({
          title: 'Yakin ingin menghapus?',
          text: 'Data tidak dapat dikembalikan!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#dc3545',
          cancelButtonText: 'Batal',
          confirmButtonText: 'Ya, Hapus!'
      }).then((result) => {
          if (result.isConfirmed) window.location.href = url;
      });
  }
  ```
- [ ] **TASK-107** — Terapkan fungsi `confirmDelete()` yang sama untuk hapus kategori.

### 7.3 Responsive & Accessibility
- [ ] **TASK-108** — Test tampilan di lebar layar 768px (tablet) — sidebar bisa di-toggle.
- [ ] **TASK-109** — Tambahkan toggle sidebar untuk layar kecil menggunakan Bootstrap offcanvas atau collapse.
- [ ] **TASK-110** — Pastikan semua form punya `label` yang terhubung dengan `id` input (aksesibilitas).
- [ ] **TASK-111** — Pastikan semua tabel punya `<th>` dengan atribut `scope="col"`.

### 7.4 Pengamanan Dasar
- [ ] **TASK-112** — Aktifkan CSRF protection di `app/Config/Security.php` (`$csrfProtection = 'session'`).
- [ ] **TASK-113** — Tambahkan `<?= csrf_field() ?>` di dalam semua tag `<form>` method POST.
- [ ] **TASK-114** — Pastikan semua output data ke view menggunakan `esc()` helper CI4 untuk mencegah XSS.
- [ ] **TASK-115** — Pastikan semua query database menggunakan Query Builder (bukan raw SQL dengan interpolasi string).

### 7.5 Halaman Error
- [ ] **TASK-116** — Buat custom view `app/Views/errors/html/error_404.php` untuk halaman 404 yang sesuai tema.
- [ ] **TASK-117** — Set `CI_ENVIRONMENT = production` di `.env` untuk menyembunyikan error detail di browser (boleh dikembalikan ke `development` saat testing).

- [ ] **TASK-118** — Commit progress:
  ```bash
  git add . && git commit -m "feat: polish UI, CSRF, SweetAlert2, responsive"
  ```

---

## FASE 8 — TESTING MENYELURUH
> Estimasi: **2–3 jam**

### 8.1 Functional Testing (Manual)

**Dashboard:**
- [ ] **TASK-119** — Kartu saldo menghitung dengan benar setelah tambah/hapus transaksi.
- [ ] **TASK-120** — Bar chart menampilkan data yang benar untuk 12 bulan.
- [ ] **TASK-121** — Doughnut chart menampilkan distribusi pengeluaran bulan ini.
- [ ] **TASK-122** — Tabel transaksi terbaru menampilkan 5 data terakhir.

**Transaksi:**
- [ ] **TASK-123** — Tambah transaksi pemasukan → saldo naik.
- [ ] **TASK-124** — Tambah transaksi pengeluaran → saldo turun.
- [ ] **TASK-125** — Edit transaksi (ubah jumlah) → saldo terupdate.
- [ ] **TASK-126** — Hapus transaksi → saldo terupdate, data hilang dari daftar.
- [ ] **TASK-127** — Submit form tambah transaksi kosong → semua validasi muncul.
- [ ] **TASK-128** — Input jumlah negatif → validasi menolak.
- [ ] **TASK-129** — Filter transaksi (kombinasi jenis + kategori + tanggal) → hasil akurat.

**Kategori:**
- [ ] **TASK-130** — Tambah kategori baru → muncul di dropdown transaksi.
- [ ] **TASK-131** — Edit kategori → nama berubah di semua referensi.
- [ ] **TASK-132** — Hapus kategori yang tidak digunakan → berhasil.
- [ ] **TASK-133** — Hapus kategori yang sedang digunakan transaksi → muncul pesan error, data tidak terhapus.

**Laporan:**
- [ ] **TASK-134** — Filter laporan bulan tertentu → data sesuai.
- [ ] **TASK-135** — Ringkasan periode terhitung benar.
- [ ] **TASK-136** — Filter bulan tanpa data → tabel kosong + ringkasan Rp 0.

**Keamanan:**
- [ ] **TASK-137** — Akses URL hapus langsung (tanpa konfirmasi) → tetap bisa tapi tidak bypass validasi.
- [ ] **TASK-138** — Coba submit form tanpa CSRF token → ditolak dengan error 403.
- [ ] **TASK-139** — Input `<script>alert('xss')</script>` pada field judul → ditampilkan sebagai teks biasa, tidak dieksekusi.

### 8.2 Cross-Browser Testing
- [ ] **TASK-140** — Test di Google Chrome (versi terbaru).
- [ ] **TASK-141** — Test di Mozilla Firefox (versi terbaru).
- [ ] **TASK-142** — Test di Microsoft Edge (versi terbaru).

- [ ] **TASK-143** — Commit progress:
  ```bash
  git add . && git commit -m "test: semua skenario testing manual selesai"
  ```

---

## FASE 9 — DOKUMENTASI & FINALISASI
> Estimasi: **1–2 jam**

### 9.1 Dokumentasi Project
- [ ] **TASK-144** — Buat file `README.md` di root project, berisi:
  - Deskripsi singkat aplikasi.
  - Screenshot tampilan (dashboard, transaksi, laporan).
  - Prasyarat (PHP 8.1+, MySQL 8.0+, Composer).
  - Langkah instalasi step-by-step.
  - Struktur direktori utama.
  - Daftar fitur.
- [ ] **TASK-145** — Dokumentasikan konfigurasi `.env` yang diperlukan (tanpa nilai sensitif).
- [ ] **TASK-146** — Buat file `database.sql` atau pastikan migration + seeder cukup untuk setup ulang DB dari nol.

### 9.2 Finalisasi Git
- [ ] **TASK-147** — Pastikan file `.env` masuk `.gitignore` (tidak ter-commit ke repository).
- [ ] **TASK-148** — Pastikan folder `vendor/` masuk `.gitignore`.
- [ ] **TASK-149** — Review semua file yang ter-commit, pastikan tidak ada credential atau data sensitif.
- [ ] **TASK-150** — Buat tag versi release:
  ```bash
  git tag -a v1.0.0 -m "Release v1.0.0 — Aplikasi Catatan Keuangan Sederhana"
  ```

### 9.3 Acceptance Criteria Final Check
- [ ] **TASK-151** — ✅ Pengguna dapat menambah transaksi pemasukan dan pengeluaran.
- [ ] **TASK-152** — ✅ Pengguna dapat mengedit dan menghapus transaksi dengan konfirmasi SweetAlert2.
- [ ] **TASK-153** — ✅ Total saldo di dashboard terupdate otomatis.
- [ ] **TASK-154** — ✅ Bar chart menampilkan data 12 bulan terakhir dengan benar.
- [ ] **TASK-155** — ✅ Doughnut chart menampilkan distribusi pengeluaran per kategori.
- [ ] **TASK-156** — ✅ Filter pada laporan menghasilkan data yang akurat.
- [ ] **TASK-157** — ✅ Semua form memiliki validasi sisi server.
- [ ] **TASK-158** — ✅ Aplikasi berjalan tanpa error di lingkungan lokal.

- [ ] **TASK-159** — Commit final:
  ```bash
  git add . && git commit -m "docs: README dan finalisasi project v1.0.0"
  ```

---

## RINGKASAN PROGRESS

| Fase | Deskripsi | Jumlah Task | Status |
|------|-----------|-------------|--------|
| 0 | Persiapan Lingkungan | 10 | ⬜ |
| 1 | Konfigurasi Database | 14 | ⬜ |
| 2 | Layout & Routing | 11 | ⬜ |
| 3 | CRUD Kategori | 15 | ⬜ |
| 4 | CRUD Transaksi | 22 | ⬜ |
| 5 | Dashboard & Grafik | 13 | ⬜ |
| 6 | Modul Laporan | 16 | ⬜ |
| 7 | Polish UI & Keamanan | 17 | ⬜ |
| 8 | Testing Menyeluruh | 21 | ⬜ |
| 9 | Dokumentasi & Finalisasi | 16 | ⬜ |
| **Total** | | **155 Task** | **0%** |

---

*Update status task ini secara berkala selama pengembangan. Setiap `[x]` yang dicentang adalah satu langkah lebih dekat ke finish line! 🚀*
