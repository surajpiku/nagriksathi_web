<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('subdistricts_master', function (Blueprint $table) {
        $table->id();
        $table->foreignId('district_id')->constrained('districts_master')->onDelete('cascade');
        $table->string('name');
        $table->string('hindi_name')->nullable();
        $table->string('code')->nullable();
        $table->enum('type', ['block', 'tehsil', 'taluka', 'taluk', 'mandal'])->default('block');
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subdistricts_master');
    }
};
