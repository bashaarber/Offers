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
        Schema::create('offerts', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('user_sign');
            $table->string('status');
            $table->date('create_date');
            $table->string('validity');
            $table->string('client_sign');
            $table->date('finish_date')->nullable();
            $table->string('object');
            $table->string('city');
            $table->string('service');
            $table->string('payment_conditions');
            $table->double('difficulty');
            $table->double('material');
            $table->string('labor_price');
            // $table->double('labor_cost');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offerts');
    }
};
