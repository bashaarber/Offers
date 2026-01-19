# 🔧 Fix 500 Error on Render.com

## Quick Fixes (Try These First)

### Fix 1: Check Render Logs ⚠️ MOST IMPORTANT

1. Go to Render Dashboard
2. Click your service
3. Click **"Logs"** tab
4. **Copy the error message** - this tells us exactly what's wrong!

Common errors you might see:
- `No application encryption key` → APP_KEY missing
- `SQLSTATE[HY000]` → Database connection issue
- `Permission denied` → Storage permissions
- `Class not found` → Missing dependencies

---

### Fix 2: Verify APP_KEY is Set

1. Render Dashboard → Your Service → **Environment**
2. Check if `APP_KEY` exists
3. Value should be: `base64:2tn1BSN/+B/kckbq0epni+6esMsAD/5PJEoe1eSo3Yc=`

**If missing or wrong:**
- Add/Update: `APP_KEY=base64:2tn1BSN/+B/kckbq0epni+6esMsAD/5PJEoe1eSo3Yc=`
- Save and redeploy

---

### Fix 3: Check Database Connection

**Verify these environment variables are set:**

```
DB_CONNECTION=pgsql
DB_HOST=(your database host)
DB_PORT=5432
DB_DATABASE=(your database name)
DB_USERNAME=(your database username)
DB_PASSWORD=(your database password)
```

**Important:** If both web service and database are on Render:
- Use the **Internal Database URL** from database dashboard
- Or use the internal hostname (not external)

---

### Fix 4: Enable Debug Mode Temporarily

**⚠️ Only for debugging - disable after fixing!**

Add to Environment Variables:
```
APP_DEBUG=true
LOG_LEVEL=debug
```

This will show detailed error messages instead of generic 500 error.

**After fixing, change back to:**
```
APP_DEBUG=false
LOG_LEVEL=error
```

---

### Fix 5: Update Dockerfile

I've updated your Dockerfile with better error handling. Make sure it's committed:

```bash
cd /Users/arberbasha/Laravel/Offers/SanivorOffers
git add Dockerfile docker-entrypoint.sh
git commit -m "Fix Dockerfile for Render deployment"
git push origin main
```

Render will automatically redeploy.

---

## Step-by-Step Debugging

### Step 1: Get the Actual Error

1. Enable debug mode (see Fix 4 above)
2. Visit your site
3. You'll see the actual error message
4. Copy it and we can fix it

### Step 2: Check Common Issues

**Checklist:**
- [ ] APP_KEY is set correctly
- [ ] All database variables are set
- [ ] Database is running (check database dashboard)
- [ ] APP_URL matches your Render URL
- [ ] Storage permissions are correct

### Step 3: Check Render Logs

The logs will show:
- Build errors
- Runtime errors
- Database connection errors
- Missing file errors

---

## Updated Files

✅ `Dockerfile` - Updated with better error handling
✅ `docker-entrypoint.sh` - Startup script with error handling
✅ `RENDER_TROUBLESHOOTING.md` - Complete troubleshooting guide

---

## Most Common 500 Errors & Solutions

### Error: "No application encryption key"

**Solution:**
```
Set APP_KEY in Render environment variables:
APP_KEY=base64:2tn1BSN/+B/kckbq0epni+6esMsAD/5PJEoe1eSo3Yc=
```

### Error: "SQLSTATE[HY000] [2002] Connection refused"

**Solution:**
- Check database is running
- Verify DB_HOST is correct
- Use Internal Database URL if both on Render
- Check DB_PORT is 5432

### Error: "Permission denied" or "The stream or file could not be opened"

**Solution:**
- Storage permissions issue
- Updated Dockerfile should fix this
- Redeploy after updating Dockerfile

### Error: "Class 'X' not found"

**Solution:**
- Missing Composer dependency
- Run `composer install` in build
- Check composer.json is correct

---

## Quick Actions

1. **Check logs** → Render Dashboard → Logs
2. **Enable debug** → Set `APP_DEBUG=true` temporarily
3. **Verify APP_KEY** → Must be set correctly
4. **Check database** → Must be running and accessible
5. **Redeploy** → After fixing issues, push to trigger redeploy

---

## Need More Help?

**Share the error message from Render logs** and I'll help you fix it!

Common places to find errors:
- Render Dashboard → Your Service → Logs
- Browser console (F12 → Console tab)
- Network tab (F12 → Network → Check failed requests)
