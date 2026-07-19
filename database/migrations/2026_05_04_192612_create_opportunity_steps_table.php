<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('opportunity_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opportunity_id')->constrained()->onDelete('cascade');
            $table->integer('step_number');
            $table->string('title');
            $table->text('description');
            $table->string('link')->nullable();
            $table->string('link_label')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('opportunity_steps'); }
};