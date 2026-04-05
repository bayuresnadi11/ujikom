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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name')->default('My Admin App');
            $table->string('app_logo')->nullable();
            
            // WhatsApp Configuration (Japati)
            $table->string('japati_api_token')->nullable();
            $table->string('japati_gateway_number')->nullable();
            
            // Midtrans Configuration - BOOLEAN (1/0)
            $table->string('midtrans_server_key')->nullable();
            $table->string('midtrans_client_key')->nullable();
            $table->boolean('midtrans_is_production')->default(false);
            // true = 1 = production mode
            // false = 0 = sandbox mode
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};