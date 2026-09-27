<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void { 
        Schema::create("nutritional_plans", function (Blueprint $table) {
            $table->id();
            $table->foreignId("client_id")->constrained("clients");
            $table->foreignId("user_id")->constrained("users");
            $table->date("start_date");
            $table->date("end_date");
            $table->text("note")->nullable();
            $table->text("additional")->nullable();
            $table->timestamps();
        }); 
    }
    public function down(): void { 
        Schema::dropIfExists("nutritional_plans"); 
    }
};