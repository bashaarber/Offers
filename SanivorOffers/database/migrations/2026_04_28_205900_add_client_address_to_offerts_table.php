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
        if (!Schema::hasColumn('offerts', 'client_address')) {
            Schema::table('offerts', function (Blueprint $table) {
                $table->text('client_address')->nullable()->after('client_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('offerts', 'client_address')) {
            Schema::table('offerts', function (Blueprint $table) {
                $table->dropColumn('client_address');
            });
        }
    }
};
