<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('state_code', 5)->nullable()->after('state');
            $table->string('district_code')->nullable()->after('district');
            $table->string('block')->nullable()->after('district_code');
            $table->string('village')->nullable()->after('block');
            $table->string('pincode', 6)->nullable()->after('city');
            $table->enum('area_type', ['rural', 'urban', 'semi_urban'])->nullable()->after('pincode');
            $table->decimal('latitude', 10, 8)->nullable()->after('area_type');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->boolean('location_verified')->default(false)->after('longitude');
            $table->timestamp('location_updated_at')->nullable()->after('location_verified');
        });
    }
    public function down(): void {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'state_code', 'district_code', 'block', 'village',
                'pincode', 'area_type', 'latitude', 'longitude',
                'location_verified', 'location_updated_at',
            ]);
        });
    }
};