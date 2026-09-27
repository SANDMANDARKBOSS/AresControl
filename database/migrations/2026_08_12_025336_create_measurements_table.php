<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create("measurements", function (Blueprint $table) {
        $table->id();
        $table->foreignId("client_id")->constrained("clients");
        $table->foreignId("user_id")->constrained("users");
        $table->date("date");
        $table->decimal("weight", 5, 2);
        $table->decimal("fat", 5, 2)->nullable();
        $table->decimal("muscle", 5, 2)->nullable();
        $table->decimal("chest", 5, 2)->nullable();
        $table->decimal("waist", 5, 2)->nullable();
        $table->decimal("hip", 5, 2)->nullable();
        $table->decimal("left_leg", 5, 2)->nullable();
        $table->decimal("right_leg", 5, 2)->nullable();
        $table->decimal("left_calf", 5, 2)->nullable();
        $table->decimal("right_calf", 5, 2)->nullable();
        $table->decimal("back", 5, 2)->nullable();
        $table->decimal("left_bicep", 5, 2)->nullable();
        $table->decimal("right_bicep", 5, 2)->nullable();
        $table->decimal("bmi", 4, 2)->nullable();
        $table->timestamps();
    }); }
    public function down(): void { Schema::dropIfExists("measurements"); }
};