# Neon Database

Project ini disiapkan untuk memakai Neon PostgreSQL.

## Cara pakai

1. Buka dashboard Neon dan salin connection string PostgreSQL.
2. Di file `.env`, ubah konfigurasi database menjadi:

```env
DB_CONNECTION=pgsql
DATABASE_URL=postgresql://username:password@host/neondb?sslmode=require
DB_SSLMODE=require
```

3. Jalankan migration:

```bash
php artisan config:clear
php artisan migrate
```

Setelah migration berhasil, data dari halaman `/formulir` akan tersimpan ke tabel `website_project_requests` di Neon, dan halaman `/admin` akan membaca data dari database yang sama.

## Membuat akun admin

Isi credential admin di `.env`:

```env
ADMIN_NAME="Admin"
ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=password-yang-kuat
```

Lalu jalankan:

```bash
php artisan db:seed
```

Akun tersebut bisa dipakai untuk login di `/admin/login`.
