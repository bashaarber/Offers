# 🐳 Render.com Deployment with Docker

Since PHP runtime isn't available, we'll use Docker instead. This is actually better for Laravel!

---

## Step 1: Select Docker Runtime

In the Render dropdown:
- ✅ **Select "Docker"** (not Node, not Python, etc.)

---

## Step 2: Configure Render Service

### Basic Settings:
- **Name**: `sanivor-offers`
- **Region**: Choose closest to you
- **Branch**: `main` (or `master`)
- **Root Directory**: `SanivorOffers` ⚠️ IMPORTANT!
- **Runtime**: **Docker** ✅
- **Plan**: **Free**

### Docker Settings:
- **Dockerfile Path**: `Dockerfile` (or `./Dockerfile`)
- **Docker Context**: `.` (or leave empty)

### Build & Start Commands:

**Build Command** (leave empty - Docker handles this automatically):
```
(leave empty - Docker will build automatically)
```

**OR** if Render requires a build command:
```bash
docker build -t sanivor-offers .
```

**Start Command** (leave empty - Dockerfile CMD handles this):
```
(leave empty - Dockerfile CMD will run)
```

**OR** if Render requires a start command:
```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

### Environment Variables:

Add these environment variables:

```
APP_NAME=Sanivor Offers
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:2tn1BSN/+B/kckbq0epni+6esMsAD/5PJEoe1eSo3Yc=
APP_URL=https://sanivor-offers.onrender.com

LOG_CHANNEL=stderr
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=(from your PostgreSQL database)
DB_PORT=5432
DB_DATABASE=(from your PostgreSQL database)
DB_USERNAME=(from your PostgreSQL database)
DB_PASSWORD=(from your PostgreSQL database)
```

### Port Configuration:
- **Port**: `8000` (or `$PORT` if Render provides it)

---

## Step 3: Create PostgreSQL Database First

Before creating the web service:

1. Go to **"New +"** → **"PostgreSQL"**
2. Name: `sanivor-db`
3. Plan: **Free**
4. Click **"Create Database"**
5. **Copy the connection details** (Host, Port, Database, Username, Password)

---

## Step 4: Deploy

1. Click **"Create Web Service"**
2. Wait 10-15 minutes for first build (Docker builds take longer)
3. Watch the build logs for any errors

---

## Troubleshooting

### Issue: Build Fails

**Check:**
- Is `Dockerfile` in the `SanivorOffers` folder?
- Is `Root Directory` set to `SanivorOffers`?
- Check build logs for specific errors

### Issue: Port Not Found

**Fix:** Make sure your Dockerfile exposes port 8000 and uses `$PORT` environment variable:

```dockerfile
EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
```

### Issue: Database Connection Error

**Check:**
- Database credentials are correct
- Database is created and running
- Use **Internal Database URL** if both services are on Render

---

## Alternative: Simpler Dockerfile

If the current Dockerfile doesn't work, try this simpler version:

```dockerfile
FROM php:8.2-cli

WORKDIR /app

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port
EXPOSE 8000

# Start server
CMD php artisan serve --host=0.0.0.0 --port=8000
```

---

## Files Created

✅ `Dockerfile` - Docker configuration for Laravel
✅ `.dockerignore` - Files to exclude from Docker build
✅ `render.yaml` - Updated for Docker

---

## Next Steps

1. Make sure `Dockerfile` is committed to Git
2. Push to GitHub
3. Follow the steps above in Render dashboard
4. Deploy!

Good luck! 🚀
