<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('billing_name')->nullable()->after('voucher_number');
            $table->string('billing_id_card')->nullable()->after('billing_name');
            $table->string('billing_address')->nullable()->after('billing_id_card');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['billing_name', 'billing_id_card', 'billing_address']);
        });
    }
};
