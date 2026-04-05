<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('category_id'); // sebelumnya: kategori_id
            $table->string('venue_name'); // sebelumnya: nama_lapangan
            $table->text('description'); // sebelumnya: deskripsi
            $table->string('location'); // sebelumnya: lokasi
            $table->float('rating')->nullable();
            $table->string('photo'); // sebelumnya: foto
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};