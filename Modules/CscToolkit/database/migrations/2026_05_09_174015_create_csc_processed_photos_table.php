<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('csc_processed_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('csc_agent_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('photo_type', ['passport', 'stamp', 'document', 'id_card']);
            $table->string('preset')->nullable();
            $table->string('original_url');
            $table->string('processed_url');
            $table->integer('width_mm')->nullable();
            $table->integer('height_mm')->nullable();
            $table->integer('file_size_kb')->nullable();
            $table->string('format')->default('jpg');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('csc_processed_photos'); }
};
