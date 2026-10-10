<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Price of the plan at the moment the order was placed (toman). Later price changes do not affect it.
            $table->string('plan_price_type', 20)->nullable()->after('plan_id');
            $table->unsignedBigInteger('plan_setup_fee')->nullable()->after('plan_price_type');
            $table->unsignedBigInteger('plan_recurring_fee')->nullable()->after('plan_setup_fee');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['plan_price_type', 'plan_setup_fee', 'plan_recurring_fee']);
        });
    }
};
