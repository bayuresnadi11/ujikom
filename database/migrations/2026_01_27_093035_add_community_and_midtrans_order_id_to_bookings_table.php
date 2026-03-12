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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('community')->nullable()->after('pay_by'); // Posisi setelah pay_by
            $table->string('midtrans_order_id', 191)->nullable()->after('ticket_code'); // Posisi setelah ticket_code
            $table->index('midtrans_order_id'); // Index biar cepat search
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['community', 'midtrans_order_id']);
        });
    }
};
