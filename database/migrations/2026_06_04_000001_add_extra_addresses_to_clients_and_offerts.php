<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'address_2')) {
                $table->string('address_2')->nullable()->after('address');
            }
            if (!Schema::hasColumn('clients', 'address_3')) {
                $table->string('address_3')->nullable()->after('address_2');
            }
        });

        Schema::table('offerts', function (Blueprint $table) {
            if (!Schema::hasColumn('offerts', 'client_address_2')) {
                $table->string('client_address_2')->nullable()->after('client_address');
            }
            if (!Schema::hasColumn('offerts', 'client_address_3')) {
                $table->string('client_address_3')->nullable()->after('client_address_2');
            }
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['address_2', 'address_3']);
        });

        Schema::table('offerts', function (Blueprint $table) {
            $table->dropColumn(['client_address_2', 'client_address_3']);
        });
    }
};
