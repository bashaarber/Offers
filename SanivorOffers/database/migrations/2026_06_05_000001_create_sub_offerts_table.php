<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sub Offert is a fully standalone module — its own table mirroring `offerts`.
 * It is NOT linked to offerts. `parent_id` is self-referential for internal
 * sub-of-sub nesting (nested children share the root's running number, -S suffix).
 */
return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('sub_offerts')) {
            return;
        }

        Schema::create('sub_offerts', function (Blueprint $table) {
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

            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('client_id')->constrained('clients');

            $table->timestamps();

            $table->decimal('default_rabatt', 8, 2)->default(0);

            $table->foreignId('locked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('locked_at')->nullable();

            $table->text('client_address')->nullable();
            $table->string('client_address_2')->nullable();
            $table->string('client_address_3')->nullable();

            // Self-referential parent for internal sub-of-sub nesting.
            $table->foreignId('parent_id')->nullable()->constrained('sub_offerts')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_offerts');
    }
};
