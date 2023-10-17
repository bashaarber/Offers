<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('element_group_element', function (Blueprint $table) {
            $table->primary(['element_id', 'group_element_id']);
            $table->foreignId('element_id')->constrained()->cascadeOnDelete();
            $table->foreignId('group_element_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('element_group_element');
    }
};
