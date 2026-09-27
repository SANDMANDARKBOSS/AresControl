<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create("plans", function (Blueprint $table) {
        $table->id();
        $table->string("name", 50);
        $table->integer("min_capacity");
        $table->integer("max_capacity")->nullable();
        $table->decimal("price", 6, 2);
        $table->integer("validity_days");
        $table->timestamps();
    }); }
    public function down(): void { Schema::dropIfExists("plans"); }
};