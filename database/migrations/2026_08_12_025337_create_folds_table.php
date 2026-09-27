<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create("folds", function (Blueprint $table) {
        $table->id();
        $table->foreignId("measurement_id")->constrained("measurements");
        $table->string("body_zone", 30);
        $table->decimal("value", 5, 2);
        $table->timestamps();
    }); }
    public function down(): void { Schema::dropIfExists("folds"); }
};