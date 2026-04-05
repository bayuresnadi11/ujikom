<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('venue_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('section_id'); // sebelumnya: section_id
            $table->date('date'); // sebelumnya: tanggal
            $table->time('start_time'); // sebelumnya: jam_mulai
            $table->time('end_time'); // sebelumnya: jam_selesai
            $table->boolean('available'); // sebelumnya: tersedia
            $table->integer('rental_price'); // sebelumnya: harga_sewa
            $table->integer('rental_duration'); // sebelumnya: durasi_sewa
            $table->timestamps();

            $table->foreign('section_id')->references('id')->on('venue_sections')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venue_schedules');
    }
};