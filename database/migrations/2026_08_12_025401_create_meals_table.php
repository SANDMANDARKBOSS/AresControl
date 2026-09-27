<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create("meals", function (Blueprint $table) {
        $table->id();
        $table->foreignId("nutritional_plan_id")->constrained("nutritional_plans");
        $table->string("type", 30);
        $table->timestamps();
    }); }
    public function down(): void { Schema::dropIfExists("meals"); }
};