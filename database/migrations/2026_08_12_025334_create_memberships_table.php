<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create("memberships", function (Blueprint $table) {
        $table->id();
        $table->foreignId("plan_id")->constrained("plans");
        $table->string("status", 20)->default("Activa");
        $table->date("start_date");
        $table->date("end_date");
        $table->timestamps();
    }); }
    public function down(): void { Schema::dropIfExists("memberships"); }
};