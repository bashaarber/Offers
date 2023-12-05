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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('unit');
            $table->double('price_in');
            $table->double('price_out');
            $table->double('z_schlosserei');    
            $table->string('z_pe');
            $table->string('z_montage');
            $table->string('z_fermacell');
            $table->double('z_total');
            $table->double('zeit_cost');
            $table->double('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
