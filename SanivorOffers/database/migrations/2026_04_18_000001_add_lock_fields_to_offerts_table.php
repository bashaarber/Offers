<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    // Prevent Laravel from wrapping this migration in a transaction.
    // On PostgreSQL, a single failure inside a transaction aborts all subsequent
    // statements with 25P02 — so we run each DDL statement independently.
    public bool $withinTransaction = false;

    public function up(): void
    {
        // Defensive: if the migrator still left an aborted transaction open on this
        // connection, roll it back so DDL below can run. Ignore errors — if there's
        // no open transaction, ROLLBACK is a harmless NOTICE.
        $this->safeRun('ROLLBACK');

        // Idempotent ADD COLUMN (PostgreSQL 9.6+). Ignores "column already exists".
        $this->safeRun('ALTER TABLE offerts ADD COLUMN IF NOT EXISTS locked_by bigint NULL');
        $this->safeRun('ALTER TABLE offerts ADD COLUMN IF NOT EXISTS locked_at timestamp NULL');

        // FK: guard via DO block since PostgreSQL has no "ADD CONSTRAINT IF NOT EXISTS".
        $this->safeRun(<<<'SQL'
DO $do$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.table_constraints
        WHERE table_name = 'offerts'
          AND constraint_name = 'offerts_locked_by_foreign'
    ) THEN
        ALTER TABLE offerts
            ADD CONSTRAINT offerts_locked_by_foreign
            FOREIGN KEY (locked_by) REFERENCES users(id) ON DELETE SET NULL;
    END IF;
END
$do$
SQL
        );
    }

    public function down(): void
    {
        $this->safeRun('ROLLBACK');
        $this->safeRun(<<<'SQL'
DO $do$
BEGIN
    IF EXISTS (
        SELECT 1 FROM information_schema.table_constraints
        WHERE table_name = 'offerts'
          AND constraint_name = 'offerts_locked_by_foreign'
    ) THEN
        ALTER TABLE offerts DROP CONSTRAINT offerts_locked_by_foreign;
    END IF;
END
$do$
SQL
        );
        $this->safeRun('ALTER TABLE offerts DROP COLUMN IF EXISTS locked_at');
        $this->safeRun('ALTER TABLE offerts DROP COLUMN IF EXISTS locked_by');
    }

    private function safeRun(string $sql): void
    {
        try {
            // DB::unprepared bypasses PDO prepare() — required for multi-statement DO blocks,
            // and avoids any implicit prepared-statement transaction wrap.
            DB::unprepared($sql);
        } catch (\Throwable $e) {
            Log::warning('lock-migration statement failed (continuing)', [
                'sql' => $sql,
                'error' => $e->getMessage(),
            ]);
        }
    }
};
