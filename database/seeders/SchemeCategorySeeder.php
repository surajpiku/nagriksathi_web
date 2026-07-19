<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchemeCategory;

class SchemeCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Financial Benefits',    'hindi_name' => 'वित्तीय लाभ',        'slug' => 'financial',   'icon' => '💰', 'display_order' => 1],
            ['name' => 'Health Services',       'hindi_name' => 'स्वास्थ्य सेवाएं',   'slug' => 'health',      'icon' => '🏥', 'display_order' => 2],
            ['name' => 'Education',             'hindi_name' => 'शिक्षा',             'slug' => 'education',   'icon' => '📚', 'display_order' => 3],
            ['name' => 'Housing',               'hindi_name' => 'आवास',               'slug' => 'housing',     'icon' => '🏠', 'display_order' => 4],
            ['name' => 'Agriculture',           'hindi_name' => 'कृषि',               'slug' => 'agriculture', 'icon' => '🌾', 'display_order' => 5],
            ['name' => 'Employment & Skill',    'hindi_name' => 'रोजगार और कौशल',    'slug' => 'employment',  'icon' => '💼', 'display_order' => 6],
            ['name' => 'Women & Child',         'hindi_name' => 'महिला और बाल',       'slug' => 'women',       'icon' => '👩', 'display_order' => 7],
            ['name' => 'Elderly & Disabled',    'hindi_name' => 'वृद्ध और दिव्यांग', 'slug' => 'elderly',     'icon' => '👴', 'display_order' => 8],
            ['name' => 'Documents & Identity',  'hindi_name' => 'दस्तावेज़ और पहचान','slug' => 'documents',   'icon' => '📄', 'display_order' => 9],
            ['name' => 'Business & MSME',       'hindi_name' => 'व्यवसाय और MSME',   'slug' => 'business',    'icon' => '🏭', 'display_order' => 10],
            ['name' => 'Legal & Rights',        'hindi_name' => 'कानूनी और अधिकार', 'slug' => 'legal',       'icon' => '⚖️', 'display_order' => 11],
            ['name' => 'Digital & Tech',        'hindi_name' => 'डिजिटल और तकनीक',  'slug' => 'digital',     'icon' => '📱', 'display_order' => 12],
        ];

        foreach ($categories as $category) {
            SchemeCategory::updateOrCreate(
                ['slug' => $category['slug']],
                array_merge($category, ['is_active' => true])
            );
        }

        $this->command->info('✅ 12 scheme categories seeded!');
    }
}