<?php

use Illuminate\Database\Migrations\Migration;

/**
 * NEUTRALIZED (2026-06-08).
 *
 * This was a one-time destructive reset that ran `TRUNCATE TABLE offerts
 * RESTART IDENTITY CASCADE` to make the next offer become id=1 ("600-H").
 *
 * It has been turned into a no-op so it can NEVER wipe another database.
 * On environments where it already ran it stays recorded; on environments
 * where it is still pending it now does nothing — numbering continues from
 * wherever that database currently is.
 *
 * Do not restore the truncate. If a fresh reset is ever needed, do it
 * deliberately and out-of-band (with a backup first), never via a migration.
 */
return new class extends Migration {
    public function up(): void
    {
        // Intentionally empty — see class docblock.
    }

    public function down(): void
    {
        // No-op.
    }
};
