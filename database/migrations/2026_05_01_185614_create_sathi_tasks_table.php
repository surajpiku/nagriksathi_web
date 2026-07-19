<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sathi_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->string('task_type');
            $table->enum('status', ['open', 'assigned', 'in_progress', 'completed', 'escalated'])->default('open');
            $table->text('description')->nullable();
            $table->text('resolution')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('channel', ['app', 'whatsapp', 'voice'])->default('app');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sathi_tasks');
    }
};