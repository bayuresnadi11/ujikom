<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sparring_participants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sparring_id');
            $table->unsignedBigInteger('community_id')->nullable(); // Jika participant_type = community
            $table->unsignedBigInteger('invited_by')->nullable(); // Comunity yang mengundang (untuk private)
            $table->enum('invitation_status', ['pending', 'accepted', 'rejected'])->nullable(); // Untuk private invitations
            $table->unsignedBigInteger('invitation_id')->nullable(); 
            // Status approval untuk host_approval
            $table->enum('payment_status', ['pending','paid'])->default('pending');
            // Payment Midtrans columns untuk pembayaran participant
            $table->decimal('amount', 12, 2)->nullable();
            $table->string('payment_token')->nullable();
            $table->string('payment_url')->nullable();
            $table->string('midtrans_order_id')->nullable();
            $table->string('midtrans_transaction_status')->nullable();

            $table->decimal('total_paid', 12, 2)->default(0);
            $table->timestamp('paid_at')->nullable();
            
            $table->timestamps();

            $table->foreign('invitation_id')->references('id')->on('sparring_invitations')->onDelete('set null');
            $table->foreign('sparring_id')->references('id')->on('sparring')->onDelete('cascade');
            $table->foreign('community_id')->references('id')->on('communities')->onDelete('set null');
            $table->foreign('invited_by')->references('id')->on('communities')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sparring_participants');
    }
};