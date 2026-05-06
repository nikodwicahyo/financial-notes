# Konfigurasi Environment (.env)

Dokumen ini menjelaskan konfigurasi environment yang diperlukan untuk menjalankan Aplikasi Catatan Keuangan Sederhana.

---

## 📝 Cara Setup

1. Salin file `env` menjadi `.env`:
   ```bash
   cp env .env
   ```
   
   Atau di Windows:
   ```bash
   copy env .env
   ```

2. Edit file `.env` sesuai dengan konfigurasi sistem Anda.

---

## ⚙️ Konfigurasi Wajib

### Environment Mode

```env
CI_ENVIRONMENT = development
```

**Nilai yang tersedia:**
- `development` - Mode pengembangan (menampilkan error detail)
- `production` - Mode produksi (menyembunyikan error detail)
- `testing` - Mode testing

**Rekomendasi:**
- Gunakan `development` saat pengembangan lokal
- Gunakan `production` saat deploy ke server live

---

### Database Configuration

```env
database.default.hostname = localhost
database.default.database = keuangan_db
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.port     = 3306
```

**Penjelasan:**
- `hostname` - Host database server (biasanya `localhost` untuk lokal)
- `database` - Nama database yang akan digunakan
- `username` - Username MySQL (default: `root`)
- `password` - Password MySQL (kosongkan jika tidak ada password)
- `DBDriver` - Driver database (`MySQLi` untuk MySQL)
- `port` - Port MySQL (default: `3306`)

**Contoh untuk XAMPP/Laragon:**
```env
database.default.hostname = localhost
database.default.database = keuangan_db
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.port     = 3306
```

**Contoh untuk server remote:**
```env
database.default.hostname = 192.168.1.100
database.default.database = keuangan_db
database.default.username = db_user
database.default.password = SecurePassword123
database.default.DBDriver = MySQLi
database.default.port     = 3306
```

---

## 🔐 Konfigurasi Keamanan (Opsional)

### Base URL

```env
app.baseURL = 'http://localhost:8080/'
```

Sesuaikan dengan URL aplikasi Anda:
- Lokal dengan `php spark serve`: `http://localhost:8080/`
- XAMPP/Laragon: `http://localhost/keuangan-app/public/`
- Server produksi: `https://yourdomain.com/`

### Session Configuration

```env
app.sessionDriver = 'CodeIgniter\Session\Handlers\FileHandler'
app.sessionCookieName = 'ci_session'
app.sessionExpiration = 7200
app.sessionSavePath = null
app.sessionMatchIP = false
app.sessionTimeToUpdate = 300
app.sessionRegenerateDestroy = false
```

**Catatan:** Konfigurasi default sudah cukup untuk penggunaan lokal.

### CSRF Protection

```env
security.csrfProtection = 'session'
security.tokenRandomize = false
security.tokenName = 'csrf_token_name'
security.headerName = 'X-CSRF-TOKEN'
security.cookieName = 'csrf_cookie_name'
security.expires = 7200
security.regenerate = true
```

**Catatan:** CSRF protection sudah aktif secara default untuk keamanan.

---

## 🚀 Konfigurasi Tambahan (Opsional)

### Timezone

```env
app.timezone = 'Asia/Jakarta'
```

Sesuaikan dengan timezone Anda:
- `Asia/Jakarta` - WIB (Jakarta, Sumatra)
- `Asia/Makassar` - WITA (Kalimantan, Sulawesi)
- `Asia/Jayapura` - WIT (Papua, Maluku)

### Locale

```env
app.defaultLocale = 'id'
app.negotiateLocale = false
app.supportedLocales = ['id']
```

---

## ⚠️ Keamanan

**PENTING:**
- File `.env` **TIDAK BOLEH** di-commit ke repository Git
- File `.env` sudah masuk dalam `.gitignore` secara default
- Jangan pernah share file `.env` yang berisi credential asli
- Gunakan password yang kuat untuk database di server produksi

---

## 📋 Checklist Setup

- [ ] File `.env` sudah dibuat dari `env`
- [ ] `CI_ENVIRONMENT` sudah diset (`development` atau `production`)
- [ ] Database hostname, username, password sudah benar
- [ ] Database `keuangan_db` sudah dibuat di MySQL
- [ ] Migration sudah dijalankan (`php spark migrate`)
- [ ] Seeder sudah dijalankan (`php spark db:seed CategorySeeder`)
- [ ] Aplikasi bisa diakses tanpa error

---

## 🔍 Troubleshooting

### Error: "Unable to connect to the database"
**Penyebab:** Konfigurasi database salah  
**Solusi:** 
1. Pastikan MySQL service berjalan
2. Cek username dan password di `.env`
3. Pastikan database `keuangan_db` sudah dibuat

### Error: "The action you requested is not allowed"
**Penyebab:** CSRF token tidak valid  
**Solusi:** 
1. Clear browser cache dan cookies
2. Pastikan `security.csrfProtection = 'session'` di `.env`

### Error: "Class 'IntlDateFormatter' not found"
**Penyebab:** Ekstensi PHP `intl` tidak aktif  
**Solusi:** 
1. Buka `php.ini`
2. Uncomment baris: `extension=intl`
3. Restart web server

---

## 📚 Referensi

- [CodeIgniter 4 Environment Configuration](https://codeigniter.com/user_guide/general/configuration.html)
- [CodeIgniter 4 Database Configuration](https://codeigniter.com/user_guide/database/configuration.html)

---

**Catatan:** Untuk konfigurasi lebih lanjut, lihat file `app/Config/*.php`
