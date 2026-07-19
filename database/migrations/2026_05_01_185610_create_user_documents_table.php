<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('doc_type');
            $table->string('file_url');
            $table->unsignedBigInteger('file_size')->nullable();
            $table->json('extracted_data_json')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('digilocker_id')->nullable();
            $table->string('ocr_status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_documents');
    }
};