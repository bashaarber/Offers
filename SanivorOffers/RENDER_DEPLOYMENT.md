# Step-by-Step Guide: Deploy Laravel to Render.com (FREE)

## Prerequisites

1. ✅ Your code is in a GitHub repository
2. ✅ You have a Render.com account (sign up at https://render.com - it's free!)

---

## Step 1: Prepare Your Repository

### 1.1 Generate Application Key Locally

```bash
cd /Users/arberbasha/Laravel/Offers/SanivorOffers
php artisan key:generate --show
```

**Copy this key** - you'll need it in Step 3!

### 1.2 Commit All Files to Git

Make sure all your files are committed:

```bash
git add .
git commit -m "Prepare for Render deployment"
git push origin main  # or master, depending on your branch
```

---

## Step 2: Create PostgreSQL Database on Render

1. **Go to Render Dashboard**: https://dashboard.render.com
2. Click **"New +"** → **"PostgreSQL"**
3. Fill in:
   - **Name**: `sanivor-db` (or any name you prefer)
   - **Database**: `sanivor` (or leave default)
   - **User**: `sanivor_user` (or leave default)
   - **Plan**: **Free** (select this!)
   - **Region**: Choose closest to you
4. Click **"Create Database"**
5. **Wait for database to be created** (takes ~2 minutes)
6. **Copy the connection details** - you'll see:
   - Internal Database URL
   - External Database URL
   - Host, Port, Database, Username, Password

---

## Step 3: Create Web Service on Render

1. **Go to Render Dashboard**
2. Click **"New +"** → **"Web Service"**
3. **Connect your GitHub account** (if not already connected)
4. **Select your repository**: `Offers` (or whatever your repo is named)
5. **Select branch**: `main` (or `master`)

### Configure the Service:

**Basic Settings:**
- **Name**: `sanivor-offers` (or any name)
- **Region**: Same as your database
- **Branch**: `main` (or `master`)
- **Root Directory**: `SanivorOffers` (important! Your Laravel app is in this folder)
- **Runtime**: `PHP`
- **Plan**: **Free**

**Build & Deploy:**
- **Build Command**: 
  ```bash
  composer install --no-dev --optimize-autoloader && php artisan migrate --force && php artisan db:seed --force
  ```
- **Start Command**: 
  ```bash
  php artisan serve --host=0.0.0.0 --port=$PORT
  ```

**Environment Variables** (click "Add Environment Variable" for each):

```
APP_NAME=Sanivor Offers
APP_ENV=production
APP_DEBUG=false
APP_KEY=paste-your-generated-key-here
APP_URL=https://your-app-name.onrender.com

LOG_CHANNEL=stderr
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=paste-from-database-dashboard
DB_PORT=5432
DB_DATABASE=paste-from-database-dashboard
DB_USERNAME=paste-from-database-dashboard
DB_PASSWORD=paste-from-database-dashboard

# Optional: If you want to use SQLite instead (not recommended for production)
# DB_CONNECTION=sqlite
# DB_DATABASE=/opt/render/project/src/database/database.sqlite
```

**Important Notes:**
- Replace `paste-your-generated-key-here` with the key from Step 1.1
- Replace all database values with the actual values from your PostgreSQL database
- The `APP_URL` will be `https://your-service-name.onrender.com` (Render will show you the URL after creation)

6. Click **"Create Web Service"**

---

## Step 4: Wait for Deployment

1. Render will start building your application
2. This takes **5-10 minutes** the first time
3. You can watch the build logs in real-time
4. Once deployed, you'll see **"Live"** status

---

## Step 5: Access Your Application

1. Your app will be available at: `https://your-service-name.onrender.com`
2. **First load might be slow** (30 seconds) if the service was sleeping
3. Subsequent loads will be faster

---

## Step 6: Set Up Custom Domain (Optional)

1. Go to your service settings
2. Click **"Custom Domains"**
3. Add your domain
4. Follow DNS instructions

---

## Troubleshooting

### Issue: Build Fails

**Check:**
- Is your `Root Directory` set to `SanivorOffers`?
- Are all dependencies in `composer.json`?
- Check build logs for specific errors

**Common Fixes:**
```bash
# Make sure these files exist:
- composer.json
- package.json
- .env.example (for reference)
```

### Issue: Database Connection Error

**Check:**
- Database credentials are correct
- Database is in the same region as web service
- Use **Internal Database URL** if both services are on Render

### Issue: 500 Error After Deployment

**Check:**
- `APP_KEY` is set correctly
- `APP_DEBUG=false` (don't set to true in production)
- Check logs: Render Dashboard → Your Service → Logs

### Issue: Service Sleeps Too Much

**Free tier limitation:** Services sleep after 15 minutes of inactivity.

**Solutions:**
1. Use a service like **UptimeRobot** (free) to ping your site every 10 minutes
2. Upgrade to paid plan ($7/month) for always-on service

---

## Post-Deployment Checklist

- [ ] Application loads successfully
- [ ] Database migrations ran (`php artisan migrate`)
- [ ] Database seeded (`php artisan db:seed`)
- [ ] Can log in with admin credentials
- [ ] All routes work correctly
- [ ] Static assets load (CSS/JS)
- [ ] File uploads work (if applicable)

---

## Updating Your Application

**Every time you push to GitHub, Render will automatically redeploy!**

1. Make changes locally
2. Commit: `git add . && git commit -m "Your message"`
3. Push: `git push origin main`
4. Render automatically detects the push and redeploys
5. Wait 5-10 minutes for new deployment

---

## Environment Variables Reference

Here's what each variable does:

| Variable | Purpose | Example |
|----------|---------|---------|
| `APP_NAME` | Application name | `Sanivor Offers` |
| `APP_ENV` | Environment | `production` |
| `APP_DEBUG` | Debug mode | `false` (never true in production!) |
| `APP_KEY` | Encryption key | `base64:...` (from `php artisan key:generate`) |
| `APP_URL` | Your app URL | `https://your-app.onrender.com` |
| `DB_CONNECTION` | Database type | `pgsql` |
| `DB_HOST` | Database host | From Render dashboard |
| `DB_PORT` | Database port | `5432` |
| `DB_DATABASE` | Database name | From Render dashboard |
| `DB_USERNAME` | Database user | From Render dashboard |
| `DB_PASSWORD` | Database password | From Render dashboard |

---

## Free Tier Limitations

**What you get:**
- ✅ 750 hours/month of compute time
- ✅ Free PostgreSQL database (1 GB)
- ✅ Automatic SSL certificates
- ✅ Custom domains
- ✅ Automatic deployments

**Limitations:**
- ⚠️ Service sleeps after 15 minutes of inactivity
- ⚠️ First request after sleep takes ~30 seconds
- ⚠️ Free database expires after 30 days (you'll get a warning)
- ⚠️ No persistent file storage (use S3 or similar for uploads)

---

## Need Help?

- Render Documentation: https://render.com/docs
- Render Community: https://community.render.com
- Laravel Deployment: https://laravel.com/docs/deployment

---

## Quick Commands Reference

**Generate APP_KEY:**
```bash
php artisan key:generate --show
```

**Check if everything is ready:**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Test database connection:**
```bash
php artisan migrate:status
```

---

## 🎉 You're Ready!

Follow these steps and your Laravel app will be live on Render.com for FREE!

Good luck! 🚀
