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
        Schema::table('users', function (Blueprint $table) {
            // Permission flag (Boolean) - Identity
            $table->boolean('can_switch_to_landowner')->default(false)->after('role');
            
            // Timestamp for approval
            $table->timestamp('landowner_approved_at')->nullable()->after('can_switch_to_landowner');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['can_switch_to_landowner', 'landowner_approved_at']);
        });
    }
};
