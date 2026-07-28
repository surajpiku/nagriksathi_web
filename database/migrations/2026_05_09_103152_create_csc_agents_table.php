<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('seva_mitras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('centre_name')->nullable();
            $table->string('csc_id')->nullable();
            $table->enum('agent_type', ['official_vle', 'sathi_partner', 'partner_agent'])->default('sathi_partner');
            $table->string('state')->nullable();
            $table->string('state_code', 5)->nullable();
            $table->string('district')->nullable();
            $table->string('block')->nullable();
            $table->string('village')->nullable();
            $table->string('pincode', 6)->nullable();
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->json('services_json')->nullable();
            $table->json('languages_json')->nullable();
            $table->json('working_hours_json')->nullable();
            $table->json('pricing_json')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('tasks_completed')->default(0);
            $table->integer('customers_served')->default(0);
            $table->string('bank_account')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('upi_id')->nullable();
            $table->string('vle_certificate_url')->nullable();
            $table->string('collaboration_letter_url')->nullable();
            $table->string('aadhaar_url')->nullable();
            $table->string('centre_photo_url')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected', 'suspended'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('seva_mitras'); }
};
