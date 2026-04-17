<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public bool $withinTransaction = false;

    public function up(): void
    {
        // ADD COLUMN IF NOT EXISTS is idempotent — safe to re-run if a previous attempt partially succeeded
        DB::statement('ALTER TABLE offerts ADD COLUMN IF NOT EXISTS locked_by bigint NULL');
        DB::statement('ALTER TABLE offerts ADD COLUMN IF NOT EXISTS locked_at timestamp NULL');

        // Add FK only if it does not already exist
        DB::statement("
            DO \$do\$
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
            \$do\$
        ");
    }

    public function down(): void
    {
        DB::statement("
            DO \$do\$
            BEGIN
                IF EXISTS (
                    SELECT 1 FROM information_schema.table_constraints
                    WHERE table_name = 'offerts'
                      AND constraint_name = 'offerts_locked_by_foreign'
                ) THEN
                    ALTER TABLE offerts DROP CONSTRAINT offerts_locked_by_foreign;
                END IF;
            END
            \$do\$
        ");
        DB::statement('ALTER TABLE offerts DROP COLUMN IF EXISTS locked_at');
        DB::statement('ALTER TABLE offerts DROP COLUMN IF EXISTS locked_by');
    }
};
