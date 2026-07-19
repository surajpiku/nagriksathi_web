<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('opportunity_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('opportunity_id')->constrained()->onDelete('cascade');
            $table->enum('alert_type', ['new_notification','apply_starting','apply_ending','admit_card_released','exam_reminder','result_declared','new_vacancy']);
            $table->string('title');
            $table->text('message');
            $table->string('action_url')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('opportunity_alerts'); }
};