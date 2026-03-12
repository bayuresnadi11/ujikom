<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('community_members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('community_id');     
            $table->unsignedBigInteger('user_id');
            $table->enum('role', ['admin', 'anggota'])->default('anggota'); 
            $table->string('status')->default('active');
            $table->timestamp('joined_at');
            $table->unsignedBigInteger('invitation_id')->nullable(); 
            $table->foreign('invitation_id')->references('id')->on('community_invitations')->onDelete('set null');
            $table->foreign('community_id')->references('id')->on('communities')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_members');
    }
};