<?php
/**
 * Konfigurasi sumber data desa (Supabase). URL dan key dibaca dari .env (SUPABASE_URL, SUPABASE_ANON_KEY).
 */

return [
    'supabase_url' => env('SUPABASE_URL', 'https://mofyetjalfukgacppuwb.supabase.co'),
    'supabase_key' => env('SUPABASE_ANON_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im1vZnlldGphbGZ1a2dhY3BwdXdiIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzYzNTMwOTMsImV4cCI6MjA5MTkyOTA5M30.07nnzQsJJFXl1ssr784XneuASw6nrmU9pAeteYA5Rmw'),

    // 30 tabel potensi desa -> kategori data_desa
    'tables' => [
        // Sosial
        'sosial_sekolah' => 'sosial',
        'sosial_guru' => 'sosial',
        'sosial_murid' => 'sosial',
        'sosial_ruang_kelas' => 'sosial',
        'sosial_faskes' => 'sosial',
        'sosial_posyandu' => 'sosial',
        'sosial_gizi' => 'sosial',
        'sosial_kesejahteraan' => 'sosial',
        'sosial_bansos' => 'sosial',
        // Kependudukan
        'kpd_penduduk_dusun' => 'kependudukan',
        'kpd_pendidikan' => 'kependudukan',
        'kpd_kk_dusun' => 'kependudukan',
        'kpd_pekerjaan' => 'kependudukan',
        'kpd_tempat_ibadah' => 'kependudukan',
        'kpd_masjid' => 'kependudukan',
        'kpd_musholla' => 'kependudukan',
        'kpd_majelis_taklim' => 'kependudukan',
        'kpd_fasilitas_olahraga' => 'kependudukan',
        // Pertanian
        'tani_lahan_irigasi' => 'pertanian',
        'tani_hutan' => 'pertanian',
        // Perdagangan
        'dagang_industri' => 'perdagangan',
        'dagang_pasar' => 'perdagangan',
        'dagang_bank_koperasi' => 'perdagangan',
        // Pariwisata
        'wisata_restoran' => 'pariwisata',
        'wisata_toko' => 'pariwisata',
        'wisata_penginapan' => 'pariwisata',
        // Transportasi
        'trans_kendaraan' => 'transportasi',
        'trans_menara' => 'transportasi',
        'trans_bengkel' => 'transportasi',
        'trans_kantor_pos' => 'transportasi',
    ],
];
