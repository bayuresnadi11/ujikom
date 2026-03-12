<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participant_price_adjustments', function (Blueprint $table) {
            $table->bigIncrements('id');

            /**
             * Relasi ke event
             * Bisa play together atau sparring
             */
            $table->enum('source_type', ['play_together', 'sparring']);
            $table->unsignedBigInteger('source_id');

            /**
             * Harga sebelum & sesudah penyesuaian
             * Ini adalah KEWAJIBAN participant
             */
            $table->decimal('old_amount', 12, 2);
            $table->decimal('new_amount', 12, 2);

            /**
             * Alasan penyesuaian (optional tapi sangat disarankan)
             * contoh: "participant kurang", "hari H", dll
             */
            $table->string('reason')->nullable();

            /**
             * Waktu mulai berlaku
             */
            $table->timestamp('effective_at');

            /**
             * Siapa yang melakukan adjustment (host / admin)
             */
            $table->unsignedBigInteger('adjusted_by')->nullable();

            $table->timestamps();

            /**
             * Foreign key
             * (Tidak pakai polymorphic FK karena MySQL tidak support)
             */
            $table->foreign('adjusted_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            /**
             * Index untuk performa
             */
            $table->index(['source_type', 'source_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participant_price_adjustments');
    }
};
