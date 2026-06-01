<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('element_material', 'sort_order')) {
            Schema::table('element_material', function (Blueprint $table) {
                $table->unsignedInteger('sort_order')->default(0);
            });
        }

        // Backfill a deterministic order for existing rows (current implicit
        // order, by material_id) so nothing visually reshuffles after deploy.
        $elementIds = DB::table('element_material')->distinct()->pluck('element_id');
        foreach ($elementIds as $elementId) {
            $materialIds = DB::table('element_material')
                ->where('element_id', $elementId)
                ->orderBy('material_id')
                ->pluck('material_id');

            foreach ($materialIds as $index => $materialId) {
                DB::table('element_material')
                    ->where('element_id', $elementId)
                    ->where('material_id', $materialId)
                    ->update(['sort_order' => $index]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('element_material', 'sort_order')) {
            Schema::table('element_material', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
    }
};
