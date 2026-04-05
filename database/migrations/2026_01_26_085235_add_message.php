<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Reply feature
            if (!Schema::hasColumn('messages', 'reply_to_id')) {
                $table->unsignedBigInteger('reply_to_id')->nullable()->after('conversation_id');
                $table->foreign('reply_to_id')->references('id')->on('messages')->onDelete('set null');
            }
            
            // Media support
            if (!Schema::hasColumn('messages', 'media_type')) {
                $table->enum('media_type', ['image', 'video', 'voice'])->nullable()->after('message');
            }
            if (!Schema::hasColumn('messages', 'media_path')) {
                $table->string('media_path')->nullable()->after('media_type');
            }
            if (!Schema::hasColumn('messages', 'voice_duration')) {
                $table->string('voice_duration')->nullable()->after('media_path'); // Format: "0:15"
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['reply_to_id']);
            $table->dropColumn(['reply_to_id', 'media_type', 'media_path', 'voice_duration', 'read_at']);
        });
    }
};