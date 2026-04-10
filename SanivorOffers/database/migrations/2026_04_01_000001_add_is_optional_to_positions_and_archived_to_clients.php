<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('positions', 'is_optional')) {
            Schema::table('positions', function (Blueprint $table) {
                $table->boolean('is_optional')->default(false)->after('position_number');
            });
        }

        if (! Schema::hasColumn('clients', 'archived')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->boolean('archived')->default(false)->after('address');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('positions', 'is_optional')) {
            Schema::table('positions', function (Blueprint $table) {
                $table->dropColumn('is_optional');
            });
        }

        if (Schema::hasColumn('clients', 'archived')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->dropColumn('archived');
            });
        }
    }
};
