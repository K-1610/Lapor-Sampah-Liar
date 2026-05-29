# Sistem Pelaporan Sampah Liar

Project ini dibuat dengan struktur modular agar mudah dikembangkan dan dirawat.

## Teknologi
- PHP Native
- MySQL / MariaDB
- Bootstrap 5.0
- DataTables
- Chart.js
- SweetAlert2
- Google Maps API
- AJAX

## Install di XAMPP
1. Copy folder project ke `htdocs`
2. Rename folder sesuai kebutuhan, contoh: `sistem-pelaporan-sampah-pro`
3. Buat database `sistem_pelaporan_sampah`
4. Import file `database/sistem_pelaporan_sampah.sql`
5. Edit `config/database.php` jika username/password MySQL berbeda
6. Isi API key Google Maps di `config/app.php`
7. Buka browser:
   `http://localhost/sistem-pelaporan-sampah-pro`

## Login Admin
Email: `admin@mail.com`
Password: `admin123`

## Struktur Folder
- `assets/` untuk CSS, JS, dan aset visual
- `config/` untuk konfigurasi
- `helpers/` untuk fungsi reusable
- `templates/` untuk komponen layout
- `pages/` untuk halaman user
- `admin/` untuk halaman admin
- `ajax/` untuk proses tanpa reload
- `api/` untuk endpoint JSON
- `uploads/` untuk file gambar laporan dan progress
- `database/` untuk file SQL

## Fitur
- Landing page modern
- Form pelaporan dengan captcha
- Upload foto validasi 5MB
- GPS otomatis dan peta Google Maps
- Tracking laporan
- Dashboard admin
- DataTables
- Chart.js
- Progress laporan
- Keamanan dasar: CSRF, prepared statement, sanitasi input

## Catatan
Untuk fitur peta, pastikan Google Maps API key diisi pada:
`config/app.php`
