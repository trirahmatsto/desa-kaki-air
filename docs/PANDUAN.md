# Panduan - Portal Desa Kaki Air

## A. Setup lokal (dari awal)

Prasyarat: PHP >= 8.2, Composer, Node.js (untuk Vite), MySQL atau SQLite.

```bash
git clone <url-repo> && cd <project>
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Koneksi DB:

- **SQLite** (default kaki-air): pastikan di `.env`:
  ```
  DB_CONNECTION=sqlite
  DB_DATABASE=C:/xampp/htdocs/<project>/database/database.sqlite
  ```
  buat file kosong `database/database.sqlite` bila belum ada.
- **MySQL**: buat database (mis. `bupolo_berseri`), lalu di `.env`:
  ```
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=<nama_db>
  DB_USERNAME=<user>
  DB_PASSWORD=<password>
  ```

Lanjut:

```bash
php artisan migrate --seed
php artisan data-desa:fetch        # isi data desa dari Supabase
php artisan serve                  # http://127.0.0.1:8000
# terminal lain (development): npm run dev
```

Login admin: `http://127.0.0.1:8000/admin` - akun default lihat README.

## B. Produksi / deploy (shared hosting cPanel/Hostinger-style)

Pola pemakai saat ini: shared hosting dengan document root tidak bisa diubah, memakai `.htaccess` di root yang me-redirect ke `public/`.

1. **Upload kode** ke `public_html/` (kecuali `node_modules`, `.env`, `.git`). Sertakan `vendor/` production (build di mesin lokal) - server tanpa akses composer.
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
2. **`.env`**: salin `.env.production.example` menjadi `.env`, isi
   - `APP_KEY` dari `php artisan key:generate --show`
   - `APP_URL` https domain
   - `DB_*` MySQL hosting (user/password database)
   - `MAIL_*` SMTP hosting (opsional, untuk kirim email)
   - `SUPABASE_URL` / `SUPABASE_ANON_KEY` (sudah terisi di template)
3. **Aktifkan SSL** + PHP 8.2/8.3 + naikkan `upload_max_filesize`/`post_max_size` (mis. 20M) di panel hosting.
4. **Import database**: buat DB MySQL, import dump SQL via phpMyAdmin, atau
   ```bash
   mysql -u <user> -p <db> < dump.sql
   ```
5. **Cache & storage link** (sekali jalan):
   ```bash
   php artisan storage:link
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   chmod -R 775 storage bootstrap/cache
   ```
6. **Cron** (untuk jadwal `data-desa:fetch` tiap 02:30). Di cPanel: Cron Jobs; tanpa SSH lewat cPanel cron:
   `* * * * * cd /home/<user>/domains/<domain>/public_html && php artisan schedule:run >> /dev/null 2>&1`

## C. Verifikasi setelah deploy

- Beranda tampil, semua aset https (tidak ada *mixed content*).
- `/data-desa` -> 9 kategori, tabel sesuai data; `/admin` -> login admin.
- Jalankan sekali `php artisan data-desa:fetch` -> output `9 kategori`.
- Uji `/profil-desa-dan-infografis` (cek `storage:link`).

## D. Troubleshooting

| Gejala | Solusi |
|---|---|
| HTTP 500 / blank | `tail storage/logs/laravel.log`; set sementara `APP_DEBUG=true` |
| Gambar tidak muncul | pastikan `php artisan storage:link` dan isi `storage/app/public` ter-upload |
| `APP_KEY` invalid | `php artisan key:generate` |
| Mixed content | `APP_URL` harus https + SSL aktif, lalu `php artisan config:cache` |
| Data desa kosong/stale | jalankan ulang `php artisan data-desa:fetch`; cek `SUPABASE_*` di `.env` |
| Login admin gagal | seeder ulang akun: `php artisan db:seed --class=AdminSeeder` |

## E. Catatan migrasi serah terima

- Ganti semua password default (README bagian akun default) setelah login pertama.
- Rotasi `APP_KEY`, `DB_PASSWORD`, `MAIL_PASSWORD` bila kredensial pernah bocor.
- `SUPABASE_ANON_KEY` adalah **anon (public, baca-saja)** - aman disimpan di repo template, bukan rahasia.