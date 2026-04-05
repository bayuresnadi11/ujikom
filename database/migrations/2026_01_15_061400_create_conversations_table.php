<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_one_id')->nullable();
            $table->unsignedBigInteger('user_two_id')->nullable();

            $table->string('type')->default('private');
            $table->unsignedBigInteger('community_id')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            $table->foreign('user_one_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_two_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('community_id')->references('id')->on('communities')->onDelete('cascade');

            $table->index(['user_one_id', 'user_two_id']);
            $table->index('last_message_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('conversations');
    }
};