<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * One-time reset: wipe all offerts and restart the id sequence so the next
 * inserted row becomes id=1, which renders as display number "600-H"
 * (Offert::DISPLAY_NUMBER_OFFSET = 599).
 *
 * Laravel records this migration in the `migrations` table after it runs,
 * so subsequent deploys (with RUN_MIGRATIONS=true) will skip it.
 *
 * `down()` is intentionally a no-op — there is no meaningful way to undo
 * a destructive truncate.
 */
return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('offerts')) {
            return;
        }

        $countBefore = (int) DB::table('offerts')->count();
        $maxIdBefore = (int) (DB::table('offerts')->max('id') ?? 0);

        DB::statement('TRUNCATE TABLE offerts RESTART IDENTITY CASCADE');

        Log::warning('reset_offerts_to_600 migration executed', [
            'rows_deleted' => $countBefore,
            'max_id_before' => $maxIdBefore,
        ]);
    }

    public function down(): void
    {
        // No-op: this migration is destructive and cannot be reversed.
    }
};
