<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sparring_invitations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sparring_id');
            $table->unsignedBigInteger('invited_by');
            $table->unsignedBigInteger('invited_community_id')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->foreign('sparring_id')->references('id')->on('sparring')->onDelete('cascade');
            $table->foreign('invited_by')->references('id')->on('communities')->onDelete('cascade');
            $table->foreign('invited_community_id')->references('id')->on('communities')->onDelete('cascade');
        });
    }
    public function down(): void { Schema::dropIfExists('sparring_invitations'); }
};