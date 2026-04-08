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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_id')->nullable()->constrained()->nullOnDelete();
            $table->dateTime('purchase_date');
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->boolean('is_member')->default(false);
            $table->decimal('total_before_discount', 15, 2);
            $table->decimal('total_after_discount', 15, 2);
            $table->unsignedInteger('points_used')->default(0);
            $table->unsignedInteger('points_earned')->default(0);
            $table->decimal('point_redeem_value', 15, 2)->default(1);
            $table->decimal('point_earn_spend', 15, 2)->default(10000);
            $table->decimal('max_redeem_percentage', 5, 2)->default(10);
            $table->decimal('points_discount_amount', 15, 2)->default(0);
            $table->decimal('cash_paid', 15, 2);
            $table->decimal('change_amount', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
