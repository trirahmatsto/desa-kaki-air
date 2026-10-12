<?php
/**
 * Cek isi tabel desa & data_desa di DB. Jalankan: php scripts/check-data.php
 */
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Desa;
use App\Models\DataDesa;

echo "Desa: " . Desa::count() . PHP_EOL;
foreach (Desa::all() as $d) {
    echo "  - {$d->nama}\n";
}

echo PHP_EOL . "DataDesa: " . DataDesa::count() . PHP_EOL;
foreach (DataDesa::with('desa')->get() as $dd) {
    $tableCount = count($dd->data);
    echo "  {$dd->desa->nama} / {$dd->kategori}: {$tableCount} tables\n";
    $tables = implode(', ', array_keys($dd->data));
    echo "    Tables: {$tables}\n";
}
