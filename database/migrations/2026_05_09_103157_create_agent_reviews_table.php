<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('agent_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seva_mitra_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('csc_customer_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('rating');
            $table->text('review')->nullable();
            $table->boolean('is_verified_visit')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('agent_reviews'); }
};
