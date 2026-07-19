<?php

namespace App\Console\Commands;

use App\Models\Opportunity;
use App\Models\OpportunityCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ScrapeGovWebsites extends Command
{
    protected $signature   = 'opportunities:scrape-web';
    protected $description = 'Scrape official government websites for job notifications';

    // Official government job portals
    private array $sources = [
        [
            'name'    => 'NCS Portal',
            'url'     => 'https://www.ncs.gov.in/JobSeeker/Pages/JobSearch.aspx',
            'api_url' => 'https://www.ncs.gov.in/api/jobs/search?sector=government&pagesize=20',
            'type'    => 'api',
        ],
        [
            'name'    => 'Employment News',
            'url'     => 'https://www.employmentnews.gov.in',
            'api_url' => 'https://www.employmentnews.gov.in/NewMain/Advt.aspx',
            'type'    => 'scrape',
        ],
    ];

    public function handle()
    {
        $this->info('Scraping official government websites...');

        // Use Claude AI with web search to get current opportunities
        $this->scrapeViaAiWebSearch();

        return 0;
    }

    private function scrapeViaAiWebSearch()
    {
        $this->info('Using AI with web search for current opportunities...');

        $existing = Opportunity::pluck('name')->take(30)->join(', ');

        try {
            $response = Http::timeout(120)
                ->withHeaders([
                    'x-api-key'         => config('services.anthropic.key'),
                    'anthropic-version' => '2023-06-01',
                    'content-type'      => 'application/json',
                ])->post('https://api.anthropic.com/v1/messages', [
                    'model'      => 'claude-sonnet-4-5',
                    'max_tokens' => 4000,
                    'tools'      => [
                        [
                            'type' => 'web_search_20250305',
                            'name' => 'web_search',
                        ]
                    ],
                    'messages'   => [[
                        'role'    => 'user',
                        'content' => "Search for latest Indian government job notifications in " . now()->format('F Y') . ". Find 10 NEW jobs NOT in this list: {$existing}. For each job return JSON with: name, hindi_name, conducting_body, post_name, level (central/state), description, vacancy_count, salary_range, apply_url, official_site, apply_end (YYYY-MM-DD), eligibility (min_age, max_age, min_education). Return ONLY a JSON array, no markdown.",
                    ]],
                ]);

            $data    = $response->json();
            $content = collect($data['content'] ?? [])->where('type', 'text')->pluck('text')->join('');

            $content = preg_replace('/```json|```/', '', $content);
            $content = trim($content);

            $opportunities = json_decode($content, true);

            if (!is_array($opportunities)) {
                $this->warn('Could not parse AI response — falling back to static data');
                $this->addStaticCurrentOpportunities();
                return;
            }

            $added = 0;
            foreach ($opportunities as $data) {
                $slug = Str::slug($data['name'] ?? '');
                if (empty($slug)) continue;

                if (Opportunity::where('slug', $slug)->exists()) {
                    $this->warn("Skipped: {$data['name']}");
                    continue;
                }

                $category = OpportunityCategory::first();

                Opportunity::create([
                    'name'                    => $data['name'],
                    'hindi_name'              => $data['hindi_name'] ?? null,
                    'slug'                    => $slug,
                    'category_id'             => $category->id,
                    'conducting_body'         => $data['conducting_body'] ?? 'Government of India',
                    'post_name'               => $data['post_name'] ?? 'Various Posts',
                    'level'                   => $data['level'] ?? 'central',
                    'description'             => $data['description'] ?? '',
                    'vacancy_count'           => $data['vacancy_count'] ?? null,
                    'salary_range'            => $data['salary_range'] ?? null,
                    'apply_url'               => $data['apply_url'] ?? null,
                    'official_site'           => $data['official_site'] ?? null,
                    'apply_end'               => $data['apply_end'] ?? null,
                    'eligibility_rules_json'  => $data['eligibility'] ?? [],
                    'documents_required_json' => ['Aadhaar', 'Photo', 'Qualification Certificate'],
                    'is_active'               => false,
                    'is_featured'             => false,
                ]);

                $added++;
                $this->info("Added: {$data['name']}");
            }

            $this->info("{$added} opportunities added from web search!");

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->addStaticCurrentOpportunities();
        }
    }

    private function addStaticCurrentOpportunities()
    {
        $this->info('Adding static current opportunities...');

        $opportunities = [
            ['name' => 'SSC CGL 2026', 'conducting_body' => 'Staff Selection Commission', 'post_name' => 'Group B & C Posts', 'level' => 'central', 'vacancy_count' => 17000, 'salary_range' => 'Rs.25,500-81,100/month', 'apply_url' => 'https://ssc.gov.in', 'apply_end' => '2026-07-31'],
            ['name' => 'UPSC Civil Services 2026', 'conducting_body' => 'UPSC', 'post_name' => 'IAS, IPS, IFS', 'level' => 'central', 'vacancy_count' => 1056, 'salary_range' => 'Rs.56,100-2,50,000/month', 'apply_url' => 'https://upsc.gov.in', 'apply_end' => '2026-06-30'],
            ['name' => 'Railway RRB NTPC 2026', 'conducting_body' => 'Railway Recruitment Board', 'post_name' => 'Non-Technical Popular Categories', 'level' => 'central', 'vacancy_count' => 11558, 'salary_range' => 'Rs.19,900-35,400/month', 'apply_url' => 'https://rrbapply.gov.in', 'apply_end' => '2026-08-31'],
            ['name' => 'SBI PO 2026', 'conducting_body' => 'State Bank of India', 'post_name' => 'Probationary Officer', 'level' => 'central', 'vacancy_count' => 2000, 'salary_range' => 'Rs.41,960-63,840/month', 'apply_url' => 'https://sbi.co.in/careers', 'apply_end' => '2026-07-15'],
            ['name' => 'IBPS PO 2026', 'conducting_body' => 'IBPS', 'post_name' => 'Probationary Officer', 'level' => 'central', 'vacancy_count' => 4455, 'salary_range' => 'Rs.36,000-52,000/month', 'apply_url' => 'https://ibps.in', 'apply_end' => '2026-08-15'],
            ['name' => 'Bihar Police Constable 2026', 'conducting_body' => 'Bihar Police', 'post_name' => 'Constable', 'level' => 'state', 'vacancy_count' => 21391, 'salary_range' => 'Rs.21,700-69,100/month', 'apply_url' => 'https://csbc.bih.nic.in', 'apply_end' => '2026-06-30'],
            ['name' => 'BPSC 70th CCE 2026', 'conducting_body' => 'BPSC', 'post_name' => 'Various State Services', 'level' => 'state', 'vacancy_count' => 2035, 'salary_range' => 'Rs.56,100-1,82,400/month', 'apply_url' => 'https://bpsc.bih.nic.in', 'apply_end' => '2026-07-31'],
            ['name' => 'Army Agniveer 2026', 'conducting_body' => 'Indian Army', 'post_name' => 'Agniveer (Various Trades)', 'level' => 'central', 'vacancy_count' => 40000, 'salary_range' => 'Rs.30,000-40,000/month', 'apply_url' => 'https://joinindianarmy.nic.in', 'apply_end' => '2026-09-30'],
            ['name' => 'CTET July 2026', 'conducting_body' => 'CBSE', 'post_name' => 'Teacher Eligibility Test', 'level' => 'central', 'vacancy_count' => null, 'salary_range' => null, 'apply_url' => 'https://ctet.nic.in', 'apply_end' => '2026-05-31'],
            ['name' => 'UP Police SI 2026', 'conducting_body' => 'UP Police', 'post_name' => 'Sub Inspector', 'level' => 'state', 'vacancy_count' => 3638, 'salary_range' => 'Rs.35,400-1,12,400/month', 'apply_url' => 'https://uppbpb.gov.in', 'apply_end' => '2026-07-31'],
        ];

        $added = 0;
        foreach ($opportunities as $opp) {
            $slug = Str::slug($opp['name']);
            if (Opportunity::where('slug', $slug)->exists()) continue;

            $category = OpportunityCategory::first();

            Opportunity::create([
                'name'                    => $opp['name'],
                'slug'                    => $slug,
                'category_id'             => $category->id,
                'conducting_body'         => $opp['conducting_body'],
                'post_name'               => $opp['post_name'],
                'level'                   => $opp['level'],
                'description'             => "Apply for {$opp['post_name']} positions. Check official website for complete details.",
                'vacancy_count'           => $opp['vacancy_count'],
                'salary_range'            => $opp['salary_range'],
                'apply_url'               => $opp['apply_url'],
                'official_site'           => $opp['apply_url'],
                'apply_end'               => $opp['apply_end'],
                'eligibility_rules_json'  => ['min_age' => 18, 'max_age' => 35],
                'documents_required_json' => ['Aadhaar', 'Photo', 'Qualification Certificate'],
                'is_active'               => false,
                'is_featured'             => false,
            ]);

            $added++;
            $this->info("Added: {$opp['name']}");
        }

        $this->info("{$added} static opportunities added!");
    }
}