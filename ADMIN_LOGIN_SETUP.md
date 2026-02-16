# Panduan Setup Admin Login

## File yang Telah Dibuat

### 1. Migration
- `database/migrations/2026_02_14_000000_create_admins_table.php` - Tabel untuk menyimpan data admin

### 2. Model
- `app/Models/Admin.php` - Model Admin dengan authentication

### 3. Controller
- `app/Http/Controllers/AdminController.php` - Controller untuk handle login, logout, dan dashboard admin

### 4. Views
- `resources/views/admin/login.blade.php` - Form login admin dengan styling modern
- `resources/views/admin/dashboard.blade.php` - Dashboard admin setelah login

### 5. Routes
- Routes admin telah ditambahkan di `routes/web.php`

### 6. Config
- `config/auth.php` - Ditambahkan guard dan provider untuk admin

### 7. Seeder
- `database/seeders/AdminSeeder.php` - Seeder untuk membuat admin default

## Cara Setup

1. **Jalankan Migration**
   ```bash
   php artisan migrate
   ```

2. **Jalankan Seeder untuk membuat admin default**
   ```bash
   php artisan db:seed --class=AdminSeeder
   ```

## Kredensial Login Default

- **Username:** admin
- **Password:** admin123

## URL Akses

- **Login Admin:** http://localhost/LSP_SMKN1Ciamis/public/admin/login
- **Dashboard Admin:** http://localhost/LSP_SMKN1Ciamis/public/admin/dashboard (setelah login)

## Fitur yang Tersedia

✅ Form login dengan username dan password
✅ Validasi login
✅ Remember me functionality
✅ Dashboard admin
✅ Logout functionality
✅ Session management
✅ Authentication guard terpisah untuk admin
✅ Design modern dan responsive

## Struktur Tabel Admins

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | string | Nama admin |
| email | string | Email admin (unique) |
| username | string | Username untuk login (unique) |
| password | string | Password (hashed) |
| remember_token | string | Token untuk remember me |
| created_at | timestamp | Waktu pembuatan |
| updated_at | timestamp | Waktu update |

## Routes yang Tersedia

- `GET /admin/login` - Tampilkan form login
- `POST /admin/login` - Submit login
- `GET /admin/dashboard` - Dashboard admin (requires auth)
- `POST /admin/logout` - Logout admin (requires auth)

## Catatan Keamanan

⚠️ **PENTING:** Setelah setup, segera ganti password default untuk keamanan!

Anda bisa menambahkan admin baru melalui database atau membuat halaman registrasi admin.
