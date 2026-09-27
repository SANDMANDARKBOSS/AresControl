<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_passes', function (Blueprint $table) {
            $table->id();
            $table->string('guest_name')->nullable();
            $table->decimal('amount', 6, 2);
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_passes');
    }
};
