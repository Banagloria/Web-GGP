# Deployment checklist — Web Gereja (Laravel)

Ringkasan langkah operasional untuk menjalankan aplikasi di server produksi.

## Server

- PHP **8.3+** dengan ekstensi yang dipakai Laravel (mbstring, openssl, pdo, tokenizer, xml, ctype, json, fileinfo, curl).
- **PHP-FPM** dan web server (**nginx** atau Apache) dengan **document root** mengarah ke folder `public/`.
- Izin tulis untuk `storage/` dan `bootstrap/cache/` (pemilik proses web server, mis. `www-data`).

## Build & aset

- Jalankan `composer install --no-dev --optimize-autoloader` di server build atau di host produksi.
- Jalankan `npm ci` lalu `npm run build:css` (atau `npm run build` jika memakai Vite penuh) agar `public/css/app.css` terbaru.
- Tanpa build, pastikan fallback `public/css/app.css` sudah ter-deploy.

## Environment

- Salin `.env.example` ke `.env` dan set:
  - `APP_ENV=production`, `APP_DEBUG=false`
  - `APP_URL=https://domain-anda.tld` (HTTPS, tanpa slash akhir berlebihan)
  - `DB_*` ke MySQL/PostgreSQL produksi bila tidak memakai SQLite.
  - `SESSION_DRIVER` dan `QUEUE_CONNECTION` sesuai kebutuhan (database disarankan untuk sesi multi-server).
- `php artisan key:generate` sekali jika `APP_KEY` kosong.
- `php artisan migrate --force`
- `php artisan storage:link` untuk symlink `public/storage` ke `storage/app/public`.

## Keamanan & akun demo

- Jangan aktifkan pemulihan login demo di produksi; ikuti komentar `CHURCH_DEMO_*` di `.env.example`.
- Pastikan HTTPS di ujung (Let's Encrypt atau terminasi di load balancer); proxy sudah dipercaya di aplikasi (`trustProxies`).

## Cadangan

- Backup berkala basis data dan isi `storage/app/public/gallery` (foto galeri).
- Setelah deploy fitur galeri: wajib `php artisan migrate --force` (membuat tabel `gallery_items` dan menghapus tabel album lama).

## Kesehatan

- Endpoint Laravel: `GET /up`
- Ping ringan tanpa sesi: `GET /__ping` (teks `ok`)

## API ringan

- `GET /api/v1/ping` mengembalikan JSON `{ "ok": true, "service": "web-gereja" }` untuk pemantauan integrasi.
