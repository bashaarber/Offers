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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->enum('blocktype',['Vorwand-Raumhoch','Vorwand-Raumhoch und Teilhoch','Vorwand-Teilhoch',
            'Freistehend-Raumhoch','Vorwand-Freistehend','Freistehend-Teilhoch','Vorwand DeBO-System','Trennwand DeBO-System'])->nullable();
            $table->string('b')->nullable();
            $table->string('h')->nullable();
            $table->string('t')->nullable();
            $table->double('price_brutto');
            $table->double('price_discount');
            $table->double('discount');
            $table->double('costo');
            $table->double('profit');
            $table->double('total');
            $table->integer('position_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
