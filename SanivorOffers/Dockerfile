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

# Run migrations, seeders, caching, and start server
CMD ["sh", "-c", "\
  chmod -R 777 storage bootstrap/cache && \
  mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs && \
  php artisan config:clear 2>/dev/null; \
  php artisan cache:clear 2>/dev/null; \
  php artisan route:clear 2>/dev/null; \
  php artisan view:clear 2>/dev/null; \
  echo 'Running fresh migrations...' && \
  php artisan migrate:fresh --force --seed && \
  echo 'Migrations and seeding complete!' && \
  php artisan config:cache && \
  php artisan route:cache && \
  php artisan view:cache && \
  echo 'Starting PHP server on port ${PORT:-8000}...' && \
  php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]
