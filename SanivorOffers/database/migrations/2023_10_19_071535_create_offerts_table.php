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
        Schema::create('offerts', function (Blueprint $table) {
            $table->id();
            $table->enum('type',['client','company'])->default('client');
            $table->string('user_sign');
            $table->enum('status',['new','finished'])->default('new');
            $table->string('validity');
            $table->string('client_sign');
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
            // $table->foreignId('coefficient_id')->constrained()->cascadeOnDelete();
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
