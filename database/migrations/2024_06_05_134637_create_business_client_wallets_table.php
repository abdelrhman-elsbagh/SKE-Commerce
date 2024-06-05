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
        Schema::create('business_client_wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_client_id');
            $table->decimal('balance', 15, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('business_client_id')->references('id')->on('business_clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_client_wallets');
    }
};
