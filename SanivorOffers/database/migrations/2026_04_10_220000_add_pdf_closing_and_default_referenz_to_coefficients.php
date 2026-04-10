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
            if (! Schema::hasColumn('coefficients', 'default_unsere_referenz')) {
                $table->string('default_unsere_referenz')->nullable();
            }
            if (! Schema::hasColumn('coefficients', 'pdf_external_closing_text')) {
                $table->text('pdf_external_closing_text')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('coefficients', function (Blueprint $table) {
            if (Schema::hasColumn('coefficients', 'pdf_external_closing_text')) {
                $table->dropColumn('pdf_external_closing_text');
            }
            if (Schema::hasColumn('coefficients', 'default_unsere_referenz')) {
                $table->dropColumn('default_unsere_referenz');
            }
        });
    }
};
