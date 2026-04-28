# Render New Position Verification Checklist

Use this checklist after every deployment that touches positions.

## 1) Production Readiness Commands

Run these in the Render shell for the web service:

```bash
php artisan optimize:clear
php artisan migrate:status --no-ansi
php artisan route:list --name=position.create-empty --no-ansi
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Expected:
- `migrate:status` shows all position-related migrations as `Ran`
- `route:list` includes `POST position/create-empty`

## 2) Schema Parity Checks (Postgres)

Run on the production database:

```sql
SELECT column_name, is_nullable, data_type
FROM information_schema.columns
WHERE table_name = 'positions'
ORDER BY ordinal_position;

SELECT conname, pg_get_constraintdef(c.oid)
FROM pg_constraint c
JOIN pg_class t ON c.conrelid = t.oid
WHERE t.relname = 'offert_position';
```

Expected:
- `positions` contains required numeric fields and `position_number`
- `offert_position` has FK constraints to `offerts` and `positions`

## 3) Endpoint Health Check

With an authenticated session in the browser:

1. Open `/position/create/0?offert_id=<valid_offert_id>`
2. Click `+ New Position` once
3. Verify request:
   - `POST /position/create-empty` returns `200`
   - JSON contains `success`, `position_id`, `position_number`, `edit_url`, `request_id`

If it fails:
- Capture `request_id` from response JSON
- Search Render logs for `position.create-empty.*` events using that `request_id`

## 4) Regression Smoke Matrix

Run all four:
- Single click creates one new position
- 3 rapid clicks create 3 sequential positions
- Open two tabs and create concurrently
- New position opens edit page without 500

## 5) Rollback Trigger

Rollback immediately if any condition is true:
- `POST /position/create-empty` returns persistent `500`
- New position is created but edit redirect crashes
- Position numbering duplicates for the same offer
