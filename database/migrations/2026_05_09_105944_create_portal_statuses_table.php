<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('portal_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('portal_name');
            $table->string('portal_url');
            $table->string('check_url');
            $table->enum('status', ['online', 'slow', 'down', 'unknown'])->default('unknown');
            $table->integer('response_time_ms')->nullable();
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamp('down_since')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('portal_statuses'); }
};