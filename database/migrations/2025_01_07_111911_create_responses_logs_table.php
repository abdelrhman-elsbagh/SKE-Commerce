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
        Schema::create('responses_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable(); // Type of log (e.g., API request, response, error)
            $table->json('request_data')->nullable(); // Request data
            $table->json('response_data')->nullable(); // Response data
            $table->text('error_message')->nullable(); // Error message, if any
            $table->timestamps(); // Created at, Updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responses_logs');
    }
};
