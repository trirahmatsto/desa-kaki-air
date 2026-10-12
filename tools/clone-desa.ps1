# Tool: klon projek desa menjadi projek baru (PowerShell).
param(
    [string]$NamaDesa   = "Savana Jaya",
    [string]$Slug       = "savana-jaya",
    [string]$SlugDomain = "savanajaya-descan",
    [string]$Domain     = "savanajaya-descan.bpsburu.com",
    [string]$EmailDomain = "savana.desa.id",
    [string]$DbName     = "u639579287_savana_jaya",
    [string]$SourceDir  = "C:\xampp\htdocs\bupolo-berseri",
    [string]$TargetDir  = "C:\xampp\htdocs\savana-jaya"
)
$ErrorActionPreference = 'Stop'

$TextExt = @('*.php', '*.blade.php', '*.js', '*.ts', '*.json', '*.md', '*.txt', '*.html', '*.css', '*.yml', '*.yaml', '*.xml', '*.config', '*.htaccess', '*.editorconfig', '*.gitignore', '*.env.example')
$SkipDirs = @('vendor', 'node_modules', 'storage', 'deploy', '.vscode', '.git', 'public\build')

function Write-Utf8([string]$path, [string]$content) {
    [System.IO.File]::WriteAllText($path, $content, (New-Object System.Text.UTF8Encoding $false))
}

Write-Host "== 1. Robocopy =="
if (Test-Path $TargetDir) { Remove-Item $TargetDir -Recurse -Force }
$xd = @()
foreach ($d in @('storage\logs', 'storage\framework\views', 'storage\framework\sessions', 'storage\framework\cache', 'storage\framework\testing', 'storage\app')) { $xd += "$SourceDir\$d" }
$xd += "$SourceDir\.git"; $xd += "$SourceDir\node_modules"; $xd += "$SourceDir\vendor"; $xd += "$SourceDir\deploy"; $xd += "$SourceDir\.vscode"
& robocopy $SourceDir $TargetDir /E /XD $xd /XF ".env" | Out-Null
if ($LASTEXITCODE -ge 8) { throw "robocopy failed ($LASTEXITCODE)" }

Write-Host "== 2. Replace teks =="
$replaced = 0
Get-ChildItem $TargetDir -Recurse -File | ForEach-Object {
    $f = $_
    $rel = $f.FullName.Substring($TargetDir.Length + 1)
    foreach ($skip in $SkipDirs) {
        if ($rel -match [regex]::Escape("$skip\") -or $rel -eq $skip) { return }
    }
    if ($f.Extension -notin @('.php', '.blade.php', '.js', '.ts', '.json', '.md', '.txt', '.html', '.css', '.yml', '.yaml', '.xml', '.config', '.htaccess', '.editorconfig', '.gitignore', '.env.example')) { return }
    $content = [System.IO.File]::ReadAllText($f.FullName)
    $new = $content
    $new = $new.Replace('jamilu', $Slug)
    $new = $new.Replace('Jamilu', $NamaDesa)
    $new = $new.Replace('bupolo.desa.id', $EmailDomain)
    $new = $new.Replace('Bupolo', $NamaDesa)
    $new = $new.Replace("$Slug-descan", $SlugDomain)
    if ($new -ne $content) { Write-Utf8 $f.FullName $new; $replaced++ }
}
Write-Host "  files replaced: $replaced"

Write-Host "== 3. Rename file gambar =="
Get-ChildItem "$TargetDir\public\images" -File | ForEach-Object {
    if ($_.Name -match 'Jamilu') {
        $newName = $_.Name.Replace('Jamilu', $NamaDesa)
        Rename-Item $_.FullName $newName
        Write-Host "  $($_.Name) -> $newName"
    }
}

Write-Host "== 4. Bersihkan cache =="
foreach ($d in @("$TargetDir\storage\framework\views", "$TargetDir\storage\framework\sessions", "$TargetDir\storage\framework\cache", "$TargetDir\storage\logs")) {
    if (Test-Path $d) { Get-ChildItem $d -File -ErrorAction SilentlyContinue | Remove-Item -Force }
}
if (Test-Path "$TargetDir\bootstrap\cache") { Get-ChildItem "$TargetDir\bootstrap\cache" -Filter "*.php" -ErrorAction SilentlyContinue | Remove-Item -Force }

Write-Host "== 5. Buat .env =="
$envSrc = Get-Content "$SourceDir\.env" -Raw
$envNew = $envSrc
$envNew = $envNew -replace '(?m)^APP_NAME=.*$', ('APP_NAME="Portal Desa ' + $NamaDesa + '"')
$envNew = $envNew -replace '(?m)^APP_URL=.*$', ("APP_URL=https://" + $Domain)
$envNew = $envNew -replace '(?m)^DB_DATABASE=.*$', ("DB_DATABASE=" + $DbName)
$envNew = $envNew -replace '(?m)^DB_USERNAME=.*$', 'DB_USERNAME=u639579287_trirahmat'
Write-Utf8 "$TargetDir\.env" $envNew

Write-Host "== 6. composer install --no-dev =="
Push-Location $TargetDir
& composer install --no-dev --optimize-autoloader 2>&1 | Select-Object -Last 3
if ($LASTEXITCODE -ne 0) { Pop-Location; throw "composer install failed" }
Write-Host "== 7. key:generate =="
& php artisan key:generate --force 2>&1 | Select-Object -Last 2
if ($LASTEXITCODE -ne 0) { Pop-Location; throw "key:generate failed" }
& php artisan storage:link 2>&1 | Select-Object -Last 1
Pop-Location

Write-Host "== 8. Template .env.production.example untuk deploy =="
$tpl = Get-Content "$TargetDir\.env.production.example" -Raw
$tpl = $tpl -replace '(?m)^APP_NAME=.*$', ('APP_NAME="Portal Desa ' + $NamaDesa + '"')
$tpl = $tpl -replace '(?m)^APP_URL=.*$', ("APP_URL=https://" + $Domain)
$tpl = $tpl -replace '(?m)^DB_DATABASE=.*$', ("DB_DATABASE=" + $DbName)
Write-Utf8 "$TargetDir\.env.production.example" $tpl

Write-Host "== 9. Zip =="
$deployDir = "$TargetDir\deploy"
if (-not (Test-Path $deployDir)) { New-Item -ItemType Directory -Path $deployDir | Out-Null }
$zipPath = "$deployDir\deploy-$Slug.zip"
if (Test-Path $zipPath) { Remove-Item $zipPath -Force }
Add-Type -AssemblyName System.IO.Compression.FileSystem
[System.IO.Compression.ZipFile]::CreateFromDirectory($TargetDir, $zipPath, [System.IO.Compression.CompressionLevel]::Optimal, $false)
Write-Host "zip: $zipPath ($([math]::Round((Get-Item $zipPath).Length/1MB,1)) MB)"

Write-Host "`nSELESAI. Target: $TargetDir"
