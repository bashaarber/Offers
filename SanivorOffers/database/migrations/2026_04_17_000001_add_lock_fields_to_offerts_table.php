<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('offerts', 'locked_by')) {
            Schema::table('offerts', function (Blueprint $table) {
                $table->unsignedBigInteger('locked_by')->nullable()->after('user_id');
            });
        }

        if (! Schema::hasColumn('offerts', 'locked_at')) {
            Schema::table('offerts', function (Blueprint $table) {
                $table->timestamp('locked_at')->nullable()->after('locked_by');
            });
        }

        // Add FK only if it doesn't already exist
        $constraints = DB::select(
            "SELECT constraint_name FROM information_schema.table_constraints
             WHERE table_name = 'offerts' AND constraint_name = 'offerts_locked_by_foreign'"
        );
        if (empty($constraints)) {
            Schema::table('offerts', function (Blueprint $table) {
                $table->foreign('locked_by')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::table('offerts', function (Blueprint $table) {
            if (Schema::hasColumn('offerts', 'locked_by')) {
                $table->dropForeign(['locked_by']);
            }
            $table->dropColumn(array_filter(['locked_by', 'locked_at'], fn($col) => Schema::hasColumn('offerts', $col)));
        });
    }
};
