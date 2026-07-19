<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('state')->nullable();
            $table->string('district')->nullable();
            $table->string('city')->nullable();
            $table->string('occupation')->nullable();
            $table->unsignedBigInteger('annual_income')->default(0);
            $table->enum('caste_category', ['general', 'obc', 'sc', 'st'])->default('general');
            $table->boolean('bpl_status')->default(false);
            $table->decimal('land_acres', 8, 2)->default(0);
            $table->string('house_type')->nullable();
            $table->boolean('has_vehicle')->default(false);
            $table->json('assets_json')->nullable();
            $table->boolean('is_complete')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};