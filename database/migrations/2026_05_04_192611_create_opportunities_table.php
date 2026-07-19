<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('hindi_name')->nullable();
            $table->string('slug')->unique();
            $table->foreignId('category_id')->constrained('opportunity_categories');
            $table->string('conducting_body');
            $table->string('post_name');
            $table->enum('level', ['central', 'state']);
            $table->string('state_code')->nullable();
            $table->text('description');
            $table->json('eligibility_rules_json');
            $table->json('physical_standards_json')->nullable();
            $table->json('documents_required_json');
            $table->integer('vacancy_count')->nullable();
            $table->string('salary_range')->nullable();
            $table->string('job_location')->nullable();
            $table->string('notification_url')->nullable();
            $table->string('apply_url')->nullable();
            $table->string('syllabus_url')->nullable();
            $table->string('official_site')->nullable();
            $table->string('helpline')->nullable();
            $table->date('apply_start')->nullable();
            $table->date('apply_end')->nullable();
            $table->date('exam_date')->nullable();
            $table->date('admit_card_date')->nullable();
            $table->date('result_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('opportunities'); }
};