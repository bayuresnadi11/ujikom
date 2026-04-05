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
            // Make message nullable if not already
            $table->text('message')->nullable()->change();
            
            // Add voice duration column if not exists
            if (!Schema::hasColumn('messages', 'voice_duration')) {
                $table->string('voice_duration', 10)->nullable()->after('media_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'voice_duration')) {
                $table->dropColumn('voice_duration');
            }
        });
    }
};