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
        Schema::table('sub_items', function (Blueprint $table) {
            $table->boolean('is_custom')->default(false)->after('external_item_id');
            $table->float('minimum_amount')->nullable()->after('is_custom');
            $table->float('max_amount')->nullable()->after('minimum_amount');
            $table->float('custom_price')->nullable()->after('max_amount');
            $table->float('custom_amount')->nullable()->after('custom_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_items', function (Blueprint $table) {
            $table->dropColumn(['is_custom', 'minimum_amount', 'max_amount', 'custom_price', 'custom_amount']);
        });
    }
};
