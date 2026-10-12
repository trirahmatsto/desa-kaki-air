# Portal Desa Kaki Air

Website resmi desa berbasis **Laravel 12** dengan frontend **Livewire 3 + Volt**, panel admin **Filament 3.3**, dan styling **Tailwind CSS 4 + Vite 6**.

Status: pengembangan lokal (SQLite), belum ada domain produksi.

## Fitur

- **7 halaman publik**: Beranda, Profil Desa, Data Desa (9 kategori + sensus), Artikel & Berita, Pengaduan, Layanan Surat Online, Profil & Infografis.
- **Panel admin** Filament di `/admin` - kelola berita/galeri, dokumen desa, infografis, jenis surat, pengaduan, pengajuan surat.
- **Autentikasi** laravel dengan role: `admin`, `staf`, `kepala_desa`.
- **Data desa otomatis** diambil dari Supabase REST via `php artisan data-desa:fetch` (jadwal harian 02:30).
- Layanan surat online dengan upload berkas, pengaduan masyarakat, unduh data sebagai CSV.

## Stack / Teknologi

| Komponen | Teknologi |
|---|---|
| Framework | Laravel 12 (PHP >= 8.2) |
| Frontend UI | Livewire 3 + Volt (Blade) |
| Admin panel | Filament 3.3 (Flux UI 2) |
| CSS / build | Tailwind CSS 4 + Vite 6 |
| Database | SQLite (lokal) |
| Sumber data | Supabase (PostgREST) |

## Struktur folder utama

```
app/
  Console/Commands/FetchDataDesa.php   # php artisan data-desa:fetch
  Filament/Resources/                  # admin panel
  Models/                              # 9 model (lihat docs/PACKAGES.md)
  Providers/Filament/AdminPanelProvider.php  # panel /admin
  Services/DataDesaImporter.php        # ambil data dari Supabase
  Services/ProfilService.php           # profil/perangkat/program (kaki-air & bupolo)
config/data-desa.php                   # daftar 30 tabel potensi -> 9 kategori
database/seeders/                      # akun awal + data contoh
resources/views/
  components/layouts/public.blade.php  # layout halaman publik
  livewire/public/*.blade.php          # halaman publik (Volt)
  livewire/admin/                      # halaman admin infografis
routes/web.php                         # rute publik & settings
routes/console.php                     # jadwal data-desa:fetch
scripts/                               # script cek blade/data
tools/clone-desa.ps1                   # tool klon projek desa
docs/                                  # dokumentasi ini
```

## Setup lokal (development)

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
# atur koneksi DB di .env (sqlite: DB_CONNECTION=sqlite, DB_DATABASE=.../database/database.sqlite)
# atau MySQL: buat database lalu isi DB_DATABASE/DB_USERNAME/DB_PASSWORD
php artisan migrate --seed
php artisan data-desa:fetch        # ambil data desa dari Supabase
php artisan serve
# terminal lain:
npm run build   # sekali, atau
npm run dev     # selama development
```

## Akun default (dari seeder - WAJIB ganti password setelah login)

| Role | Email | Password |
|---|---|---|
| Admin | admin@kaki-air.desa.id | `password` |
| Staf | staf@kaki-air.desa.id | `password` |
| Kepala Desa | kades@kaki-air.desa.id | `password` |

Login panel: `{APP_URL}/admin`

## Rute publik

| Halaman | URL | File Volt |
|---|---|---|
| Beranda | `/` | `livewire/public/beranda.blade.php` |
| Profil Desa | `/profil-desa` | `livewire/public/profil-desa.blade.php` |
| Data Desa | `/data-desa` | `livewire/public/data-desa.blade.php` |
| Artikel & Berita | `/artikel` | `livewire/public/artikel.blade.php` |
| Pengaduan | `/pengaduan` | `livewire/public/pengaduan.blade.php` |
| Layanan Surat | `/layanan-surat` | `livewire/public/layanan-surat.blade.php` |
| Profil & Infografis | `/profil-desa-dan-infografis` | `livewire/public/profil-desa-dan-infografis.blade.php` |

## Dokumentasi lengkap

- [SPESIFIKASI.md](docs/SPESIFIKASI.md) - fitur, role, alur data, skema
- [PANDUAN.md](docs/PANDUAN.md) - setup lokal & deploy server
- [PACKAGES.md](docs/PACKAGES.md) - daftar dependensi/plugin dan file penting