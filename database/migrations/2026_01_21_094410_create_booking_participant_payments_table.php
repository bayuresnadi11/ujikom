<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('booking_participant_payments', function (Blueprint $table) {
    $table->bigIncrements('id');

    $table->unsignedBigInteger('booking_id');
    $table->unsignedBigInteger('user_id');

    $table->unsignedBigInteger('play_together_participant_id')->nullable();
    $table->unsignedBigInteger('sparring_participant_id')->nullable();

    $table->decimal('amount', 12, 2);

    $table->string('payment_token')->nullable();
    $table->string('payment_url')->nullable();
    $table->string('midtrans_order_id')->nullable();
    $table->string('midtrans_transaction_status')->nullable();
    $table->timestamp('paid_at')->nullable();

    $table->timestamps();

    // ===== FOREIGN KEYS (MANUAL NAMES) =====

    $table->foreign('booking_id', 'fk_bpp_booking')
        ->references('id')
        ->on('bookings')
        ->onDelete('cascade');

    $table->foreign('user_id', 'fk_bpp_user')
        ->references('id')
        ->on('users')
        ->onDelete('cascade');

    $table->foreign('play_together_participant_id', 'fk_bpp_pt_participant')
        ->references('id')
        ->on('play_together_participants')
        ->onDelete('cascade');

    $table->foreign('sparring_participant_id', 'fk_bpp_sparring_participant')
        ->references('id')
        ->on('sparring_participants')
        ->onDelete('cascade');
});
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_participant_payments');
    }
};
