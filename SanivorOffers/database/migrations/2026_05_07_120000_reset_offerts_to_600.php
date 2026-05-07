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

        $driver = DB::connection()->getDriverName();

        switch ($driver) {
            case 'pgsql':
                DB::statement('TRUNCATE TABLE offerts RESTART IDENTITY CASCADE');
                break;

            case 'mysql':
            case 'mariadb':
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                DB::statement('TRUNCATE TABLE offert_position');
                DB::statement('TRUNCATE TABLE offerts');
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
                break;

            case 'sqlite':
                DB::table('offert_position')->delete();
                DB::table('offerts')->delete();
                if (Schema::hasTable('sqlite_sequence')) {
                    DB::table('sqlite_sequence')->where('name', 'offerts')->delete();
                    DB::table('sqlite_sequence')->where('name', 'offert_position')->delete();
                }
                break;

            default:
                throw new \RuntimeException("Unsupported database driver for offert reset: {$driver}");
        }

        Log::warning('reset_offerts_to_600 migration executed', [
            'driver' => $driver,
            'rows_deleted' => $countBefore,
            'max_id_before' => $maxIdBefore,
        ]);
    }

    public function down(): void
    {
        // No-op: this migration is destructive and cannot be reversed.
    }
};
