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
    Schema::create('gram_panchayats_master', function (Blueprint $table) {
        $table->id();
        $table->foreignId('subdistrict_id')->constrained('subdistricts_master')->onDelete('cascade');
        $table->string('name');
        $table->string('hindi_name')->nullable();
        $table->string('lgd_code')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gram_panchayats_master');
    }
};
