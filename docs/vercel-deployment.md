# Deploy ke Vercel

Project ini sudah disiapkan mengikuti pola deploy Laravel dari project `invite-me`.

## Environment variables di Vercel

Isi variable berikut di dashboard Vercel:

```env
APP_NAME="Form Custom"
APP_ENV=production
APP_KEY=base64:isi-dari-php-artisan-key-generate
APP_DEBUG=false
APP_URL=https://domain-vercel-atau-domain-custom

DB_CONNECTION=pgsql
DATABASE_URL=postgresql://username:password@host/neondb?sslmode=require&channel_binding=require
DB_SSLMODE=require

ADMIN_NAME="Admin"
ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=password-yang-kuat
```

`vercel.json` sudah memberi default serverless untuk:

```env
LOG_CHANNEL=stderr
CACHE_STORE=array
SESSION_DRIVER=cookie
QUEUE_CONNECTION=sync
```

## Setelah deploy

Jalankan migration dan seeder ke Neon dari lokal dengan env production/Neon yang sama:

```bash
php artisan migrate --force
php artisan db:seed --force
```

Lalu cek:

- `/` untuk halaman utama.
- `/formulir` untuk halaman formulir.
- `/admin/login` untuk login admin.
- `/_health` untuk cek bootstrap Laravel, APP_KEY, database, query, dan manifest Vite.

Jika `/_health` menampilkan `vite_manifest_exists: false`, jalankan:

```bash
npm run build
```

Pastikan folder `public/build` ikut ter-commit/deploy. Project ini sudah mengizinkan `public/build/manifest.json` dan `public/build/assets/*` lewat `.gitignore`, mengikuti pola project `invite-me`.

## Catatan

- Vercel menjalankan Laravel lewat `api/index.php`.
- Storage, cache, session, dan compiled views dipindah ke `/tmp/form-custom-storage`.
- Jangan commit `.env`; isi secret hanya di Vercel Environment Variables.
