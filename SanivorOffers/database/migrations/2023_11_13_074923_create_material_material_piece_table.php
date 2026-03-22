<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('material_material_piece', function (Blueprint $table) {
            $table->primary(['material_id', 'material_piece_id']);
            $table->foreignId('material_id')->constrained()->cascadeOnDelete();
            $table->foreignId('material_piece_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_material_piece');
    }
};
