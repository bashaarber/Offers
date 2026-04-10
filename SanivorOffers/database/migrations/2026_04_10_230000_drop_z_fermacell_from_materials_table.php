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
        if (! Schema::hasColumn('materials', 'z_fermacell')) {
            return;
        }

        $laborPrice = (float) (DB::table('coefficients')->value('labor_price') ?? 0);

        foreach (DB::table('materials')->cursor() as $row) {
            $newZTotal = (float) $row->z_schlosserei + (float) $row->z_pe + (float) $row->z_montage;
            $oldZTotal = (float) ($row->z_total ?? 0);
            $oldZeit = (float) ($row->zeit_cost ?? 0);
            $newZeit = $laborPrice > 0
                ? $newZTotal * $laborPrice
                : ($oldZTotal > 0 ? $oldZeit * ($newZTotal / $oldZTotal) : 0);
            $oldTotal = (float) ($row->total ?? 0);
            $newTotal = $oldTotal - $oldZeit + $newZeit;

            DB::table('materials')->where('id', $row->id)->update([
                'z_total' => $newZTotal,
                'zeit_cost' => $newZeit,
                'total' => $newTotal,
            ]);
        }

        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn('z_fermacell');
        });
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            if (! Schema::hasColumn('materials', 'z_fermacell')) {
                $table->string('z_fermacell')->default('0');
            }
        });
    }
};
