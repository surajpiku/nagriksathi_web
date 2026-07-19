<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('saved_opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('opportunity_id')->constrained()->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'opportunity_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('saved_opportunities'); }
};