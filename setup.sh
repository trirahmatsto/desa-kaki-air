#!/usr/bin/env bash
set -e
cd "$(dirname "$0")"

echo "PHP: $(php -v | head -n 1)"

if [ ! -f .env ]; then
    cp .env.production.example .env
    echo ""
    echo ">>> File .env dibuat dari template."
    echo ">>> WAJIB isi dulu password DB + email di dalamnya:"
    echo ">>>     nano .env"
    echo ">>>     cari: DB_PASSWORD= dan MAIL_USERNAME=/MAIL_PASSWORD="
    echo ">>> lalu jalankan ulang: bash setup.sh"
    exit 1
fi

echo ""
echo ">> composer install --no-dev"
composer install --no-dev --optimize-autoloader

echo ">> key:generate"
php artisan key:generate --force

echo ">> storage:link"
php artisan storage:link

echo ">> cache config/route/view"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ">> permissions storage & bootstrap/cache"
chmod -R 775 storage bootstrap/cache

echo ">> tes koneksi DB + ambil data desa"
php artisan data-desa:fetch

echo ""
echo "SELESAI. Buka https://jamilu-descan.bpsburu.com"
