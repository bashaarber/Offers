# 🐳 Quick Guide: Deploy Laravel with Docker on Render

## ✅ What to Select in Render

**Runtime Dropdown:**
- ✅ **Select "Docker"**

That's it! Now follow the steps below.

---

## Step-by-Step Setup

### 1. Make Sure Files Are Committed

```bash
cd /Users/arberbasha/Laravel/Offers/SanivorOffers
git add Dockerfile .dockerignore
git commit -m "Add Docker configuration for Render"
git push origin main
```

### 2. In Render Dashboard - Create Web Service

**Basic Settings:**
- **Name**: `sanivor-offers`
- **Root Directory**: `SanivorOffers` ⚠️ VERY IMPORTANT!
- **Runtime**: **Docker** ✅
- **Branch**: `main` (or `master`)
- **Plan**: **Free**

**Docker Settings:**
- **Dockerfile Path**: `Dockerfile` (or leave default)
- **Docker Context**: `.` (or leave default)

**Build Command** (Render might auto-detect, but if needed):
```
(leave empty - Docker builds automatically)
```

**Start Command** (Render might auto-detect, but if needed):
```
(leave empty - Dockerfile CMD handles this)
```

**Port**: `8000` (or `$PORT` if Render provides it)

### 3. Environment Variables

Add these (click "Add Environment Variable" for each):

```
APP_NAME=Sanivor Offers
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:2tn1BSN/+B/kckbq0epni+6esMsAD/5PJEoe1eSo3Yc=
APP_URL=https://sanivor-offers.onrender.com

LOG_CHANNEL=stderr
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=(from PostgreSQL dashboard)
DB_PORT=5432
DB_DATABASE=(from PostgreSQL dashboard)
DB_USERNAME=(from PostgreSQL dashboard)
DB_PASSWORD=(from PostgreSQL dashboard)
```

### 4. Create PostgreSQL Database First!

**Before creating web service:**
1. Click **"New +"** → **"PostgreSQL"**
2. Name: `sanivor-db`
3. Plan: **Free**
4. Click **"Create Database"**
5. Copy connection details

### 5. Deploy!

1. Click **"Create Web Service"**
2. Wait 10-15 minutes (first Docker build takes time)
3. Watch build logs
4. Visit your URL when done!

---

## ⚠️ Important Notes

1. **Root Directory MUST be `SanivorOffers`** - This is critical!
2. **Dockerfile must be in `SanivorOffers` folder**
3. **First build takes 10-15 minutes** - be patient!
4. **Free services sleep after 15 min** - first load might be slow

---

## Troubleshooting

### Build Fails
- Check: Is `Dockerfile` in `SanivorOffers` folder?
- Check: Is `Root Directory` set correctly?
- Check build logs for errors

### Port Error
- Make sure port is set to `8000` or `$PORT`

### Database Error
- Verify database credentials
- Make sure database is created first

---

## Files Created

✅ `Dockerfile` - Docker configuration
✅ `.dockerignore` - Excludes unnecessary files
✅ `RENDER_DOCKER_SETUP.md` - Detailed guide

---

## That's It!

Select **"Docker"** in the dropdown and follow the steps above. Good luck! 🚀
