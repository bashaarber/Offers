<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offerts', function (Blueprint $table) {
            if (!Schema::hasColumn('offerts', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->nullable()->after('id');
                $table->index('parent_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('offerts', function (Blueprint $table) {
            $table->dropIndex(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};
