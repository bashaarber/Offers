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

# Copy and set permissions for entrypoint script (fix line endings + make executable)
COPY docker-entrypoint.sh /usr/local/bin/
RUN sed -i 's/\r$//' /usr/local/bin/docker-entrypoint.sh && chmod +x /usr/local/bin/docker-entrypoint.sh

# Set permissions for storage and cache
RUN chmod -R 775 /app/storage /app/bootstrap/cache || true
RUN mkdir -p /app/storage/framework/{sessions,views,cache} || true
RUN mkdir -p /app/storage/logs || true

# Expose port (Render will provide PORT env variable)
EXPOSE 8000

# Use entrypoint script
ENTRYPOINT ["docker-entrypoint.sh"]
