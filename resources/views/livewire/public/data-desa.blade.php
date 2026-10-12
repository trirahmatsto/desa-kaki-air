<?php

use App\Models\DataDesa;
use App\Models\Desa;
use App\Services\ProfilService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

new #[Layout('components.layouts.public')] class extends Component
{
    public Desa $desa;

    public array $categories = [];

    public ?string $openCategory = null;

    public array $openTables = [];

    public function with(): array
    {
        return [
            'profil' => ProfilService::profil('Kaki Air'),
        ];
    }

    public function mount(): void
    {
        $this->desa = Desa::where('slug', 'kaki-air')->firstOrFail();
        $dataDesa = DataDesa::where('desa_id', $this->desa->id)->get()->keyBy('kategori');
        $this->categories = [
            'sosial' => [
                'label' => 'Sosial dan Kesejahteraan',
                'data' => $dataDesa->get('sosial')?->data ?? [],
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
            ],
            'kependudukan' => [
                'label' => 'Kependudukan',
                'data' => $dataDesa->get('kependudukan')?->data ?? [],
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>',
            ],
            'pertanian' => [
                'label' => 'Pertanian',
                'data' => $dataDesa->get('pertanian')?->data ?? [],
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 20A7 7 0 019.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>',
            ],
            'perdagangan' => [
                'label' => 'Perdagangan',
                'data' => $dataDesa->get('perdagangan')?->data ?? [],
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>',
            ],
            'pariwisata' => [
                'label' => 'Pariwisata',
                'data' => $dataDesa->get('pariwisata')?->data ?? [],
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>',
            ],
            'transportasi' => [
                'label' => 'Transportasi dan Komunikasi',
                'data' => $dataDesa->get('transportasi')?->data ?? [],
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>',
            ],
            'sensus_perumahan' => [
                'label' => 'Perumahan dan Permukiman',
                'data' => $dataDesa->get('sensus_perumahan')?->data ?? [],
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
            ],
            'sensus_ekonomi' => [
                'label' => 'Usaha dan Ekonomi',
                'data' => $dataDesa->get('sensus_ekonomi')?->data ?? [],
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
            ],
            'sensus_kependudukan' => [
                'label' => 'Kependudukan (Hasil Sensus Desa)',
                'data' => $dataDesa->get('sensus_kependudukan')?->data ?? [],
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
            ],
        ];
        $this->openCategory = 'sosial';
    }

    public function toggleCategory(string $key): void
    {
        $this->openCategory = $this->openCategory === $key ? null : $key;
    }

    public function toggleTable(string $category, string $tableName): void
    {
        $key = "{$category}.{$tableName}";

        if (in_array($key, $this->openTables, true)) {
            $this->openTables = array_values(array_diff($this->openTables, [$key]));
        } else {
            $this->openTables[] = $key;
        }
    }

    public function tableOpen(string $category, string $tableName): bool
    {
        return in_array("{$category}.{$tableName}", $this->openTables, true);
    }

    public function macroSummary(string $tableName, array $rows): array
    {
        $sumSpec = [
            // Sosial
            'sosial_sekolah' => ['columns' => ['negeri', 'swasta'], 'label' => 'Sekolah'],
            'sosial_guru' => ['columns' => ['laki_laki', 'perempuan'], 'label' => 'Guru'],
            'sosial_murid' => ['columns' => ['laki_laki', 'perempuan'], 'label' => 'Murid'],
            'sosial_ruang_kelas' => ['columns' => ['jumlah_ruang'], 'label' => 'Ruang Kelas'],
            'sosial_faskes' => ['columns' => ['puskesmas', 'pustu', 'polindes', 'posyandu', 'klinik'], 'label' => 'Fasilitas Kesehatan'],
            'sosial_posyandu' => ['count' => true, 'label' => 'Posyandu'],
            'sosial_gizi' => ['columns' => ['jumlah'], 'label' => 'Anak 0-59 Bulan'],
            'sosial_kesejahteraan' => ['columns' => ['pra_sejahtera', 'sejahtera_i', 'sejahtera_ii', 'sejahtera_iii', 'sejahtera_iii_plus'], 'label' => 'Keluarga'],
            'sosial_bansos' => ['columns' => ['jumlah_penerima'], 'label' => 'Penerima Bansos'],
            // Kependudukan
            'kpd_penduduk_dusun' => ['columns' => ['total'], 'label' => 'Penduduk'],
            'kpd_pendidikan' => ['columns' => ['jumlah'], 'label' => 'Penduduk'],
            'kpd_kk_dusun' => ['columns' => ['jumlah_kk'], 'label' => 'Kartu Keluarga'],
            'kpd_pekerjaan' => ['columns' => ['jumlah'], 'label' => 'Pekerja'],
            'kpd_tempat_ibadah' => ['columns' => ['masjid', 'musholla', 'gereja', 'pura', 'vihara'], 'label' => 'Tempat Ibadah'],
            'kpd_masjid' => ['count' => true, 'label' => 'Masjid'],
            'kpd_musholla' => ['count' => true, 'label' => 'Musholla'],
            'kpd_majelis_taklim' => ['columns' => ['jumlah_kelompok'], 'label' => 'Kelompok Majelis Taklim'],
            'kpd_fasilitas_olahraga' => ['columns' => ['jumlah'], 'label' => 'Sarana Olahraga'],
            // Pertanian
            'tani_lahan_irigasi' => ['columns' => ['luas_ha'], 'label' => 'Ha'],
            'tani_hutan' => ['columns' => ['luas_ha'], 'label' => 'Ha'],
            // Perdagangan
            'dagang_industri' => ['columns' => ['jumlah'], 'label' => 'Unit Industri'],
            'dagang_pasar' => ['columns' => ['jumlah'], 'label' => 'Pasar'],
            'dagang_bank_koperasi' => ['columns' => ['jumlah'], 'label' => 'Bank/Koperasi'],
            // Pariwisata
            'wisata_restoran' => ['columns' => ['restoran', 'kedai', 'warung_makan'], 'label' => 'Unit Usaha Makan'],
            'wisata_toko' => ['columns' => ['toko_kelontong', 'warung', 'minimarket'], 'label' => 'Toko/Warung'],
            'wisata_penginapan' => ['columns' => ['hotel', 'penginapan', 'homestay'], 'label' => 'Penginapan'],
            // Transportasi
            'trans_kendaraan' => ['columns' => ['jumlah'], 'label' => 'Kendaraan'],
            'trans_menara' => ['columns' => ['jumlah_menara'], 'label' => 'Menara'],
            'trans_bengkel' => ['columns' => ['bengkel_motor', 'bengkel_mobil', 'bengkel_las'], 'label' => 'Bengkel'],
            'trans_kantor_pos' => ['count' => true, 'label' => 'Kantor POS/Ekspedisi'],
            // Sensus — Perumahan
            'sens_jenis_bangunan' => ['columns' => ['jumlah'], 'label' => 'Keluarga'],
            'sens_kepemilikan_bangunan' => ['columns' => ['jumlah'], 'label' => 'Keluarga'],
            'sens_lantai' => ['columns' => ['jumlah'], 'label' => 'Keluarga'],
            'sens_dinding' => ['columns' => ['jumlah'], 'label' => 'Keluarga'],
            'sens_atap' => ['columns' => ['jumlah'], 'label' => 'Keluarga'],
            'sens_air_minum' => ['columns' => ['jumlah'], 'label' => 'Keluarga'],
            'sens_air_mandi' => ['columns' => ['jumlah'], 'label' => 'Keluarga'],
            'sens_penerangan' => ['columns' => ['jumlah'], 'label' => 'Keluarga'],
            'sens_bahan_bakar' => ['columns' => ['jumlah'], 'label' => 'Keluarga'],
            // Sensus — Ekonomi
            'sens_lapangan_usaha' => ['columns' => ['jumlah'], 'label' => 'Unit Usaha'],
            'sens_izin_usaha' => ['columns' => ['jumlah'], 'label' => 'Unit Usaha'],
            // Sensus — Kependudukan
            'sens_kelompok_umur' => ['columns' => ['laki_laki', 'perempuan'], 'label' => 'Jiwa'],
            'sens_status_perkawinan' => ['columns' => ['laki_laki', 'perempuan'], 'label' => 'Jiwa'],
            'sens_baca_tulis' => ['columns' => ['laki_laki', 'perempuan'], 'label' => 'Jiwa'],
            'sens_partisipasi_sekolah' => ['columns' => ['jumlah'], 'label' => 'Jiwa'],
            'sens_ijazah' => ['columns' => ['jumlah'], 'label' => 'Jiwa'],
            'sens_status_pekerjaan' => ['columns' => ['jumlah'], 'label' => 'Jiwa'],
        ];

        $spec = $sumSpec[$tableName] ?? ['columns' => null, 'label' => null];

        if ($spec['label'] === null) {
            $numericCols = collect($rows[0] ?? [])->keys()->filter(fn ($c) => !$this->skipColumn($c) && is_numeric($rows[0][$c]))->values();
            $value = 0;
            foreach ($rows as $row) {
                foreach ($numericCols as $col) {
                    $value += (float) ($row[$col] ?? 0);
                }
            }

            return ['label' => str_replace('_', ' ', ucfirst($tableName)), 'value' => $value];
        }

        if (! empty($spec['count'])) {
            $value = 0;
            foreach ($rows as $row) {
                foreach ($row as $col => $val) {
                    if ($this->skipColumn($col)) {
                        continue;
                    }
                    if ($val !== null && $val !== '' && $val !== false && $val !== 0 && $val !== '0') {
                        $value++;
                        break;
                    }
                }
            }

            return ['label' => $spec['label'], 'value' => $value];
        }

        $value = 0;
        foreach ($rows as $row) {
            foreach ($spec['columns'] as $col) {
                $value += (float) ($row[$col] ?? 0);
            }
        }

        return ['label' => $spec['label'], 'value' => $value];
    }

    public function tableTitle(string $tableName): string
    {
        $titles = [
            // Sosial
            'sosial_sekolah' => 'Jumlah Sekolah Menurut Tingkat Pendidikan',
            'sosial_guru' => 'Jumlah Guru Menurut Tingkat Pendidikan',
            'sosial_murid' => 'Jumlah Murid Menurut Tingkat Pendidikan',
            'sosial_ruang_kelas' => 'Jumlah Ruang Kelas Menurut Tingkat Pendidikan',
            'sosial_faskes' => 'Jumlah Fasilitas Kesehatan Menurut Dusun',
            'sosial_posyandu' => 'Nama Posyandu dan Jumlah Kader',
            'sosial_gizi' => 'Jumlah Anak 0-59 Bulan Menurut Status Gizi',
            'sosial_kesejahteraan' => 'Jumlah Keluarga Pra-Sejahtera dan Sejahtera',
            'sosial_bansos' => 'Jumlah Keluarga Penerima Manfaat dari Pemerintah',
            // Kependudukan
            'kpd_penduduk_dusun' => 'Jumlah Penduduk dan Rasio Jenis Kelamin Menurut Dusun',
            'kpd_pendidikan' => 'Jumlah Penduduk Menurut Tingkat Pendidikan',
            'kpd_kk_dusun' => 'Jumlah Kartu Keluarga Menurut Dusun',
            'kpd_pekerjaan' => 'Jumlah Penduduk Menurut Jenis Pekerjaan',
            'kpd_tempat_ibadah' => 'Jumlah Tempat Peribadatan Menurut Dusun',
            'kpd_masjid' => 'Nama Masjid Menurut Dusun',
            'kpd_musholla' => 'Nama Musholla Menurut Dusun',
            'kpd_majelis_taklim' => 'Jumlah Majelis Taklim Menurut Dusun',
            'kpd_fasilitas_olahraga' => 'Jumlah Sarana Olahraga di Desa',
            // Pertanian
            'tani_lahan_irigasi' => 'Luas Lahan Menurut Jenis Irigasi',
            'tani_hutan' => 'Luas Hutan Menurut Jenis Hutan',
            // Perdagangan
            'dagang_industri' => 'Jumlah Industri Pengolahan Menurut Dusun',
            'dagang_pasar' => 'Jumlah Pasar Menurut Dusun',
            'dagang_bank_koperasi' => 'Jumlah Bank dan Koperasi Menurut Dusun',
            // Pariwisata
            'wisata_restoran' => 'Jumlah Restoran, Kedai, Warung Makan Menurut Dusun',
            'wisata_toko' => 'Jumlah Toko dan Minimarket Menurut Dusun',
            'wisata_penginapan' => 'Jumlah Penginapan dan Hotel Menurut Dusun',
            // Transportasi
            'trans_kendaraan' => 'Jumlah Kendaraan Bermotor Menurut Jenis',
            'trans_menara' => 'Jumlah Menara Seluler Menurut Dusun',
            'trans_bengkel' => 'Jumlah Bengkel Menurut Dusun',
            'trans_kantor_pos' => 'Keberadaan Kantor POS dan Agen Ekspedisi',
            // Sensus — Perumahan
            'sens_jenis_bangunan' => 'Jenis Bangunan Tempat Tinggal',
            'sens_kepemilikan_bangunan' => 'Status Kepemilikan Bangunan',
            'sens_lantai' => 'Jenis Lantai Rumah',
            'sens_dinding' => 'Jenis Dinding Rumah',
            'sens_atap' => 'Jenis Atap Rumah',
            'sens_air_minum' => 'Sumber Air Minum',
            'sens_air_mandi' => 'Sumber Air untuk Mandi/Cuci',
            'sens_penerangan' => 'Sumber Penerangan Utama',
            'sens_bahan_bakar' => 'Bahan Bakar Memasak',
            // Sensus — Ekonomi
            'sens_lapangan_usaha' => 'Lapangan Usaha Menurut Sektor',
            'sens_izin_usaha' => 'Jenis Izin Usaha',
            // Sensus — Kependudukan
            'sens_kelompok_umur' => 'Penduduk Menurut Kelompok Umur dan Jenis Kelamin',
            'sens_status_perkawinan' => 'Penduduk Menurut Status Perkawinan',
            'sens_baca_tulis' => 'Penduduk Menurut Kemampuan Baca Tulis',
            'sens_partisipasi_sekolah' => 'Penduduk Menurut Partisipasi Sekolah',
            'sens_ijazah' => 'Penduduk Menurut Ijazah Tertinggi',
            'sens_status_pekerjaan' => 'Penduduk Menurut Status Pekerjaan',
        ];

        return $titles[$tableName] ?? str_replace('_', ' ', ucfirst($tableName));
    }

    public function columnLabel(string $column): string
    {
        $labels = [
            'tingkat_pendidikan' => 'Tingkat Pendidikan',
            'laki_laki' => 'Laki-laki',
            'perempuan' => 'Perempuan',
            'negeri' => 'Negeri',
            'swasta' => 'Swasta',
            'jumlah_ruang' => 'Jumlah Ruang',
            'kondisi_baik' => 'Kondisi Baik',
            'kondisi_rusak' => 'Kondisi Rusak',
            'nama_dusun' => 'Dusun',
            'nama_posyandu' => 'Nama Posyandu',
            'nama_masjid' => 'Nama Masjid',
            'nama_musholla' => 'Nama Musholla',
            'nama_pasar' => 'Nama Pasar',
            'status_gizi' => 'Status Gizi',
            'jumlah' => 'Jumlah',
            'jumlah_kk' => 'Jumlah KK',
            'jumlah_kader' => 'Jumlah Kader',
            'pra_sejahtera' => 'Pra-Sejahtera',
            'sejahtera_i' => 'Sejahtera I',
            'sejahtera_ii' => 'Sejahtera II',
            'sejahtera_iii' => 'Sejahtera III',
            'sejahtera_iii_plus' => 'Sejahtera III+',
            'nama_program' => 'Nama Program',
            'jumlah_penerima' => 'Jumlah Penerima',
            'keterangan' => 'Keterangan',
            'jenis_pekerjaan' => 'Jenis Pekerjaan',
            'jenis_irigasi' => 'Jenis Irigasi',
            'luas_ha' => 'Luas (Ha)',
            'jenis_hutan' => 'Jenis Hutan',
            'jenis_industri' => 'Jenis Industri',
            'jenis_pasar' => 'Jenis Pasar',
            'jenis' => 'Jenis',
            'nama' => 'Nama',
            'restoran' => 'Restoran',
            'kedai' => 'Kedai',
            'warung_makan' => 'Warung Makan',
            'toko_kelontong' => 'Toko Kelontong',
            'warung' => 'Warung',
            'minimarket' => 'Minimarket',
            'hotel' => 'Hotel',
            'penginapan' => 'Penginapan',
            'homestay' => 'Homestay',
            'jenis_kendaraan' => 'Jenis Kendaraan',
            'jumlah_menara' => 'Jumlah Menara',
            'kondisi_sinyal' => 'Kondisi Sinyal',
            'operator' => 'Operator',
            'bengkel_motor' => 'Bengkel Motor',
            'bengkel_mobil' => 'Bengkel Mobil',
            'bengkel_las' => 'Bengkel Las',
            'ada_kantor_pos' => 'Ada Kantor POS',
            'nama_kantor_pos' => 'Nama Kantor POS',
            'jumlah_agen_ekspedisi' => 'Jumlah Agen Ekspedisi',
            'nama_agen' => 'Nama Agen',
            'rasio_jk' => 'Rasio JK',
            'persentase' => 'Persentase',
            'total' => 'Total',
            'puskesmas' => 'Puskesmas',
            'pustu' => 'Pustu',
            'polindes' => 'Polindes',
            'posyandu' => 'Posyandu',
            'klinik' => 'Klinik',
            'masjid' => 'Masjid',
            'musholla' => 'Musholla',
            'gereja' => 'Gereja',
            'pura' => 'Pura',
            'vihara' => 'Vihara',
            'jumlah_kelompok' => 'Jumlah Kelompok',
            'jumlah_anggota' => 'Jumlah Anggota',
            'jenis_fasilitas' => 'Jenis Fasilitas',
            'kondisi' => 'Kondisi',
            'alamat' => 'Alamat',
            'no' => 'No',
            'jenis_bangunan' => 'Jenis Bangunan',
            'status_kepemilikan' => 'Status Kepemilikan',
            'jenis_lantai' => 'Jenis Lantai',
            'jenis_dinding' => 'Jenis Dinding',
            'jenis_atap' => 'Jenis Atap',
            'sumber_air_minum' => 'Sumber Air Minum',
            'sumber_air_mandi' => 'Sumber Air Mandi/Cuci',
            'sumber_penerangan' => 'Sumber Penerangan',
            'bahan_bakar' => 'Bahan Bakar',
            'lapangan_usaha' => 'Lapangan Usaha',
            'jenis_izin' => 'Jenis Izin',
            'kelompok_umur' => 'Kelompok Umur',
            'status_perkawinan' => 'Status Perkawinan',
            'kemampuan' => 'Kemampuan Baca Tulis',
            'partisipasi' => 'Partisipasi Sekolah',
            'ijazah' => 'Ijazah Tertinggi',
        ];

        return $labels[$column] ?? str_replace('_', ' ', ucfirst($column));
    }

    public function skipColumn(string $column): bool
    {
        return in_array($column, ['id', 'created_at', 'desa', 'updated_at']);
    }

    public function tableHasRows(array $rows): bool
    {
        foreach ($rows as $row) {
            foreach ($row as $col => $val) {
                if ($this->skipColumn($col)) {
                    continue;
                }
                if ($val !== null && $val !== '' && $val !== false && $val !== 0 && $val !== '0') {
                    return true;
                }
            }
        }

        return false;
    }

    public function categoryTables(string $category): array
    {
        return collect($this->categories[$category]['data'] ?? [])
            ->filter(fn ($rows) => $this->tableHasRows($rows))
            ->all();
    }

    public function downloadTable(string $category, string $tableName): StreamedResponse
    {
        $rows = $this->categories[$category]['data'][$tableName] ?? [];

        if (! $this->tableHasRows($rows)) {
            abort(404);
        }

        $columns = collect($rows[0])->keys()->filter(fn ($c) => ! $this->skipColumn($c))->values();

        return response()->streamDownload(function () use ($rows, $columns) {
            $out = fopen('php://output', 'w');

            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, $columns->map(fn ($c) => $this->columnLabel($c))->all());

            foreach ($rows as $row) {
                $values = $columns->map(function ($col) use ($row) {
                    $val = $row[$col] ?? '';

                    return is_bool($val) ? ($val ? 'Ada' : 'Tidak Ada') : $val;
                })->all();
                fputcsv($out, $values);
            }

            fclose($out);
        }, "data-desa-{$tableName}.csv", ['Content-Type' => 'text/csv']);
    }
}; ?>
{{-- Halaman DATA DESA (/data-desa) - 9 kategori, tabel dari tabel data_desa. --}}

