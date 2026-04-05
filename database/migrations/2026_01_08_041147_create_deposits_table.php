<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id'); // penerima dana
            $table->unsignedBigInteger('booking_id')->nullable();

            $table->decimal('amount', 12, 2);

            $table->enum('source_type', ['booking','play_together','sparring']);
            $table->unsignedBigInteger('source_id');

            $table->unsignedBigInteger('participant_id')->nullable();

            $table->enum('status', ['pending','completed','canceled'])->default('pending');
            $table->string('description')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
