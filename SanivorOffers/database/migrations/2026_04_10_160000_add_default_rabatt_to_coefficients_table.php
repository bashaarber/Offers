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
            if (!Schema::hasColumn('coefficients', 'default_rabatt')) {
                $table->decimal('default_rabatt', 5, 2)->default(0)->after('payment_conditions');
            }
        });
    }

    public function down(): void
    {
        Schema::table('coefficients', function (Blueprint $table) {
            if (Schema::hasColumn('coefficients', 'default_rabatt')) {
                $table->dropColumn('default_rabatt');
            }
        });
    }
};

