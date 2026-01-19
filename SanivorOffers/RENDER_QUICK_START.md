# 🚀 Quick Start: Deploy to Render.com in 5 Minutes

## Prerequisites Checklist

- [ ] Code is pushed to GitHub
- [ ] Render.com account created (free at https://render.com)

---

## Step 1: Generate APP_KEY (Do This First!)

```bash
cd /Users/arberbasha/Laravel/Offers/SanivorOffers
php artisan key:generate --show
```

**Copy the output** - you'll need it in Step 3!

---

## Step 2: Create Database on Render

1. Go to https://dashboard.render.com
2. Click **"New +"** → **"PostgreSQL"**
3. Settings:
   - Name: `sanivor-db`
   - Plan: **Free**
   - Region: Choose closest
4. Click **"Create Database"**
5. **Copy these values** (you'll see them on the database page):
   - Host
   - Port (usually 5432)
   - Database name
   - Username
   - Password

---

## Step 3: Create Web Service

1. Click **"New +"** → **"Web Service"**
2. Connect GitHub → Select your `Offers` repository
3. Configure:

### Basic Settings:
- **Name**: `sanivor-offers`
- **Root Directory**: `SanivorOffers` ⚠️ IMPORTANT!
- **Region**: Same as database
- **Branch**: `main` (or `master`)
- **Runtime**: `PHP`
- **Plan**: **Free**

### Build & Start Commands:
**Build Command:**
```bash
composer install --no-dev --optimize-autoloader && php artisan migrate --force && php artisan db:seed --force
```

**Start Command:**
```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

### Environment Variables (Add each one):

```
APP_NAME=Sanivor Offers
APP_ENV=production
APP_DEBUG=false
APP_KEY=paste-your-key-from-step-1
APP_URL=https://sanivor-offers.onrender.com

LOG_CHANNEL=stderr
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=paste-from-database-dashboard
DB_PORT=5432
DB_DATABASE=paste-from-database-dashboard
DB_USERNAME=paste-from-database-dashboard
DB_PASSWORD=paste-from-database-dashboard
```

4. Click **"Create Web Service"**

---

## Step 4: Wait & Test

1. Wait 5-10 minutes for first deployment
2. Visit your URL: `https://sanivor-offers.onrender.com`
3. First load might take 30 seconds (service waking up)

---

## ✅ Done!

Your app is now live! 🎉

**Remember:**
- Free services sleep after 15 minutes
- Use UptimeRobot (free) to keep it awake
- Or upgrade to $7/month for always-on

---

## Need More Details?

See `RENDER_DEPLOYMENT.md` for complete guide with troubleshooting!
