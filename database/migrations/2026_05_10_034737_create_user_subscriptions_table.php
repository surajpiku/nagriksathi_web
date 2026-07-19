<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('subscription_plans')->onDelete('cascade');
            $table->enum('billing_cycle', ['monthly', 'yearly']);
            $table->enum('status', ['active', 'cancelled', 'expired', 'trial'])->default('active');
            $table->string('razorpay_subscription_id')->nullable();
            $table->timestamp('starts_at')->useCurrent();
$table->timestamp('ends_at')->useCurrent();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->timestamps();
            $table->index('user_id');
        });
    }
    public function down(): void { Schema::dropIfExists('user_subscriptions'); }
};