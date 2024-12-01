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
            $table->unsignedBigInteger('client_store_id')->nullable()->after('id');
            $table->foreign('client_store_id')->references('id')->on('client_stores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_items', function (Blueprint $table) {
            $table->dropForeign(['client_store_id']);
            $table->dropColumn('client_store_id');
        });
    }
};
