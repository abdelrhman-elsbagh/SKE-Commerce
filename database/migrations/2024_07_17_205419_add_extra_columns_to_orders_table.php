<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraColumnsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('user_email')->nullable()->after('id');
            $table->string('user_phone')->nullable()->after('user_email');
            $table->string('item_name')->nullable()->after('user_phone');
            $table->string('sub_item_name')->nullable()->after('item_name');
            $table->string('service_id')->nullable()->after('sub_item_name');
            $table->string('user_name')->nullable()->after('service_id');
            $table->decimal('item_price', 8, 2)->nullable()->after('user_id');
            $table->decimal('item_fee', 8, 2)->nullable()->after('item_price');
            $table->string('fee_name')->nullable()->after('item_fee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_email', 'user_phone', 'user_name', 'user_id', 'item_price', 'item_fee', 'fee_name']);
        });
    }
}
