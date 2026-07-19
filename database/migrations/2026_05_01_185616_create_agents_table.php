<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('state')->nullable();
            $table->json('languages_json')->nullable();
            $table->string('specialisation')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('tasks_completed')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('fcm_token')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};