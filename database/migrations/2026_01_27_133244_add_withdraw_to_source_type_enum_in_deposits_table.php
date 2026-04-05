<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Untuk MySQL, kita perlu menggunakan raw query untuk mengubah ENUM
        DB::statement("ALTER TABLE deposits 
            MODIFY COLUMN source_type 
            ENUM('booking', 'play_together', 'sparring', 'withdraw') 
            NOT NULL");
    }

    public function down(): void
    {
        // Kembalikan ke enum sebelumnya
        DB::statement("ALTER TABLE deposits 
            MODIFY COLUMN source_type 
            ENUM('booking', 'play_together', 'sparring') 
            NOT NULL");
    }
};