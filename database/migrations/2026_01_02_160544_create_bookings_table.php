<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->enum('type', ['regular', 'play_together', 'sparring'])->default('regular');

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('venue_id');
            $table->unsignedBigInteger('schedule_id');

            $table->string('ticket_code');
            $table->enum('scan_status', [
                'belum_scan',
                'masuk_venue',
                'masuk_lapang'
            ])->default('belum_scan'); 
            $table->timestamp('scan_time')->nullable();

            // ===== PAYMENT CORE =====
            $table->decimal('amount', 12, 2)->nullable(); // total wajib booking
            $table->enum('method', ['cash', 'midtrans'])
                  ->nullable();
            $table->decimal('total_paid', 12, 2)->default(0); // total dari participant
            $table->decimal('paid_amount', 12, 2)->nullable(); // host payment (jika host bayar)
            $table->integer('change')
                  ->nullable();

            $table->enum('booking_payment', ['pending','partial','full'])->default('pending');
            $table->enum('pay_by', ['host','participant'])->nullable();

            // kembalian
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('venue_id')->references('id')->on('venues')->onDelete('cascade');
            $table->foreign('schedule_id')->references('id')->on('venue_schedules')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
