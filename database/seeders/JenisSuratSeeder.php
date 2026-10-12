<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use Illuminate\Database\Seeder;

class JenisSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisSurats = [
            [
                'nama_surat' => 'Surat Keterangan Tidak Mampu (SKTM)',
                'kode_surat' => 'SKTM',
                'is_aktif' => true,
            ],
            [
                'nama_surat' => 'Surat Keterangan Domisili',
                'kode_surat' => 'SKD',
                'is_aktif' => true,
            ],
            [
                'nama_surat' => 'Surat Keterangan Usaha (SKU)',
                'kode_surat' => 'SKU',
                'is_aktif' => true,
            ],
            [
                'nama_surat' => 'Surat Keterangan Pengantar Kelakuan Baik',
                'kode_surat' => 'SKP',
                'is_aktif' => true,
            ],
            [
                'nama_surat' => 'Surat Keterangan Bersih Diri',
                'kode_surat' => 'SKBD',
                'is_aktif' => true,
            ],
            [
                'nama_surat' => 'Surat Keterangan Belum Menikah',
                'kode_surat' => 'SKBM',
                'is_aktif' => true,
            ],
        ];

        foreach ($jenisSurats as $surat) {
            JenisSurat::updateOrCreate(
                ['kode_surat' => $surat['kode_surat']],
                [
                    'nama_surat' => $surat['nama_surat'],
                    'is_aktif' => $surat['is_aktif'],
                ]
            );
        }
    }
}
