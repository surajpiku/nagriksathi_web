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
    Schema::create('user_services', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('category_id')->constrained('service_categories')->onDelete('cascade');
        $table->foreignId('service_type_id')->constrained('service_types')->onDelete('cascade');
        $table->text('description')->nullable();
        $table->enum('availability', ['available_now', 'available_today', 'by_appointment', 'weekdays_only', 'weekends_only'])->default('available_now');
        $table->enum('price_range', ['free', 'negotiable', 'low', 'medium', 'high'])->default('negotiable');
        $table->json('languages_json')->nullable();
        $table->string('service_area')->nullable();
        $table->decimal('rating', 3, 2)->default(0);
        $table->integer('review_count')->default(0);
        $table->integer('contact_count')->default(0);
        $table->enum('status', ['active', 'paused', 'suspended'])->default('active');
        $table->boolean('is_verified')->default(false);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_services');
    }
};
