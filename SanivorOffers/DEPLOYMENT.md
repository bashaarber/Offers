# Deployment Guide for Laravel Application

## ⚠️ Important: Netlify is NOT suitable for Laravel

Netlify is designed for static sites and serverless functions. Laravel requires:
- PHP runtime (8.1+)
- Server-side rendering (Blade templates)
- Database connections (SQLite/MySQL/PostgreSQL)
- Artisan commands

## Recommended Deployment Options

### Option 1: Laravel Vapor (Best for Serverless)
**Pros:** Serverless, auto-scaling, AWS-powered
**Cost:** Pay-per-use

```bash
# Install Vapor CLI
composer require laravel/vapor-cli --global

# Initialize Vapor
vapor init

# Deploy
vapor deploy production
```

### Option 2: Render.com (Best Free Option) ⭐
**Pros:** Free tier available, easy setup, free PostgreSQL
**Cost:** FREE (with limitations) or $7/month

**Note:** Heroku removed their free tier in November 2022. Render.com is the best free alternative.

1. Create `Procfile`:
```
web: vendor/bin/heroku-php-apache2 public/
```

2. Create `composer.json` scripts:
```json
"scripts": {
    "post-install-cmd": [
        "php artisan migrate --force",
        "php artisan db:seed --force"
    ]
}
```

3. Deploy:
```bash
heroku create your-app-name
git push heroku main
```

### Option 3: DigitalOcean App Platform
**Pros:** Managed Laravel hosting, easy setup
**Cost:** $5/month+

1. Connect your GitHub repository
2. Select Laravel preset
3. Configure environment variables
4. Deploy automatically

### Option 4: Traditional VPS (Most Control)
**Pros:** Full control, cost-effective
**Cost:** $5-20/month

**Recommended:** DigitalOcean Droplet or Linode

**Setup Steps:**
1. Create Ubuntu 22.04 LTS droplet
2. Install LAMP/LEMP stack
3. Clone repository
4. Configure Nginx/Apache
5. Set up SSL with Let's Encrypt

## Environment Variables Needed

Create a `.env` file with:
```env
APP_NAME="Sanivor Offers"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite

# Or use MySQL/PostgreSQL for production
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=your_database
# DB_USERNAME=your_username
# DB_PASSWORD=your_password
```

## Pre-Deployment Checklist

1. ✅ Run `php artisan key:generate`
2. ✅ Set `APP_ENV=production` and `APP_DEBUG=false`
3. ✅ Build assets: `npm run build`
4. ✅ Run migrations: `php artisan migrate --force`
5. ✅ Seed database: `php artisan db:seed --force`
6. ✅ Set proper file permissions
7. ✅ Configure web server (Nginx/Apache)
8. ✅ Set up SSL certificate
9. ✅ Configure queue workers (if using queues)
10. ✅ Set up scheduled tasks (cron jobs)

## Quick Start: Render.com Deployment (FREE) ⭐

**Render.com offers the best free tier for Laravel!**

1. **Sign up** at [render.com](https://render.com) (free)

2. **Create New Web Service:**
   - Click "New +" → "Web Service"
   - Connect your GitHub repository
   - Select your repository and branch
   - **Build Command:** `composer install --no-dev --optimize-autoloader && php artisan migrate --force && php artisan db:seed --force`
   - **Start Command:** `php artisan serve --host=0.0.0.0 --port=$PORT`

3. **Create PostgreSQL Database (FREE):**
   - Click "New +" → "PostgreSQL"
   - Name it (e.g., "sanivor-db")
   - **Note:** Free database expires after 30 days unless upgraded

4. **Set Environment Variables:**
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:YOUR_GENERATED_KEY
   DB_CONNECTION=pgsql
   DB_HOST=(from Render database dashboard)
   DB_DATABASE=(from Render database dashboard)
   DB_USERNAME=(from Render database dashboard)
   DB_PASSWORD=(from Render database dashboard)
   ```

5. **Deploy!** Render will automatically deploy on every git push.

**⚠️ Important:** Free services sleep after 15 minutes of inactivity. First request after sleep takes ~30 seconds to wake up.

## Need Help?

For Laravel-specific deployment help, check:
- Laravel Deployment Documentation: https://laravel.com/docs/deployment
- Laravel Vapor: https://vapor.laravel.com
- DigitalOcean Laravel Guide: https://www.digitalocean.com/community/tags/laravel
