FROM php:8.2-cli

# Set working directory
WORKDIR /app

# Install system dependencies including Node.js
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libfontconfig1 \
    fontconfig \
    fonts-dejavu-core \
    zip \
    unzip \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
# (cache-bust 2026-04-18: force rebuild to pick up renamed lock-fields migration)
COPY . /app

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Install Node.js dependencies and build assets
RUN npm install && npm run build

# Set permissions for storage and cache
RUN chmod -R 775 /app/storage /app/bootstrap/cache || true
RUN mkdir -p /app/storage/framework/{sessions,views,cache} || true
RUN mkdir -p /app/storage/logs || true

# Expose port (Render will provide PORT env variable)
EXPOSE 8000

# Run app startup tasks and start server (Render sets PORT)
# Optional env flags:
# - RUN_MIGRATIONS=true|false (default: true)
# - RUN_SEEDERS=true|false (default: false)
# - RUN_ELEMENTS_REPAIR=true|false (default: false)
CMD sh -c "\
  chmod -R 777 storage bootstrap/cache && \
  mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs && \
  php artisan config:clear 2>/dev/null; \
  php artisan cache:clear 2>/dev/null; \
  php artisan route:clear 2>/dev/null; \
  php artisan view:clear 2>/dev/null; \
  if [ \"$CLEANUP_PG_ENUMS\" = \"true\" ]; then \
    echo 'CLEANUP_PG_ENUMS=true: running enum cleanup (recovery only)...' && \
    php cleanup-enums.php 2>/dev/null || true; \
  fi && \
  if [ \"${RUN_MIGRATIONS:-true}\" = \"true\" ]; then \
    echo 'Running migrations...' && php artisan migrate --force; \
  else \
    echo 'Skipping migrations (RUN_MIGRATIONS=false).'; \
  fi && \
  if [ \"${RUN_SEEDERS:-false}\" = \"true\" ]; then \
    echo 'Running seeders...' && (php artisan db:seed --force || echo 'Warning: db:seed exited non-zero (see logs).'); \
  else \
    echo 'Skipping seeders (RUN_SEEDERS=false).'; \
  fi && \
  if [ \"${RUN_ELEMENTS_REPAIR:-false}\" = \"true\" ]; then \
    echo 'Running element-material repair check...' && (php artisan elements:check-materials --repair || echo 'Warning: element-material repair check failed (see logs).'); \
  else \
    echo 'Skipping element-material repair check (RUN_ELEMENTS_REPAIR=false).'; \
  fi && \
  echo 'Startup tasks complete.' && \
  php artisan config:cache && \
  php artisan route:cache && \
  php artisan view:cache && \
  echo \"Starting PHP server on port ${PORT:-8000}...\" && \
  php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"
