<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('element_position', 'is_optional')) {
            return;
        }

        Schema::table('element_position', function (Blueprint $table) {
            $table->boolean('is_optional')->default(false)->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('element_position', 'is_optional')) {
            return;
        }

        Schema::table('element_position', function (Blueprint $table) {
            $table->dropColumn('is_optional');
        });
    }
};
