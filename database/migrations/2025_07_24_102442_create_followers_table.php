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
        Schema::create('followers', function (Blueprint $table) {
            $table->primary(['user_id', 'following_user_id']); // Kunci utama gabungan

            // user_id adalah orang yang melakukan follow
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // following_user_id adalah orang yang di-follow
            $table->foreignId('following_user_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('followers');
    }
};
