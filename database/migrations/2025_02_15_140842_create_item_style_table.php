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
        Schema::create('item_style', function (Blueprint $table) {
            $table->id();
            $table->integer('xl')->default(6);
            $table->integer('lg')->default(5);
            $table->integer('md')->default(4);
            $table->integer('sm')->default(3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_style');
    }
};
