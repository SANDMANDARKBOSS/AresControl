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
        Schema::create('cash_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // Recepcionista/Admin
            $table->dateTime('opening_time');
            $table->dateTime('closing_time')->nullable();
            $table->decimal('initial_base_cash', 8, 2)->default(0);
            $table->decimal('total_system_cash', 8, 2)->default(0);
            $table->decimal('total_declared_cash', 8, 2)->default(0);
            $table->decimal('cash_difference', 8, 2)->default(0);
            $table->decimal('total_system_transfers', 8, 2)->default(0);
            $table->string('status')->default('abierto'); // abierto, cerrado
            $table->text('observations')->nullable();
            $table->json('cash_breakdown')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_shifts');
    }
};
