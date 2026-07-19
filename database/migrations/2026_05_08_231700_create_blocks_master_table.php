<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('blocks_master', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->constrained('districts_master')->onDelete('cascade');
            $table->string('name');
            $table->string('hindi_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('blocks_master'); }
};