<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('community_invitations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('community_id');
            $table->unsignedBigInteger('invited_user_id'); // User yang diundang
            $table->unsignedBigInteger('invited_by'); // Siapa yang mengundang
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();

            $table->foreign('community_id')->references('id')->on('communities')->onDelete('cascade');
            $table->foreign('invited_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('invited_by')->references('id')->on('users')->onDelete('cascade');
            
            // Pastikan tidak ada duplicate pending invitation
            $table->unique(['community_id', 'invited_user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_invitations');
    }
};