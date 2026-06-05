<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Positions are shared by the offert editor and the new sub-offert editor.
 * A position belongs to an Offert (via the offert_position pivot) OR to a
 * SubOffert (via this nullable column). Exactly one parent is ever set.
 */
return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('positions', 'sub_offert_id')) {
            return;
        }

        Schema::table('positions', function (Blueprint $table) {
            $table->foreignId('sub_offert_id')
                ->nullable()
                ->after('id')
                ->constrained('sub_offerts')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            if (Schema::hasColumn('positions', 'sub_offert_id')) {
                $table->dropConstrainedForeignId('sub_offert_id');
            }
        });
    }
};
