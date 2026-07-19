<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('scheme_id')->constrained()->onDelete('cascade');
            $table->string('reference_number')->nullable();
            $table->string('status')->default('submitted');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('expected_by')->nullable();
            $table->string('portal_status')->nullable();
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};