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
    Schema::create('service_reviews', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_service_id')->constrained()->onDelete('cascade');
        $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
        $table->tinyInteger('rating');
        $table->text('comment')->nullable();
        $table->boolean('is_approved')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_reviews');
    }
};
