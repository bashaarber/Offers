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
        Schema::table('offerts', function (Blueprint $table) {
            if (!Schema::hasColumn('offerts', 'client_address')) {
                $table->text('client_address')->nullable()->after('client_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offerts', function (Blueprint $table) {
            if (Schema::hasColumn('offerts', 'client_address')) {
                $table->dropColumn('client_address');
            }
        });
    }
};
