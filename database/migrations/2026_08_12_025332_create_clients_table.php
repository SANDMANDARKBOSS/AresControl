<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create("clients", function (Blueprint $table) {
        $table->id();
        $table->foreignId("user_id")->constrained("users");
        $table->string("name", 100);
        $table->string("last_name", 100);
        $table->string("id_card", 10)->unique();
        $table->string("phone", 15)->nullable();
        $table->date("entry_date");
        $table->timestamps();
    }); }
    public function down(): void { Schema::dropIfExists("clients"); }
};