<div class="py-12 md:py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold text-emerald-600 tracking-wider uppercase">Statistik & Wilayah</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2 mb-4">
                Data Desa {{ $desa->nama }}
            </h1>
            <p class="text-slate-500 leading-relaxed text-base sm:text-lg">
                {{ count($this->categories) }} Kategori &mdash; {{ collect($this->categories)->keys()->sum(fn ($key) => count($this->categoryTables($key))) }} tabel data statistik.
            </p>
        </div>

        <!-- Infografis Cards -->
        @php
            $pd = $this->categories['kependudukan']['data']['kpd_penduduk_dusun'] ?? [];
            $totalPenduduk = collect($pd)->sum('total');
            $totalLaki = collect($pd)->sum('laki_laki');
            $totalPerempuan = collect($pd)->sum('perempuan');
            if ($totalPenduduk === 0) {
                $pendidikanData = $this->categories['kependudukan']['data']['kpd_pendidikan'] ?? [];
                $totalPenduduk = collect($pendidikanData)->sum('jumlah');
            }
            $kkData = $this->categories['kependudukan']['data']['kpd_kk_dusun'] ?? [];
            $totalKK = collect($kkData)->sum('jumlah_kk');
            $dusunCount = count($pd);
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-100 flex items-center space-x-5">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                </div>
                <div>
                    <span class="text-xs font-medium text-slate-400 uppercase tracking-wider block">Luas Wilayah</span>
                    <span class="text-2xl font-bold text-slate-900 block mt-1">{{ ($profil['luas_wilayah_ha'] ?? '-') }} Ha</span>
                </div>
            </div>

            @if ($dusunCount > 0)
            <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-100 flex items-center space-x-5">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </div>
                <div>
                    <span class="text-xs font-medium text-slate-400 uppercase tracking-wider block">Wilayah Dusun</span>
                    <span class="text-2xl font-bold text-slate-900 block mt-1">{{ $dusunCount }} Dusun</span>
                </div>
            </div>
            @endif

            @if ($totalPenduduk > 0)
            <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-100 flex items-center space-x-5">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <div>
                    <span class="text-xs font-medium text-slate-400 uppercase tracking-wider block">Jumlah Penduduk</span>
                    <span class="text-2xl font-bold text-slate-900 block mt-1">{{ number_format($totalPenduduk, 0, ',', '.') }} Jiwa</span>
                </div>
            </div>
            @endif

            @if ($totalKK > 0)
            <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-100 flex items-center space-x-5">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                </div>
                <div>
                    <span class="text-xs font-medium text-slate-400 uppercase tracking-wider block">Jumlah KK</span>
                    <span class="text-2xl font-bold text-slate-900 block mt-1">{{ number_format($totalKK, 0, ',', '.') }} KK</span>
                </div>
            </div>
            @endif
        </div>

        <!-- Batas Administratif -->
        <div class="bg-white rounded-3xl p-8 md:p-12 shadow-xl border border-slate-100 mb-12">
            <h3 class="text-2xl font-extrabold text-slate-900 mb-8 flex items-center">
                <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 inline-flex items-center justify-center mr-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                </span>
                Batas Administratif Desa
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    <span class="text-xs font-bold text-emerald-600 tracking-wider uppercase block mb-3">Sebelah Utara</span>
                    <h4 class="text-lg font-bold text-slate-900">{{ $profil['batas_utara'] ?? '-' }}</h4>
                    <p class="text-xs text-slate-400 mt-1">Kecamatan Namlea</p>
                </div>
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    <span class="text-xs font-bold text-emerald-600 tracking-wider uppercase block mb-3">Sebelah Timur</span>
                    <h4 class="text-lg font-bold text-slate-900">{{ $profil['batas_timur'] ?? '-' }}</h4>
                    <p class="text-xs text-slate-400 mt-1">Batas Alami</p>
                </div>
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    <span class="text-xs font-bold text-emerald-600 tracking-wider uppercase block mb-3">Sebelah Selatan</span>
                    <h4 class="text-lg font-bold text-slate-900">{{ $profil['batas_selatan'] ?? '-' }}</h4>
                    <p class="text-xs text-slate-400 mt-1">Kecamatan Namlea</p>
                </div>
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    <span class="text-xs font-bold text-emerald-600 tracking-wider uppercase block mb-3">Sebelah Barat</span>
                    <h4 class="text-lg font-bold text-slate-900">{{ $profil['batas_barat'] ?? '-' }}</h4>
                    <p class="text-xs text-slate-400 mt-1">Batas Alami</p>
                </div>
            </div>
        </div>

        <!-- Accordion Sections -->
        <div class="mb-12">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-bold text-emerald-600 tracking-wider uppercase">Statistik Terkini</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2 mb-4">Kategori Data Desa</h2>
                <p class="text-slate-500 leading-relaxed text-sm sm:text-base">
                    Ringkasan data statistik makro Desa {{ $desa->nama }} menurut bidang. Klik kategori untuk melihat rinciannya.
                </p>
            </div>

            <div class="space-y-3">
            @foreach ($this->categories as $key => $category)
                @php
                    $tables = $this->categoryTables($key);
                    $tableCount = count($tables);
                    $isOpen = $this->openCategory === $key;
                @endphp
                <div class="bg-white rounded-3xl border overflow-hidden shadow-md transition-all duration-300 {{ $isOpen ? 'border-emerald-200 ring-1 ring-emerald-100' : 'border-slate-100 hover:shadow-xl' }}">
                    <button
                        wire:click="toggleCategory('{{ $key }}')"
                        class="w-full px-6 sm:px-8 py-9 flex items-center gap-6 transition-colors hover:bg-slate-50/70 text-left"
                    >
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 transition-colors duration-300 {{ $isOpen ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'bg-emerald-50 text-emerald-600' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $category['icon'] !!}</svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h2 class="text-xl font-bold text-slate-900 leading-snug">{{ $category['label'] }}</h2>
                            <p class="text-sm text-slate-500 mt-1">{{ $tableCount }} tabel data statistik</p>
                        </div>
                        <div class="px-5 py-2 rounded-xl text-sm font-semibold border bg-emerald-50 text-emerald-700 border-emerald-200/60 shrink-0 whitespace-nowrap">
                            {{ $tableCount }} tabel
                        </div>
                        <svg class="w-6 h-6 text-slate-400 transition-transform duration-300 shrink-0 {{ $isOpen ? 'rotate-90' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    @if ($this->openCategory === $key)
                        <div class="p-6 border-t border-slate-100 space-y-4">
                            @forelse ($tables as $tableName => $rows)
                                @if (count($rows) > 0)
                                    @php
                                        $columns = collect($rows[0])->keys()->filter(fn($c) => !$this->skipColumn($c))->values();
                                        $summary = $this->macroSummary($tableName, $rows);
                                        $open = $this->tableOpen($key, $tableName);
                                    @endphp
                                    <div class="rounded-2xl border border-slate-100 overflow-hidden">
                                        <div class="p-5 flex items-center justify-between gap-4">
                                            <div class="flex items-center gap-5 min-w-0">
                                                <div class="w-14 h-14 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                                    <span class="text-xl font-extrabold">{{ number_format((float) $summary['value'], 0, ',', '.') }}</span>
                                                </div>
                                                <div class="w-2"></div>
                                                <div class="min-w-0">
                                                    <h4 class="text-base font-semibold text-slate-800 leading-snug">
                                                        {{ $this->tableTitle($tableName) }}
                                                    </h4>
                                                    <p class="text-sm text-slate-500 mt-0.5">Data makro &mdash; {{ $summary['label'] }}</p>
                                                </div>
                                            </div>
                                            <button
                                                wire:click="toggleTable('{{ $key }}', '{{ $tableName }}')"
                                                type="button"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border shrink-0 transition-colors {{ $open ? 'bg-slate-100 text-slate-700 border-slate-200' : 'bg-slate-900 text-white hover:bg-emerald-600' }}"
                                            >
                                                <svg class="w-3.5 h-3.5 {{ $open ? 'rotate-90' : '' }} transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                                {{ $open ? 'Tutup Rincian' : 'Lihat Rincian' }}
                                            </button>
                                        </div>

                                        @if ($open)
                                            <div class="p-5 pt-0 border-t border-slate-100">
                                                <div class="flex items-start justify-between gap-4 mb-3">
                                                    <h4 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">
                                                        Rincian &mdash; {{ $this->tableTitle($tableName) }}
                                                    </h4>
                                                    <button
                                                        wire:click="downloadTable('{{ $key }}', '{{ $tableName }}')"
                                                        type="button"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-900 text-white hover:bg-emerald-600 transition-colors shrink-0"
                                                    >
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                                        Download CSV
                                                    </button>
                                                </div>
                                        <div class="overflow-x-auto rounded-xl border border-slate-200">
                                            <table class="w-full min-w-[520px] divide-y divide-slate-200 text-sm table-auto">
                                                <thead class="bg-slate-50">
                                                    <tr>
                                                        @foreach ($columns as $col)
                                                            <th class="px-4 py-3 whitespace-nowrap text-left text-xs font-bold text-slate-500 uppercase tracking-wider {{ in_array($col, ['laki_laki', 'perempuan', 'negeri', 'swasta', 'jumlah', 'total', 'luas_ha', 'jumlah_ruang', 'kondisi_baik', 'kondisi_rusak', 'jumlah_kader', 'jumlah_penerima', 'jumlah_kk', 'jumlah_kelompok', 'jumlah_anggota', 'jumlah_menara', 'jumlah_agen_ekspedisi', 'puskesmas', 'pustu', 'polindes', 'posyandu', 'klinik', 'masjid', 'musholla', 'gereja', 'pura', 'vihara', 'restoran', 'kedai', 'warung_makan', 'toko_kelontong', 'warung', 'minimarket', 'hotel', 'penginapan', 'homestay', 'bengkel_motor', 'bengkel_mobil', 'bengkel_las', 'pra_sejahtera', 'sejahtera_i', 'sejahtera_ii', 'sejahtera_iii', 'sejahtera_iii_plus']) ? 'text-right' : '' }}">
                                                                {{ $this->columnLabel($col) }}
                                                            </th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-slate-100">
                                                    @foreach ($rows as $row)
                                                        <tr class="hover:bg-slate-50/50">
                                                            @foreach ($columns as $col)
                                                                @php
                                                                    $val = $row[$col] ?? '';
                                                                    $isNumeric = is_numeric($val);
                                                                @endphp
                                                                <td class="px-4 py-2.5 align-middle {{ $isNumeric ? 'text-right whitespace-nowrap text-slate-700 font-medium' : 'text-slate-600' }}">
                                                                    @if (is_bool($val))
                                                                        @if ($val)
                                                                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Ada</span>
                                                                        @else
                                                                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500">Tidak Ada</span>
                                                                        @endif
                                                                    @elseif ($col === 'kondisi' || $col === 'kondisi_sinyal')
                                                                        @php
                                                                            $color = match (true) {
                                                                                str_contains($val, 'Baik') || str_contains($val, 'Kuat') => 'bg-emerald-100 text-emerald-700',
                                                                                str_contains($val, 'Sedang') => 'bg-amber-100 text-amber-700',
                                                                                default => 'bg-red-100 text-red-700',
                                                                            };
                                                                        @endphp
                                                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $color }}">{{ $val }}</span>
                                                                    @elseif ($col === 'status_gizi')
                                                                        @php
                                                                            $color = match (true) {
                                                                                str_contains($val, 'Baik') => 'text-emerald-700',
                                                                                str_contains($val, 'Kurang') => 'text-amber-700',
                                                                                str_contains($val, 'Buruk') => 'text-red-700',
                                                                                default => 'text-orange-700',
                                                                            };
                                                                        @endphp
                                                                        <span class="flex items-center gap-2">
                                                                            <span class="w-2 h-2 rounded-full {{ match(true) {
                                                                                str_contains($val, 'Baik') => 'bg-emerald-500',
                                                                                str_contains($val, 'Kurang') => 'bg-amber-500',
                                                                                str_contains($val, 'Buruk') => 'bg-red-500',
                                                                                default => 'bg-orange-400',
                                                                            } }}"></span>
                                                                            {{ $val }}
                                                                        </span>
                                                                    @elseif ($isNumeric)
                                                                        {{ number_format((float) $val, str_contains((string) $val, '.') ? 2 : 0, ',', '.') }}
                                                                    @else
                                                                        {{ $val }}
                                                                    @endif
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                @php
                                                    // Add totals row for numeric columns
                                                    $numericCols = $columns->filter(fn($c) => in_array($c, ['laki_laki', 'perempuan', 'negeri', 'swasta', 'jumlah', 'total', 'luas_ha', 'jumlah_ruang', 'kondisi_baik', 'kondisi_rusak', 'jumlah_kader', 'jumlah_penerima', 'jumlah_kk', 'jumlah_kelompok', 'jumlah_anggota', 'jumlah_menara', 'jumlah_agen_ekspedisi', 'puskesmas', 'pustu', 'polindes', 'posyandu', 'klinik', 'masjid', 'musholla', 'gereja', 'pura', 'vihara', 'restoran', 'kedai', 'warung_makan', 'toko_kelontong', 'warung', 'minimarket', 'hotel', 'penginapan', 'homestay', 'bengkel_motor', 'bengkel_mobil', 'bengkel_las', 'pra_sejahtera', 'sejahtera_i', 'sejahtera_ii', 'sejahtera_iii', 'sejahtera_iii_plus', 'jumlah_ruang']))->values();
                                                @endphp
                                                @if ($numericCols->isNotEmpty() && count($rows) > 1)
                                                    <tfoot>
                                                        <tr class="bg-slate-50 font-semibold text-slate-800">
                                                            @foreach ($columns as $col)
                                                                @if ($numericCols->contains($col))
                                                                    @php
                                                                        $sum = collect($rows)->sum(fn($r) => (float) ($r[$col] ?? 0));
                                                                    @endphp
                                                                    <td class="px-4 py-2.5 text-right {{ $col === 'laki_laki' ? 'text-emerald-600' : ($col === 'perempuan' ? 'text-rose-600' : '') }}">
                                                                        {{ number_format($sum, str_contains((string) ($rows[0][$col] ?? ''), '.') ? 2 : 0, ',', '.') }}
                                                                    </td>
                                                                @else
                                                                    <td class="px-4 py-2.5 {{ $loop->first ? '' : 'text-slate-400' }}">
                                                                        {{ $loop->first ? 'Total' : '' }}
                                                                    </td>
                                                                @endif
                                                            @endforeach
                                                        </tr>
                                                    </tfoot>
                                                @endif
                                            </table>
                                        </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @empty
                                <div class="bg-slate-50 rounded-2xl p-10 text-center">
                                    <div class="w-12 h-12 rounded-full bg-white text-slate-400 shadow-sm flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p class="text-slate-500 text-sm">Belum ada data statistik untuk kategori ini.</p>
                                </div>
                            @endforelse
                        </div>
                    @endif
                </div>
            @endforeach
            </div>
        </div>

    </div>
</div>
