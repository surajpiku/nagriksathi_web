<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('service_contacts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_service_id')->constrained()->onDelete('cascade');
        $table->foreignId('seeker_id')->constrained('users')->onDelete('cascade');
        $table->enum('contact_method', ['in_app_chat', 'phone_call', 'whatsapp'])->default('in_app_chat');
        $table->timestamp('contacted_at')->useCurrent();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_contacts');
    }
};
