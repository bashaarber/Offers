<?php
/**
 * Clean up orphaned PostgreSQL enum types before running migrations.
 * This prevents "In failed sql transaction" errors with migrate:fresh.
 */
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $types = DB::select("SELECT typname FROM pg_type WHERE typtype = 'e' AND typnamespace = (SELECT oid FROM pg_namespace WHERE nspname = 'public')");
    foreach ($types as $type) {
        DB::unprepared('DROP TYPE IF EXISTS "' . $type->typname . '" CASCADE');
        echo "Dropped enum type: {$type->typname}\n";
    }
    echo "Enum cleanup complete.\n";
} catch (\Exception $e) {
    echo "Enum cleanup skipped: " . $e->getMessage() . "\n";
}
