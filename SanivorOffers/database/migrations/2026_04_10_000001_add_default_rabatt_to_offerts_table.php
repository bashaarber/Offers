<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offerts', function (Blueprint $table) {
            $table->decimal('default_rabatt', 5, 2)->default(0)->after('labor_price');
        });
    }

    public function down(): void
    {
        Schema::table('offerts', function (Blueprint $table) {
            $table->dropColumn('default_rabatt');
        });
    }
};
