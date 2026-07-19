<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('opportunities', function (Blueprint $table) {
            $table->string('district')->nullable()->after('state_code');
            $table->enum('local_level', [
                'gram_panchayat', 'block', 'tehsil',
                'municipality', 'corporation', 'cantonment'
            ])->nullable()->after('district');
        });
    }
    public function down(): void {
        Schema::table('opportunities', function (Blueprint $table) {
            $table->dropColumn(['district', 'local_level']);
        });
    }
};