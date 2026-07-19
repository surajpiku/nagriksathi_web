<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('user_opportunity_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('opportunity_id')->constrained()->onDelete('cascade');
            $table->enum('eligibility_status', ['eligible', 'needs_preparation', 'age_bar', 'ineligible']);
            $table->integer('match_score')->default(0);
            $table->boolean('has_applied')->default(false);
            $table->boolean('is_saved')->default(false);
            $table->date('applied_on')->nullable();
            $table->timestamp('matched_at')->useCurrent();
            $table->unique(['user_id', 'opportunity_id']);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('user_opportunity_matches'); }
};