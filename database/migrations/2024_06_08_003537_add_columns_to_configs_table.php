<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToconfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configs', function (Blueprint $table) {
            $table->text('whatsapp')->nullable()->after('description');
            $table->text('telegram')->nullable()->after('whatsapp');
            $table->text('phone')->nullable()->after('telegram');
            $table->text('facebook')->nullable()->after('phone');
            $table->float('fee')->default(10)->after('facebook');
            $table->float('discount')->default(0)->after('fee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configs', function (Blueprint $table) {
            $table->dropColumn('whatsapp');
            $table->dropColumn('telegram');
            $table->dropColumn('phone');
            $table->dropColumn('facebook');
            $table->dropColumn('fee');
            $table->dropColumn('discount');
        });
    }
}
