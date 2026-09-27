<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create("payments", function (Blueprint $table) {
        $table->id();
        $table->foreignId("membership_id")->constrained("memberships");
        $table->foreignId("payment_method_id")->constrained("payment_methods");
        $table->date("date");
        $table->decimal("amount", 6, 2);
        $table->decimal("pending_balance", 6, 2)->default(0);
        $table->string("voucher_number", 30)->nullable();
        $table->timestamps();
    }); }
    public function down(): void { Schema::dropIfExists("payments"); }
};