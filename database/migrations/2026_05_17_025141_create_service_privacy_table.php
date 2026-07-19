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
    Schema::create('service_privacy', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();
        $table->enum('visibility', ['everyone', 'women_only', 'hidden'])->default('everyone');
        $table->enum('location_precision', ['village', 'block', 'district'])->default('block');
        $table->enum('contact_preference', ['in_app_chat', 'phone', 'both'])->default('both');
        $table->boolean('show_phone')->default(false);
        $table->boolean('all_paused')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_privacy');
    }
};
