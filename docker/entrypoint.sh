#!/bin/bash
set -e

# Cache config & routes untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan migrasi (--force untuk production)
php artisan migrate --force

exec "$@"
