<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create("client_membership", function (Blueprint $table) {
        $table->id();
        $table->foreignId("client_id")->constrained("clients");
        $table->foreignId("membership_id")->constrained("memberships");
        $table->unique(["client_id", "membership_id"]);
        $table->timestamps();
    }); }
    public function down(): void { Schema::dropIfExists("client_membership"); }
};