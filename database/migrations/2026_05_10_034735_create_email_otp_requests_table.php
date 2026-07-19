<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('email_otp_requests', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('otp_hash');
            $table->timestamp('expires_at');
            $table->timestamp('verified_at')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
            $table->index('email');
        });
    }
    public function down(): void { Schema::dropIfExists('email_otp_requests'); }
};