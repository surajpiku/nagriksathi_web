<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
   public function run(): void
{
   $this->call([
    RolesAndPermissionsSeeder::class,
    LocationSeeder::class,
    SchemeCategorySeeder::class,
    SchemeSeeder::class,
    OpportunityCategorySeeder::class,
    OpportunitySeeder::class,
    SubscriptionPlanSeeder::class, // ← Add this
]);
}
}