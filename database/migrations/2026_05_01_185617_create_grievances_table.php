<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grievances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->string('cpgrams_id')->nullable();
            $table->string('rti_reference')->nullable();
            $table->text('rti_text')->nullable();
            $table->string('status')->default('filed');
            $table->timestamp('filed_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grievances');
    }
};