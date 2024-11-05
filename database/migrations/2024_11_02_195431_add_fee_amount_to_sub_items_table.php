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
            $table->float('fee_amount')->nullable()->after('user_id'); // Adjust "after" to position it as needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_items', function (Blueprint $table) {
            $table->dropColumn('fee_amount');
        });
    }
};
