#!/bin/sh
set -e

echo "Starting Laravel application..."

# Clear all caches first
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Set storage permissions
chmod -R 775 storage bootstrap/cache || true
mkdir -p storage/framework/{sessions,views,cache} || true
mkdir -p storage/logs || true

# Run migrations
echo "Running migrations..."
php artisan migrate --force || echo "Migration failed, continuing..."

# Run seeders
echo "Running seeders..."
php artisan db:seed --force || echo "Seeding failed, continuing..."

# Cache configuration
echo "Caching configuration..."
php artisan config:cache || echo "Config cache failed, continuing..."
php artisan route:cache || echo "Route cache failed, continuing..."
php artisan view:cache || echo "View cache failed, continuing..."

# Start server
echo "Starting PHP server on port ${PORT:-8000}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
