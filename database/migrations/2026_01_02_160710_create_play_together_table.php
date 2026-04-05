<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('play_together', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('booking_id')->nullable(); // Relasi ke booking
            $table->unsignedBigInteger('community_id')->nullable();
            $table->date('date');
            $table->string('location');
            $table->integer('max_participants'); // Maksimal peserta
            $table->enum('type', ['free', 'paid']); // Tipe gratis atau berbayar

            $table->timestamp('payment_deadline');
            $table->integer('price_per_person')->nullable(); // Harga per orang jika berbayar
            $table->enum('privacy', ['public', 'private', 'community']); // Privacy type baru
            $table->string('gender')->nullable();
            $table->boolean('host_approval')->default(false);
            $table->unsignedBigInteger('created_by');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'active', 'completed', 'canceled'])->default('pending');
            
            // Payment Midtrans columns untuk participant payment
            $table->string('payment_type')->nullable(); // 'participant_payment' jika paid
            
            $table->timestamps();

            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('community_id')->references('id')->on('communities')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('play_together');
    }
};