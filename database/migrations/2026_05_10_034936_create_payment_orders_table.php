<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payment_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('subscription_plans')->onDelete('cascade');
            $table->string('razorpay_order_id')->unique();
            $table->string('razorpay_payment_id')->nullable();
            $table->string('razorpay_signature')->nullable();
            $table->decimal('amount', 8, 2);
            $table->string('currency')->default('INR');
            $table->enum('billing_cycle', ['monthly', 'yearly']);
            $table->enum('status', ['created', 'paid', 'failed', 'refunded'])->default('created');
            $table->json('razorpay_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('payment_orders'); }
};