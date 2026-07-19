<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['scheme', 'deadline', 'renewal', 'new_scheme', 'payment'])->default('scheme');
            $table->string('title');
            $table->text('message')->nullable();
            $table->json('data_json')->nullable();
            $table->enum('urgency', ['low', 'medium', 'high', 'critical'])->default('low');
            $table->timestamp('read_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};