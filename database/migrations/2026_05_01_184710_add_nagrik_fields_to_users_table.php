<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 15)->unique()->nullable()->after('id');
            $table->string('language', 5)->default('hi')->after('email');
            $table->enum('subscription_tier', ['free', 'plus', 'pro'])->default('free')->after('language');
            $table->integer('nagrik_score')->default(0)->after('subscription_tier');
            $table->string('fcm_token')->nullable()->after('nagrik_score');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'language', 'subscription_tier', 'nagrik_score', 'fcm_token']);
        });
    }
};