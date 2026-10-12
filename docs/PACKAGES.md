# Paket & Dependensi - Portal Desa Kaki Air

## Produksi (composer `require`)

| Paket | Versi | Fungsi |
|---|---|---|
| `laravel/framework` | ^12.0 | Framework |
| `filament/filament` | 3.3 | Panel admin `/admin` |
| `livewire/volt` | ^1.6.7 | Volt - komponen Livewire berbasis file blade |
| `livewire/flux` | ^2.0 | Komponen UI Flux |
| `laravel/tinker` | ^2.10.1 | REPL artisan |

## Development (composer `require-dev`)

| Paket | Fungsi |
|---|---|
| `pestphp/pest` + `pest-plugin-laravel` | Testing |
| `laravel/pint` | Formatter kode |
| `laravel/pail` | Log tail |
| `laravel/sail` | Docker dev |
| `fakerphp/faker` | Data palsu |
| `mockery/mockery` | Mock |
| `nunomaduro/collision` | Error handler CLI |

## Node.js (package.json)

`tailwindcss` ^4.0.7, `@tailwindcss/vite` ^4.0.7, `vite` ^6.0, `laravel-vite-plugin` ^1.0, `autoprefixer` ^10.4, `axios` ^1.7, `concurrently` ^9.0 (plus optionalDependencies linux x64 untuk build di server).

## Filament Resources (panel admin)

| Resource | Kelola |
|---|---|
| `ArtikelGaleriResource` | Berita & galeri |
| `DokumenDesaResource` | Dokumen publik desa |
| `InfographicResource` | Infografis |
| `JenisSuratResource` | Daftar jenis surat layanan online |
| `PengaduanResource` | Pengaduan masyarakat |
| `PengajuanSuratResource` | Pengajuan surat online |

## Model (app/Models)

`User` (role), `Desa`, `DataDesa` (data JSON per kategori), `ArtikelGaleri`, `DokumenDesa`, `Infographic`, `JenisSurat`, `Pengaduan`, `PengajuanSurat`.

## Services & Command

- `App\Services\DataDesaImporter` - fetch potensi + sensus dari Supabase.
- `App\Services\ProfilService` *(kaki-air & bupolo)* - profil/perangkat/program dengan cache 15 menit.
- `php artisan data-desa:fetch` (`App\Console\Commands\FetchDataDesa`) - jadwal `routes/console.php` pukul 02:30.

## Konfigurasi penting

| File | Isi |
|---|---|
| `config/data-desa.php` | URL & anon key Supabase + 30 tabel -> kategori |
| `.env.production.example` | Template `.env` produksi (DB, MAIL, SUPABASE) |
| `routes/web.php` | Semua rute publik + settings |

## Script & tool tambahan

- `scripts/check-blade.php` - lint PHP dalam blade (jalankan: `php scripts/check-blade.php`)
- `scripts/check-data.php` - ringkasan isi tabel `desa` & `data_desa` (`php scripts/check-data.php`)
- `tools/clone-desa.ps1` - klon projek desa ke desa baru (PowerShell)
- `.github/workflows/lint.yml`, `.github/workflows/tests.yml` - CI

## Lainnya

Pustaka pendukung bawaan Laravel: Redis/Memcached/queue/session, cache, mail, notification, dll.