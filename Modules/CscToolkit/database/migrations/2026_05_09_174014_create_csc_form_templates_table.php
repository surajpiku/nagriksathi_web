<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('csc_form_templates', function (Blueprint $table) {
            $table->id();
            $table->string('form_id')->unique();
            $table->string('form_name');
            $table->string('hindi_name')->nullable();
            $table->string('category')->nullable();
            $table->string('portal_url');
            $table->string('portal_name')->nullable();
            $table->json('fields_json');
            $table->integer('total_fields')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('csc_form_templates'); }
};