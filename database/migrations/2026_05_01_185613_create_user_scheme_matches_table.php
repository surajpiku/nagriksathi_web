<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_scheme_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('scheme_id')->constrained()->onDelete('cascade');
            $table->enum('eligibility_status', ['eligible', 'needs_docs', 'ineligible'])->default('ineligible');
            $table->integer('match_score')->default(0);
            $table->timestamp('matched_at')->nullable();
            $table->timestamp('applied_at')->nullable();
            $table->timestamp('claimed_at')->nullable();
            $table->decimal('benefit_value', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_scheme_matches');
    }
};