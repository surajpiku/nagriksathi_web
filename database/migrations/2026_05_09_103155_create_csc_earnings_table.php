<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('csc_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seva_mitra_id')->constrained()->onDelete('cascade');
            $table->foreignId('csc_customer_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('sathi_task_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('earning_type', ['customer_service', 'sathi_task', 'bonus', 'referral']);
            $table->decimal('gross_amount', 8, 2);
            $table->decimal('commission_deducted', 8, 2)->default(0);
            $table->decimal('net_amount', 8, 2);
            $table->enum('payment_status', ['pending', 'processing', 'paid', 'failed'])->default('pending');
            $table->string('payment_reference')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('csc_earnings'); }
};
