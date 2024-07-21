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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('currency_id')->nullable()->after('user_id');
            $table->unsignedBigInteger('item_id')->nullable()->after('currency_id');
            $table->unsignedBigInteger('sub_item_id')->nullable()->after('item_id');

            // Assuming you have currency, item, and sub_item tables
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('set null');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('set null');
            $table->foreign('sub_item_id')->references('id')->on('sub_items')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
            $table->dropForeign(['item_id']);
            $table->dropForeign(['sub_item_id']);
            $table->dropColumn(['currency_id', 'item_id', 'sub_item_id']);
        });
    }
};
