<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('csc_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seva_mitra_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->string('task_type');
            $table->text('task_description')->nullable();
            $table->enum('status', ['waiting', 'in_progress', 'completed', 'cancelled'])->default('waiting');
            $table->integer('token_number')->nullable();
            $table->decimal('amount_charged', 8, 2)->default(0);
            $table->decimal('platform_commission', 8, 2)->default(0);
            $table->decimal('agent_earning', 8, 2)->default(0);
            $table->enum('payment_method', ['cash', 'upi', 'platform'])->nullable();
            $table->integer('rating')->nullable();
            $table->text('customer_feedback')->nullable();
            $table->timestamp('visited_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('csc_customers'); }
};
