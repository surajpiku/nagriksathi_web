<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scheme_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheme_id')->constrained()->onDelete('cascade');
            $table->integer('step_number');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('link')->nullable();
            $table->string('link_label')->nullable();
            $table->string('office_type')->nullable();
            $table->boolean('is_online')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scheme_steps');
    }
};