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
        Schema::table('items', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('id'); // Adjust 'after' as needed
        });

        // Add 'order' column to 'sub_items' table
        Schema::table('sub_items', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('id'); // Adjust 'after' as needed
        });

        // Add 'order' column to 'categories' table
        Schema::table('categories', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('id'); // Adjust 'after' as needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('order');
        });

        // Remove 'order' column from 'sub_items' table
        Schema::table('sub_items', function (Blueprint $table) {
            $table->dropColumn('order');
        });

        // Remove 'order' column from 'categories' table
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
