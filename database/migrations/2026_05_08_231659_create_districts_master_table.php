<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('districts_master', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained('states_master')->onDelete('cascade');
            $table->string('name');
            $table->string('hindi_name')->nullable();
            $table->string('code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('districts_master'); }
};