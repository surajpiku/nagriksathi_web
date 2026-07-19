<?php

namespace App\Console\Commands;

use App\Models\Opportunity;
use App\Models\OpportunityCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ScrapeGovJobs extends Command
{
    protected $signature   = 'opportunities:scrape-rss';
    protected $description = 'Scrape government job RSS feeds and add/update opportunities';

    private array $feeds = [
        [
            'url'    => 'https://www.sarkariresult.com/feed/',
            'source' => 'SarkariResult',
            'level'  => 'central',
        ],
        [
            'url'    => 'https://www.sarkariresult.com/latestjob/feed/',
            'source' => 'SarkariResult Jobs',
            'level'  => 'central',
        ],
        [
            'url'    => 'https://www.freejobalert.com/feed/',
            'source' => 'FreeJobAlert',
            'level'  => 'central',
        ],
        [
            'url'    => 'https://www.sarkarijobfind.com/feed/',
            'source' => 'SarkariJobFind',
            'level'  => 'central',
        ],
        [
            'url'    => 'https://rojgarresult.com/feed/',
            'source' => 'RojgarResult',
            'level'  => 'central',
        ],
    ];

    private array $jobKeywords = [
        'recruitment', 'vacancy', 'job', 'bharti', 'notification',
        'apply', 'post', 'result', 'admit card', 'exam', 'syllabus',
        'answer key', 'merit list', 'cut off', 'selection list',
    ];

    public function handle()
    {
        $this->info('Scraping government job RSS feeds...');

        $defaultCategory = OpportunityCategory::first();
        $added   = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($this->feeds as $feed) {
            $this->info("Fetching: {$feed['source']}");

            try {
                $response = Http::timeout(30)
                    ->withHeaders(['User-Agent' => 'NagrikSathi/1.0 (nagriksathi.com)'])
                    ->get($feed['url']);

                if (!$response->successful()) {
                    $this->warn("Failed: {$feed['url']} ({$response->status()})");
                    continue;
                }

                $xml = @simplexml_load_string($response->body());
                if (!$xml) {
                    $this->warn("Invalid XML from: {$feed['url']}");
                    continue;
                }

                $items = $xml->channel->item ?? [];
                $count = 0;

                foreach ($items as $item) {
                    $name = trim((string) $item->title);
                    $link = trim((string) $item->link);
                    $desc = strip_tags(trim((string) $item->description));
                    $pubDate = trim((string) $item->pubDate);

                    if (empty($name) || strlen($name) < 5) continue;

                    // Only job-related items
                    $hasKeyword = false;
                    foreach ($this->jobKeywords as $kw) {
                        if (stripos($name, $kw) !== false || stripos($desc, $kw) !== false) {
                            $hasKeyword = true;
                            break;
                        }
                    }
                    if (!$hasKeyword) continue;

                    $slug = Str::slug($name);

                    // Extract vacancy count
                    preg_match('/(\d[\d,]+)\s*(post|vacancy|vacancies|seat|bharti)/i', $desc . ' ' . $name, $vacancyMatch);
                    $vacancyCount = isset($vacancyMatch[1]) ? (int) str_replace(',', '', $vacancyMatch[1]) : null;

                    // Extract last date — multiple patterns
                    $applyEnd = null;
                    $datePatterns = [
                        '/last date[:\s]+(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{2,4})/i',
                        '/apply.*?before[:\s]+(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{2,4})/i',
                        '/closing date[:\s]+(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{2,4})/i',
                        '/(\d{1,2}\s+(?:jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)[a-z]*\s+\d{4})/i',
                    ];
                    foreach ($datePatterns as $pattern) {
                        if (preg_match($pattern, $desc, $dateMatch)) {
                            try {
                                $applyEnd = \Carbon\Carbon::parse($dateMatch[1])->format('Y-m-d');
                                break;
                            } catch (\Exception $e) {}
                        }
                    }

                    // Detect level and state
                    $level     = 'central';
                    $stateCode = null;
                    $stateMap  = [
                        'Bihar'         => 'BR',
                        'Uttar Pradesh' => 'UP',
                        'UP'            => 'UP',
                        'Rajasthan'     => 'RJ',
                        'Madhya Pradesh'=> 'MP',
                        'Maharashtra'   => 'MH',
                        'Karnataka'     => 'KA',
                        'Tamil Nadu'    => 'TN',
                        'Gujarat'       => 'GJ',
                        'Punjab'        => 'PB',
                        'Haryana'       => 'HR',
                        'Jharkhand'     => 'JH',
                        'Uttarakhand'   => 'UK',
                    ];
                    foreach ($stateMap as $state => $code) {
                        if (stripos($name, $state) !== false) {
                            $level     = 'state';
                            $stateCode = $code;
                            break;
                        }
                    }

                    // Detect category
                    $category = $this->detectCategory($name) ?? $defaultCategory;

                    // Check if exists
                    $existing = Opportunity::where('slug', $slug)
                        ->orWhere('name', $name)
                        ->first();

                    if ($existing) {
                        // Update with fresh data from RSS
                        $updateData = [];

                        if ($applyEnd && $applyEnd !== $existing->apply_end?->format('Y-m-d')) {
                            $updateData['apply_end'] = $applyEnd;
                        }
                        if ($link && $link !== $existing->apply_url) {
                            $updateData['apply_url'] = $link;
                        }
                        if ($vacancyCount && $vacancyCount !== $existing->vacancy_count) {
                            $updateData['vacancy_count'] = $vacancyCount;
                        }
                        if ($desc && strlen($desc) > strlen($existing->description ?? '')) {
                            $updateData['description'] = Str::limit($desc, 500);
                        }

                        if (!empty($updateData)) {
                            $existing->update($updateData);
                            $updated++;
                            $this->line("Updated: {$name}");
                        } else {
                            $skipped++;
                        }
                        continue;
                    }

                    // Add new opportunity
                    Opportunity::create([
                        'name'                    => $name,
                        'slug'                    => $slug,
                        'category_id'             => $category->id,
                        'conducting_body'         => $feed['source'],
                        'post_name'               => 'Various Posts',
                        'level'                   => $level,
                        'state_code'              => $stateCode,
                        'description'             => Str::limit($desc, 500),
                        'vacancy_count'           => $vacancyCount,
                        'apply_url'               => $link,
                        'official_site'           => $link,
                        'apply_end'               => $applyEnd,
                        'eligibility_rules_json'  => [],
                        'documents_required_json' => ['Aadhaar', 'Photo'],
                        'is_active'               => true, // Auto-activate from RSS
                        'is_featured'             => false,
                    ]);

                    $added++;
                    $count++;
                    $this->info("Added: {$name}");
                }

                $this->line("  -> {$feed['source']}: {$count} new items");

            } catch (\Exception $e) {
                $this->warn("Error with {$feed['source']}: " . $e->getMessage());
            }
        }

        $this->info("=== RSS Scrape Complete ===");
        $this->info("Added:   {$added}");
        $this->info("Updated: {$updated}");
        $this->info("Skipped: {$skipped}");

        return 0;
    }

    private function detectCategory(string $name): ?OpportunityCategory
    {
        $map = [
            'Banking & Finance'    => ['bank', 'ibps', 'sbi', 'rbi', 'nabard', 'finance', 'rrb bank'],
            'Railways'             => ['railway', 'rrb', 'rail', 'loco'],
            'Defence & Police'     => ['police', 'army', 'navy', 'air force', 'crpf', 'bsf', 'cisf', 'defence', 'agniveer', 'military', 'paramilitary'],
            'Teaching & Education' => ['teacher', 'ctet', 'btet', 'tgt', 'pgt', 'education', 'school', 'college', 'university', 'lecturer'],
            'Medical & Health'     => ['nurse', 'doctor', 'aiims', 'esic', 'health', 'medical', 'nhm', 'hospital', 'pharmacist', 'staff nurse'],
            'Government Services'  => ['ssc', 'upsc', 'psc', 'ias', 'ips', 'civil service', 'clerk', 'inspector'],
            'Engineering & Tech'   => ['engineer', 'technical', 'junior engineer', 'je ', 'ae ', 'iti', 'diploma'],
            'Agriculture'          => ['agriculture', 'krishi', 'farmer', 'agricultural'],
        ];

        foreach ($map as $categoryName => $keywords) {
            foreach ($keywords as $kw) {
                if (stripos($name, $kw) !== false) {
                    $firstWord = explode(' ', $categoryName)[0];
                    $cat = OpportunityCategory::where('name', 'like', "%{$firstWord}%")->first();
                    if ($cat) return $cat;
                }
            }
        }

        return null;
    }
}