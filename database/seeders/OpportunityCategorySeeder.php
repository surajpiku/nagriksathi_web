<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\OpportunityCategory;

class OpportunityCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Civil Services',      'hindi_name' => 'सिविल सेवाएं',        'slug' => 'civil-services', 'icon' => '🏛️', 'display_order' => 1],
            ['name' => 'Military & Defence',  'hindi_name' => 'सैन्य और रक्षा',      'slug' => 'defence',        'icon' => '⚔️', 'display_order' => 2],
            ['name' => 'Banking & Finance',   'hindi_name' => 'बैंकिंग और वित्त',    'slug' => 'banking',        'icon' => '🏦', 'display_order' => 3],
            ['name' => 'Railway',             'hindi_name' => 'रेलवे',               'slug' => 'railway',        'icon' => '🚂', 'display_order' => 4],
            ['name' => 'SSC',                 'hindi_name' => 'एसएससी',              'slug' => 'ssc',            'icon' => '📝', 'display_order' => 5],
            ['name' => 'Teaching',            'hindi_name' => 'शिक्षण',              'slug' => 'teaching',       'icon' => '📚', 'display_order' => 6],
            ['name' => 'Healthcare',          'hindi_name' => 'स्वास्थ्य सेवा',     'slug' => 'healthcare',     'icon' => '🏥', 'display_order' => 7],
            ['name' => 'Engineering & PSU',   'hindi_name' => 'इंजीनियरिंग और PSU', 'slug' => 'engineering',    'icon' => '⚙️', 'display_order' => 8],
            ['name' => 'State Government',    'hindi_name' => 'राज्य सरकार',        'slug' => 'state-govt',     'icon' => '🏢', 'display_order' => 9],
            ['name' => 'Scholarships',        'hindi_name' => 'छात्रवृत्ति',         'slug' => 'scholarships',   'icon' => '🎓', 'display_order' => 10],
        ];

        foreach ($categories as $cat) {
            OpportunityCategory::updateOrCreate(
                ['slug' => $cat['slug']],
                array_merge($cat, ['is_active' => true])
            );
        }

        $this->command->info('✅ 10 opportunity categories seeded!');
    }
}