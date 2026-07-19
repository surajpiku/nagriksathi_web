<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schemes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('hindi_name')->nullable();
            $table->string('slug')->unique();
            $table->foreignId('category_id')->constrained('scheme_categories')->onDelete('cascade');
            $table->string('ministry')->nullable();
            $table->string('state')->default('central');
            $table->text('description')->nullable();
            $table->json('eligibility_rules_json')->nullable();
            $table->json('documents_required_json')->nullable();
            $table->decimal('benefit_value', 12, 2)->default(0);
            $table->string('benefit_type')->nullable();
            $table->string('portal_url')->nullable();
            $table->string('form_url')->nullable();
            $table->string('status_url')->nullable();
            $table->string('helpline')->nullable();
            $table->string('whatsapp')->nullable();
            $table->date('deadline')->nullable();
            $table->boolean('is_central')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schemes');
    }
};