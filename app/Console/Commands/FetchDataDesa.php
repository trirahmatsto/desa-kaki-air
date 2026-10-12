<?php
/**
 * Perintah CLI: php artisan data-desa:fetch - fetch data desa dari Supabase lalu simpan ke tabel data_desa. Jadwal harian via schedule.
 */

namespace App\Console\Commands;

use App\Services\DataDesaImporter;
use Illuminate\Console\Command;

class FetchDataDesa extends Command
{
    protected $signature = 'data-desa:fetch';

    protected $description = 'Fetch data desa (potensi desa + sensus) langsung dari Supabase REST dan import ke database';

    public function handle(): int
    {
        $importer = new DataDesaImporter;

        $desas = \App\Models\Desa::pluck('nama');

        if ($desas->isEmpty()) {
            $desas = collect(['Kaki Air']);
        }

        foreach ($desas as $desaNama) {
            $categories = $importer->fetchAll($desaNama);

            if ($categories === null) {
                $this->error("Gagal mengambil tabel potensi desa untuk {$desaNama}.");
                return self::FAILURE;
            }

            $sensus = $importer->fetchSensus($desaNama);

            if ($sensus === null) {
                $this->error("Gagal mengambil data sensus untuk {$desaNama}.");
                return self::FAILURE;
            }

            $importer->import($desaNama, array_merge($categories, $sensus));

            $tableCount = collect($categories)->sum(fn ($t) => count($t)) + collect($sensus)->sum(fn ($t) => count($t));

            $this->info("  ✓ {$desaNama}: " . (count($categories) + count($sensus)) . " kategori, {$tableCount} tabel");
        }

        $this->info("\nData Desa fetched and imported successfully!");

        return self::SUCCESS;
    }
}
