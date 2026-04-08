<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('point_redeem_value', 15, 2)->default(1);
            $table->decimal('point_earn_spend', 15, 2)->default(10000);
            $table->decimal('default_max_redeem_percentage', 5, 2)->default(10);
            $table->timestamps();
        });

        DB::table('system_settings')->insert([
            'point_redeem_value' => 1,
            'point_earn_spend' => 10000,
            'default_max_redeem_percentage' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
