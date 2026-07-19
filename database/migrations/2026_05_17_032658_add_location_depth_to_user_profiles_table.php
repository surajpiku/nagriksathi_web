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
    Schema::table('user_profiles', function (Blueprint $table) {
        $table->string('state_lgd_code')->nullable()->after('state_code');
        $table->unsignedBigInteger('subdistrict_id')->nullable()->after('district_code');
        $table->string('gram_panchayat')->nullable()->after('village');
        $table->string('locality_name')->nullable()->after('gram_panchayat');
        $table->string('ward_number')->nullable()->after('locality_name');
        $table->boolean('location_complete')->default(false)->after('location_verified');
        $table->tinyInteger('location_depth')->default(0)->after('location_complete');
        $table->timestamp('gps_captured_at')->nullable()->after('location_updated_at');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            //
        });
    }
};
