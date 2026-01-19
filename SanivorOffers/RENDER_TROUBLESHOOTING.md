# 🔧 Render.com 500 Error Troubleshooting Guide

## Common Causes of 500 Errors

### 1. Missing or Invalid APP_KEY ⚠️ MOST COMMON

**Check:**
- Go to Render Dashboard → Your Service → Environment
- Verify `APP_KEY` is set correctly
- Should look like: `base64:2tn1BSN/+B/kckbq0epni+6esMsAD/5PJEoe1eSo3Yc=`

**Fix:**
```bash
# Generate new key locally
php artisan key:generate --show

# Update in Render:
# Dashboard → Your Service → Environment → Edit APP_KEY
```

---

### 2. Database Connection Issues

**Check Logs:**
- Render Dashboard → Your Service → Logs
- Look for database connection errors

**Common Errors:**
- `SQLSTATE[HY000] [2002] Connection refused`
- `could not connect to server`

**Fix:**
1. Verify database is created and running
2. Check environment variables:
   - `DB_CONNECTION=pgsql`
   - `DB_HOST` (use Internal Database URL if both on Render)
   - `DB_PORT=5432`
   - `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

**Use Internal Database URL:**
- If web service and database are both on Render
- Use the **Internal Database URL** from database dashboard
- Format: `postgresql://user:password@host:port/database`

---

### 3. Storage Permissions

**Check:**
- Laravel needs write access to `storage/` and `bootstrap/cache/`

**Fix:**
The Dockerfile should handle this, but if not:

Add to Dockerfile:
```dockerfile
RUN chmod -R 775 /app/storage /app/bootstrap/cache
RUN mkdir -p /app/storage/framework/{sessions,views,cache}
RUN mkdir -p /app/storage/logs
```

---

### 4. Missing Environment Variables

**Required Variables:**
```
APP_NAME=Sanivor Offers
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_KEY_HERE
APP_URL=https://your-app.onrender.com

LOG_CHANNEL=stderr
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_PORT=5432
DB_DATABASE=your-database
DB_USERNAME=your-username
DB_PASSWORD=your-password
```

---

### 5. Cache Issues

**Fix:**
Add to Dockerfile CMD or run manually:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Step-by-Step Debugging

### Step 1: Check Render Logs

1. Go to Render Dashboard
2. Click your service
3. Click **"Logs"** tab
4. Look for error messages
5. Copy the full error message

### Step 2: Enable Debug Mode Temporarily

**⚠️ Only for debugging - disable after!**

In Render Environment Variables:
```
APP_DEBUG=true
LOG_LEVEL=debug
```

This will show detailed error messages.

### Step 3: Check Database Connection

In Render Logs, look for:
- Database connection errors
- Migration errors
- Seeding errors

### Step 4: Verify All Environment Variables

Make sure ALL required variables are set:
- [ ] APP_KEY
- [ ] APP_URL
- [ ] Database credentials (all 5: HOST, PORT, DATABASE, USERNAME, PASSWORD)

---

## Quick Fixes

### Fix 1: Regenerate APP_KEY

```bash
# Locally
php artisan key:generate --show

# Copy output and update in Render Dashboard
```

### Fix 2: Clear All Caches

Add to Dockerfile CMD:
```dockerfile
CMD php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear && php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
```

### Fix 3: Check Database Connection String

If using Render's PostgreSQL, use the **Internal Database URL**:

```
DB_CONNECTION=pgsql
DATABASE_URL=postgresql://user:password@host:port/database
```

Or parse it into individual variables:
```
DB_HOST=hostname
DB_PORT=5432
DB_DATABASE=database_name
DB_USERNAME=username
DB_PASSWORD=password
```

---

## Updated Dockerfile for Better Error Handling

See the updated `Dockerfile` - it now includes:
- Proper storage permissions
- Cache clearing
- Migration and seeding
- Better error handling

---

## Still Getting 500 Error?

1. **Check Render Logs** - Most important!
2. **Enable APP_DEBUG=true** temporarily
3. **Verify APP_KEY** is set correctly
4. **Check database** is running and accessible
5. **Verify all environment variables** are set

---

## Common Error Messages & Solutions

| Error | Solution |
|-------|----------|
| `No application encryption key` | Set APP_KEY in environment variables |
| `SQLSTATE[HY000] [2002]` | Check database connection settings |
| `Permission denied` | Fix storage permissions in Dockerfile |
| `Class not found` | Run `composer install` in build |
| `Route not found` | Run `php artisan route:cache` |

---

## Need More Help?

Share the error message from Render logs and I'll help you fix it!
