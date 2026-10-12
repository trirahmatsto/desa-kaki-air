<?php

use App\Models\DataDesa;
use App\Models\Desa;
use Livewire\Livewire;

function createKakiAirData(): void
{
    $desa = Desa::create(['nama' => 'Kaki Air']);
    DataDesa::create([
        'desa_id' => $desa->id,
        'kategori' => 'sosial',
        'data' => [
            'sosial_sekolah' => [
                ['id' => 1, 'desa' => 'Kaki Air', 'tingkat_pendidikan' => 'SD/Sederajat', 'negeri' => 2, 'swasta' => 1, 'created_at' => '2026-01-01'],
            ],
        ],
    ]);
    DataDesa::create([
        'desa_id' => $desa->id,
        'kategori' => 'pertanian',
        'data' => [
            'tani_hutan' => [],
        ],
    ]);
    DataDesa::create([
        'desa_id' => $desa->id,
        'kategori' => 'kependudukan',
        'data' => [
            'kpd_kk_dusun' => [
                ['id' => 1, 'desa' => 'Kaki Air', 'nama_dusun' => '', 'jumlah_kk' => 0, 'created_at' => '2026-01-01'],
            ],
        ],
    ]);
}

test('data desa page renders macro summary with real data only', function () {
    createKakiAirData();

    $response = $this->get('/data-desa');

    $response->assertOk();
    $response->assertSee('Data Desa Kaki Air');
    $response->assertSee('1 tabel data statistik');
    $response->assertSee('Data makro');
    $response->assertSee('Lihat Rincian');
    $response->assertDontSee('SD/Sederajat');
    $response->assertDontSee('Download CSV');
    $response->assertSee('0 tabel data');
    $response->assertDontSee('Jumlah KK');
});

test('micro detail can be expanded from macro summary', function () {
    createKakiAirData();

    Livewire::test('public.data-desa')
        ->assertDontSee('SD/Sederajat')
        ->call('toggleTable', 'sosial', 'sosial_sekolah')
        ->assertSee('SD/Sederajat')
        ->assertSee('Download CSV')
        ->call('toggleTable', 'sosial', 'sosial_sekolah')
        ->assertDontSee('SD/Sederajat');
});

test('table can be downloaded as csv', function () {
    createKakiAirData();

    Livewire::test('public.data-desa')
        ->call('downloadTable', 'sosial', 'sosial_sekolah')
        ->assertFileDownloaded(
            'data-desa-sosial_sekolah.csv',
            "\xEF\xBB\xBF\"Tingkat Pendidikan\",Negeri,Swasta\nSD/Sederajat,2,1\n"
        );
});

test('cannot download empty table', function () {
    createKakiAirData();

    Livewire::test('public.data-desa')
        ->call('downloadTable', 'pertanian', 'tani_hutan')
        ->assertStatus(404);
});

test('census categories render macro summary and can be expanded', function () {
    $desa = Desa::create(['nama' => 'Kaki Air']);
    DataDesa::create([
        'desa_id' => $desa->id,
        'kategori' => 'sensus_perumahan',
        'data' => [
            'sens_air_minum' => [
                ['sumber_air_minum' => 'Air isi ulang', 'jumlah' => 151],
                ['sumber_air_minum' => 'Leding/PDAM', 'jumlah' => 97],
            ],
        ],
    ]);
    DataDesa::create([
        'desa_id' => $desa->id,
        'kategori' => 'sensus_kependudukan',
        'data' => [
            'sens_kelompok_umur' => [
                ['kelompok_umur' => '0-4 tahun', 'laki_laki' => 29, 'perempuan' => 19],
            ],
            'sens_ijazah' => [
                ['ijazah' => 'SMA/sederajat', 'jumlah' => 281],
            ],
        ],
    ]);

    $this->get('/data-desa')
        ->assertOk()
        ->assertSee('Perumahan dan Permukiman')
        ->assertSee('Kependudukan (Hasil Sensus Desa)')
        ->assertSee('9 Kategori')
        ->assertSee('3 tabel data statistik');

    Livewire::test('public.data-desa')
        ->call('toggleCategory', 'sensus_perumahan')
        ->assertSee('Sumber Air Minum')
        ->assertSee('Data makro')
        ->assertDontSee('Air isi ulang')
        ->call('toggleTable', 'sensus_perumahan', 'sens_air_minum')
        ->assertSee('Air isi ulang');
});
