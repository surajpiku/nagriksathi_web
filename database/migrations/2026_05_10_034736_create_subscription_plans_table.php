<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('hindi_name')->nullable();
            $table->enum('type', ['citizen', 'seva_mitra']);
            $table->decimal('price_monthly', 8, 2)->default(0);
            $table->decimal('price_yearly', 8, 2)->default(0);
            $table->string('razorpay_plan_monthly')->nullable();
            $table->string('razorpay_plan_yearly')->nullable();
            $table->json('features_json');
            $table->json('limits_json');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_popular')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('subscription_plans'); }
};
