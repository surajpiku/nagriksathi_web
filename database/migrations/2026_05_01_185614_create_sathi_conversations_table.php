<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sathi_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->unsignedBigInteger('task_id')->nullable();
            $table->string('channel')->default('app');
            $table->json('messages_json')->nullable();
            $table->integer('ai_tokens_used')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sathi_conversations');
    }
};