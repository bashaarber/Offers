#!/bin/bash
# Local development startup script for SanivorOffers
# Run this from the project root: bash start-local.sh

set -e

echo "==> Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "==> Running migrations..."
php artisan migrate

echo "==> Starting local server at http://localhost:8000"
php artisan serve
