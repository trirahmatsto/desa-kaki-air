<?php

use App\Models\DataDesa;
use App\Models\Desa;
use Illuminate\Support\Facades\Http;

$families = [
    ['kategori' => 'jenis_bangunan', 'sub_kategori' => 'Rumah tinggal tunggal', 'jumlah' => 285],
    ['kategori' => 'sumber_air_minum', 'sub_kategori' => 'Air isi ulang', 'jumlah' => 151],
    ['kategori' => 'sumber_air_minum', 'sub_kategori' => 'Leding/PDAM', 'jumlah' => 97],
];

$people = [
    ['kategori' => 'kelompok_umur', 'sub_kategori' => '0-4 tahun', 'jenis_kelamin' => 'Laki-laki', 'jumlah' => 29],
    ['kategori' => 'kelompok_umur', 'sub_kategori' => '0-4 tahun', 'jenis_kelamin' => 'Perempuan', 'jumlah' => 19],
    ['kategori' => 'ijazah_tertinggi', 'sub_kategori' => 'SMA/sederajat', 'jenis_kelamin' => 'Semua', 'jumlah' => 281],
];

beforeEach(function () use ($families, $people) {
    Http::fake(function ($request) use ($families, $people) {
        $url = $request->url();

        if (str_contains($url, '/rpc/get_sensus_keluarga')) {
            return Http::response($families);
        }

        if (str_contains($url, '/rpc/get_sensus_penduduk')) {
            return Http::response($people);
        }

        if (str_contains($url, '/rest/v1/sosial_sekolah')) {
            return Http::response([
                ['id' => 1, 'desa' => 'Kaki Air', 'tingkat_pendidikan' => 'SD/Sederajat', 'negeri' => 2, 'swasta' => 1, 'created_at' => '2026-01-01'],
            ]);
        }

        return Http::response([]);
    });
});

test('data-desa:fetch imports all 9 categories from Supabase REST', function () {
    $this->artisan('data-desa:fetch')->assertExitCode(0);

    expect(Desa::count())->toBe(1);
    expect(DataDesa::count())->toBe(9);

    $social = DataDesa::where('kategori', 'sosial')->first();
    expect($social->data['sosial_sekolah'])->toHaveCount(1);

    $housing = DataDesa::where('kategori', 'sensus_perumahan')->first();
    expect($housing->data['sens_jenis_bangunan'][0]['jumlah'])->toBe(285);
    expect(collect($housing->data['sens_air_minum'])->sum('jumlah'))->toBe(248);

    $peopleData = DataDesa::where('kategori', 'sensus_kependudukan')->first();
    expect($peopleData->data['sens_kelompok_umur'])->toBe([
        ['kelompok_umur' => '0-4 tahun', 'laki_laki' => 29, 'perempuan' => 19],
    ]);
    expect($peopleData->data['sens_ijazah'][0])->toBe(['ijazah' => 'SMA/sederajat', 'jumlah' => 281]);

    expect(DataDesa::where('kategori', 'sensus_ekonomi')->exists())->toBeTrue();
    expect(DataDesa::where('kategori', 'pertanian')->exists())->toBeTrue();
});

test('data-desa:fetch is idempotent', function () {
    $this->artisan('data-desa:fetch')->assertExitCode(0);
    $this->artisan('data-desa:fetch')->assertExitCode(0);

    expect(Desa::count())->toBe(1);
    expect(DataDesa::count())->toBe(9);
});
