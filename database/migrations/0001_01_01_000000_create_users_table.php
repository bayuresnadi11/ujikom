<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name'); 
            $table->string('phone');
            $table->text('address')->nullable();
            $table->string('business_name')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('birthdate')->nullable();
            $table->string('password');
            $table->enum('role', ['buyer', 'landowner', 'admin', 'cashier']); 
            $table->unsignedBigInteger('created_by')->nullable();
            $table->rememberToken();
            $table->string('avatar')->nullable();
            $table->foreign('created_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};