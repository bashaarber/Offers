<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up(): void
    {
        if (Schema::hasColumn('materials', 'total_arbeit')) {
            return;
        }

        Schema::table('materials', function (Blueprint $table) {
            $table->double('total_arbeit')->nullable()->after('zeit_cost');
        });

        $laborPrice = (float) (DB::table('coefficients')->value('labor_price') ?? 0);

        foreach (DB::table('materials')->cursor() as $row) {
            $zTotal = (float) ($row->z_total ?? 0);
            $arbeit = $laborPrice > 0 ? $zTotal * $laborPrice : (float) ($row->zeit_cost ?? 0);
            DB::table('materials')->where('id', $row->id)->update(['total_arbeit' => $arbeit]);
        }
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            if (Schema::hasColumn('materials', 'total_arbeit')) {
                $table->dropColumn('total_arbeit');
            }
        });
    }
};
