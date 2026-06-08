<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offerts', function (Blueprint $table) {
            // Marks a top-level offer as a "Gross" parent: it holds the shared
            // header and has no positions of its own — only its child offers do.
            if (!Schema::hasColumn('offerts', 'is_gross')) {
                $table->boolean('is_gross')->default(false)->after('parent_id');
            }
            // Per-child part-object (e.g. "Haus A"), shown under the inherited Objekt.
            if (!Schema::hasColumn('offerts', 'teil_objekt')) {
                $table->string('teil_objekt')->nullable()->after('object');
            }
        });
    }

    public function down(): void
    {
        Schema::table('offerts', function (Blueprint $table) {
            if (Schema::hasColumn('offerts', 'is_gross')) {
                $table->dropColumn('is_gross');
            }
            if (Schema::hasColumn('offerts', 'teil_objekt')) {
                $table->dropColumn('teil_objekt');
            }
        });
    }
};
