<?php
/**
 * Cek sintaks PHP dalam blade. Jalankan: php scripts/check-blade.php
 */
$content = file_get_contents(__DIR__ . '/../resources/views/livewire/public/data-desa.blade.php');
preg_match('/<\?php(.*?)\?>/s', $content, $matches);
if (!$matches) {
    echo "No PHP code found\n";
    exit(1);
}
$phpCode = '<?php ' . $matches[1] . ' ?>';
$tmpFile = tempnam(sys_get_temp_dir(), 'phpcheck_') . '.php';
file_put_contents($tmpFile, $phpCode);
exec('php -l ' . escapeshellarg($tmpFile) . ' 2>&1', $output, $code);
echo implode("\n", $output) . "\n";
echo 'Exit code: ' . $code . "\n";
unlink($tmpFile);
exit($code);
