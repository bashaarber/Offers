<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up(): void
    {
        Schema::table('coefficients', function (Blueprint $table) {
            if (!Schema::hasColumn('coefficients', 'default_signature')) {
                $table->string('default_signature')->default('Arber Basha')->after('default_rabatt');
            }
        });
    }

    public function down(): void
    {
        Schema::table('coefficients', function (Blueprint $table) {
            if (Schema::hasColumn('coefficients', 'default_signature')) {
                $table->dropColumn('default_signature');
            }
        });
    }
};

