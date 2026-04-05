<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('community_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('community_id'); // sebelumnya: community_id
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['pending', 'rejected', 'approved'])->default('pending');
            $table->timestamps();

            $table->foreign('community_id')->references('id')->on('communities')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_requests');
    }
};