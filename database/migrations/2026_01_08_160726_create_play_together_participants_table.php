<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('play_together_participants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('play_together_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('invited_by')->nullable(); // User yang mengundang (untuk private)
            $table->enum('invitation_status', ['pending', 'accepted', 'rejected'])->nullable(); // Untuk private invitations
            $table->unsignedBigInteger('invitation_id')->nullable(); 
            // Status approval untuk host_approval
            $table->enum('approval_status', ['pending', 'approved', 'rejected', 'canceled'])->default('pending');
            $table->enum('payment_status', ['pending','partial','paid'])->default('pending');
            // Payment Midtrans columns untuk pembayaran participant
            $table->decimal('amount', 12, 2)->nullable();      // harga WAJIB participant SAAT INI
            $table->decimal('total_paid', 12, 2)->default(0);  // total uang yang SUDAH dibayar
            $table->string('payment_token')->nullable();
            $table->string('payment_url')->nullable();
            $table->string('midtrans_order_id')->nullable();
            $table->string('midtrans_transaction_status')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();

            $table->foreign('invitation_id')->references('id')->on('play_together_invitations')->onDelete('set null');
            $table->foreign('play_together_id')->references('id')->on('play_together')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('invited_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('play_together_participants');
    }
};