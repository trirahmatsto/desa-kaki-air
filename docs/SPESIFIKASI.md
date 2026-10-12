# Spesifikasi - Portal Desa Kaki Air

## 1. Ringkasan

Situs layanan digital desa: halaman publik + panel admin. Data statistik desa bersumber dari Supabase dan disinkronkan berkala ke database lokal (MySQL/SQLite) melalui perintah artisan.

## 2. Halaman publik & fungsi

| Halaman | Fungsi inti |
|---|---|
| Beranda `/` | Hero slider, running text, statistik penduduk (total penduduk, KK, dusun, bansos), profil singkat + modal sejarah, struktur pemerintahan, statistik pendidikan & distribusi usia, fasilitas desa, APBDes & program prioritas, potensi & infrastruktur, fitur layanan, publikasi, galeri foto (tab foto/video + modal preview), berita & pengumuman |
| Profil Desa `/profil-desa` | Profil, visi-misi, perangkat desa, program prioritas  - data dari ProfilService (Supabase, cache 15 menit) |
| Data Desa `/data-desa` | 9 kategori data makro + rincian per tabel, bisa dibuka/tutup, unduh CSV |
| Artikel `/artikel` | Daftar berita & galeri |
| Pengaduan `/pengaduan` | Form aduan + upload lampiran |
| Layanan Surat `/layanan-surat` | Pilih jenis surat, isi form, upload berkas, nomor antre |
| Profil & Infografis `/profil-desa-dan-infografis` | Dokumen desa + infografis |

## 3. Alur data desa

```
Supabase (PostgREST)
  30 tabel potensi desa        -> config/data-desa.php ('tables')
  3 tabel profil: profil_desa, perangkat_desa, program_prioritas
  2 RPC sensus: get_sensus_keluarga, get_sensus_penduduk
        |
        v  php artisan data-desa:fetch (DataDesaImporter + ProfilService*)
Database:
  tabel desa (nama, slug)
  tabel data_desa (desa_id, kategori, data JSON)   <- 9 kategori
        |
        v
Halaman publik (Volt blade) membaca data_desa & mengagregasi
```

- `kategori` di `data_desa`: `sosial`, `kependudukan`, `pertanian`, `perdagangan`, `pariwisata`, `transportasi`, `sensus_perumahan`, `sensus_ekonomi`, `sensus_kependudukan`.
- `php artisan data-desa:fetch` dijalankan harian pukul **02:30** (lihat `routes/console.php`). Di server perlu cron `php artisan schedule:run`.
- *ProfilService* (kaki-air & bupolo) mengambil profil/perangkat/program dari Supabase dengan cache 15 menit; data identik juga tersimpan via fetch.

## 4. Role & hak akses

| Role | Akses |
|---|---|
| `admin` | Panel Filament penuh (semua resource) |
| `staf` | Panel Filament (resource operasional) |
| `kepala_desa` | Panel Filament (resource tertentu) |
| tamu | Halaman publik + pengajuan surat/pengaduan |

Implementasi role: `app/Models/User.php` kolom `role` + `@can`/Filament policy.

## 5. Skema database (7 migration)

| Migration | Isi |
|---|---|
| `create_users_table` | users (role: admin/staf/kepala_desa) |
| `create_cache_table` | cache Laravel |
| `create_jobs_table` | antrean job |
| `create_main_tables` | desa, data_desa, artikel_galeri, jenis_surats, pengaduan, pengajuan_surats |
| `create_dokumen_desas_table` | dokumen_desas |
| `create_infographics_table` | infographics |
| `create_data_desa_tables` | penyesuaian data_desa |

## 6. Non-fungsional

- PHP >= 8.2; ekstensi: pdo_mysql, mbstring, bcmath, gd, zip, intl, openssl, fileinfo
- CI GitHub Actions: `lint.yml`, `tests.yml` (Pest)
- Script internal: `scripts/check-blade.php` (lint PHP dalam blade), `scripts/check-data.php` (cek isi data_desa)