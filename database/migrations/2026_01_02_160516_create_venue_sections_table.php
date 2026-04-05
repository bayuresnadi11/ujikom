<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('venue_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('venue_id'); 
            $table->string('section_name'); 
            $table->text('description'); 
            $table->timestamps();

            $table->foreign('venue_id')->references('id')->on('venues')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venue_sections');
    }
};