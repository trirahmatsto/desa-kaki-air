<?php
/**
 * Service: ambil data potensi desa + sensus dari Supabase REST (lihat config/data-desa.php). Dipakai perintah php artisan data-desa:fetch.
 */

namespace App\Services;

use App\Models\DataDesa;
use App\Models\Desa;
use Illuminate\Support\Facades\Http;

class DataDesaImporter
{
    public function fetchAll(string $desaNama): ?array
    {
        $url = config('data-desa.supabase_url');
        $key = config('data-desa.supabase_key');

        $grouped = [];

        foreach (config('data-desa.tables') as $table => $kategori) {
            $rows = $this->get($url, $key, "/rest/v1/{$table}", ['desa' => "eq.{$desaNama}", 'select' => '*']);

            if ($rows === null) {
                return null;
            }

            $grouped[$kategori][$table] = $rows;
        }

        return $grouped;
    }

    public function fetchSensus(string $desaNama): ?array
    {
        $url = config('data-desa.supabase_url');
        $key = config('data-desa.supabase_key');

        $families = $this->rpc($url, $key, 'get_sensus_keluarga', $desaNama);
        $people = $this->rpc($url, $key, 'get_sensus_penduduk', $desaNama);

        if ($families === null || $people === null) {
            return null;
        }

        return [
            'sensus_perumahan' => $this->buildKeluargaTables($families),
            'sensus_ekonomi' => [
                'sens_lapangan_usaha' => $this->simpleTable($families, 'jenis_usaha', 'lapangan_usaha'),
                'sens_izin_usaha' => $this->simpleTable($families, 'izin_usaha', 'jenis_izin'),
            ],
            'sensus_kependudukan' => [
                'sens_kelompok_umur' => $this->genderTable($people, 'kelompok_umur', 'kelompok_umur'),
                'sens_status_perkawinan' => $this->genderTable($people, 'status_perkawinan', 'status_perkawinan'),
                'sens_baca_tulis' => $this->genderTable($people, 'baca_tulis', 'kemampuan'),
                'sens_partisipasi_sekolah' => $this->simpleTable($people, 'partisipasi_sekolah', 'partisipasi'),
                'sens_ijazah' => $this->simpleTable($people, 'ijazah_tertinggi', 'ijazah'),
                'sens_status_pekerjaan' => $this->simpleTable($people, 'status_pekerjaan', 'jenis_pekerjaan'),
            ],
        ];
    }

    public function import(string $desaNama, array $categories): void
    {
        $desa = Desa::firstOrCreate(['slug' => str($desaNama)->slug()], ['nama' => $desaNama]);

        foreach ($categories as $kategori => $tables) {
            DataDesa::updateOrCreate(
                ['desa_id' => $desa->id, 'kategori' => $kategori],
                ['data' => $tables]
            );
        }
    }

    protected function get(string $url, string $key, string $path, array $query): ?array
    {
        $response = Http::withHeaders(['apikey' => $key])
            ->withToken($key, 'Bearer')
            ->timeout(60)
            ->get($url.$path, $query);

        return $response->successful() ? $response->json() : null;
    }

    protected function rpc(string $url, string $key, string $function, string $desa): ?array
    {
        $response = Http::withHeaders(['apikey' => $key])
            ->withToken($key, 'Bearer')
            ->timeout(60)
            ->post("{$url}/rest/v1/rpc/{$function}", ['p_desa' => $desa]);

        return $response->successful() ? $response->json() : null;
    }

    protected function buildKeluargaTables(array $rows): array
    {
        $map = [
            'jenis_bangunan' => ['sens_jenis_bangunan', 'jenis_bangunan'],
            'kepemilikan_tanah' => ['sens_kepemilikan_bangunan', 'status_kepemilikan'],
            'lantai_rumah' => ['sens_lantai', 'jenis_lantai'],
            'dinding_rumah' => ['sens_dinding', 'jenis_dinding'],
            'atap_rumah' => ['sens_atap', 'jenis_atap'],
            'sumber_air_minum' => ['sens_air_minum', 'sumber_air_minum'],
            'sumber_air_mandi' => ['sens_air_mandi', 'sumber_air_mandi'],
            'sumber_penerangan' => ['sens_penerangan', 'sumber_penerangan'],
            'bahan_bakar' => ['sens_bahan_bakar', 'bahan_bakar'],
        ];

        $result = [];

        foreach ($map as $kategori => [$table, $labelCol]) {
            $result[$table] = $this->simpleTable($rows, $kategori, $labelCol);
        }

        return $result;
    }

    protected function simpleTable(array $rows, string $kategori, string $labelCol): array
    {
        return collect($rows)
            ->where('kategori', $kategori)
            ->sortBy('sub_kategori')
            ->map(fn ($row) => [$labelCol => $row['sub_kategori'], 'jumlah' => (int) $row['jumlah']])
            ->values()
            ->all();
    }

    protected function genderTable(array $rows, string $kategori, string $labelCol): array
    {
        return collect($rows)
            ->where('kategori', $kategori)
            ->groupBy('sub_kategori')
            ->sortKeys()
            ->map(function ($group, $sub) use ($labelCol) {
                $row = [$labelCol => $sub, 'laki_laki' => 0, 'perempuan' => 0];

                foreach ($group as $item) {
                    $key = $item['jenis_kelamin'] === 'Laki-laki' ? 'laki_laki' : 'perempuan';
                    $row[$key] = (int) $item['jumlah'];
                }

                return $row;
            })
            ->values()
            ->all();
    }
}
