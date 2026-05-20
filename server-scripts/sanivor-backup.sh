#!/bin/bash
# Daily prod DB backup.
# Install on the server: copy to /usr/local/bin/sanivor-backup.sh, chmod +x.
# Triggered by Windows Task Scheduler (see Register-BackupTask.ps1).

set -euo pipefail

BACKUP_DIR="/mnt/c/Backups/sanivor"
RETENTION_DAYS=14
DB_CONTAINER="app-db-1"
DB_USER="sanivor"
DB_NAME="sanivoroffers"

mkdir -p "$BACKUP_DIR"
TS=$(date +%Y-%m-%d_%H-%M)
OUT="$BACKUP_DIR/sanivoroffers_${TS}.sql.gz"
LOG="$BACKUP_DIR/backup.log"

log() { echo "$(date +'%Y-%m-%d %H:%M:%S')  $*" | tee -a "$LOG"; }

log "=== Starting backup ==="

if ! docker ps --format '{{.Names}}' | grep -q "^${DB_CONTAINER}$"; then
    log "ERROR: container ${DB_CONTAINER} not running - aborting"
    exit 1
fi

if docker exec "$DB_CONTAINER" pg_dump -U "$DB_USER" -d "$DB_NAME" \
    --clean --if-exists --no-owner --no-acl 2>>"$LOG" | gzip > "$OUT"; then
    SIZE=$(du -h "$OUT" | cut -f1)
    log "OK: $OUT (size: $SIZE)"
else
    log "ERROR: pg_dump failed - removing partial file"
    rm -f "$OUT"
    exit 1
fi

# Retention: delete backups older than $RETENTION_DAYS
DELETED=$(find "$BACKUP_DIR" -maxdepth 1 -name 'sanivoroffers_*.sql.gz' -mtime +${RETENTION_DAYS} -print -delete | wc -l)
log "Rotated: deleted ${DELETED} backup(s) older than ${RETENTION_DAYS} days"

log "=== Done ==="
