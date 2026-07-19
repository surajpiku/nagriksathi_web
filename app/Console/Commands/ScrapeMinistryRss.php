<?php

namespace App\Console\Commands;

use App\Models\Scheme;
use App\Models\SchemeCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ScrapeMinistryRss extends Command
{
    protected $signature   = 'schemes:scrape-ministry-rss';
    protected $description = 'Scrape PIB and ministry RSS feeds for new scheme announcements';

    private array $feeds = [
        [
            'url'      => 'https://pib.gov.in/RSSFeed.aspx?ModID=6&Lang=3',
            'ministry' => 'PIB - Welfare Schemes',
            'category' => 'Social welfare',
        ],
        [
            'url'      => 'https://pib.gov.in/RSSFeed.aspx?ModID=3&Lang=3',
            'ministry' => 'PIB - Agriculture',
            'category' => 'Agriculture',
        ],
        [
            'url'      => 'https://pib.gov.in/RSSFeed.aspx?ModID=11&Lang=3',
            'ministry' => 'PIB - Health',
            'category' => 'Health',
        ],
        [
            'url'      => 'https://pib.gov.in/RSSFeed.aspx?ModID=4&Lang=3',
            'ministry' => 'PIB - Education',
            'category' => 'Education',
        ],
        [
            'url'      => 'https://pib.gov.in/RSSFeed.aspx?ModID=2&Lang=3',
            'ministry' => 'PIB - Finance',
            'category' => 'Financial',
        ],
        [
            'url'      => 'https://pib.gov.in/RSSFeed.aspx?ModID=7&Lang=3',
            'ministry' => 'PIB - Housing & Urban',
            'category' => 'Housing',
        ],
        [
            'url'      => 'https://www.india.gov.in/rss/schemes.xml',
            'ministry' => 'India.gov.in',
            'category' => 'General',
        ],
    ];

    // Keywords that indicate a scheme announcement
    private array $schemeKeywords = [
        'scheme', 'yojana', 'mission', 'programme', 'program', 'initiative',
        'benefits', 'subsidy', 'assistance', 'relief', 'welfare', 'scholarship',
        'pension', 'insurance', 'loan', 'grant', 'allowance', 'stipend',
    ];

    public function handle()
    {
        $this->info('=== Scraping Ministry RSS Feeds ===');

        $allItems = [];

        foreach ($this->feeds as $feed) {
            $items = $this->fetchFeed($feed);
            $allItems = array_merge($allItems, $items);
            sleep(1);
        }

        if (empty($allItems)) {
            $this->warn('No items from RSS feeds — using AI to generate fresh scheme announcements');
            $this->fetchViaAI();
            return 0;
        }

        $this->info('Total RSS items collected: ' . count($allItems));

        // Filter scheme-related items
        $schemeItems = array_filter($allItems, function ($item) {
            $text = strtolower(($item['title'] ?? '') . ' ' . ($item['description'] ?? ''));
            foreach ($this->schemeKeywords as $kw) {
                if (str_contains($text, $kw)) return true;
            }
            return false;
        });

        $this->info('Scheme-related items: ' . count($schemeItems));

        if (count($schemeItems) < 5) {
            $this->warn('Too few scheme items — supplementing with AI');
            $this->fetchViaAI();
            return 0;
        }

        // Use AI to extract structured scheme data from RSS items
        $this->extractSchemesFromRss(array_values($schemeItems));

        return 0;
    }

    private function fetchFeed(array $feed): array
    {
        try {
            $response = Http::timeout(15)
                ->withHeaders(['User-Agent' => 'NagrikSathi/1.0 (nagriksathi.com)'])
                ->get($feed['url']);

            if (!$response->successful()) {
                $this->warn("Failed: {$feed['url']} ({$response->status()})");
                return [];
            }

            $xml = @simplexml_load_string($response->body());
            if (!$xml) {
                $this->warn("Invalid XML: {$feed['url']}");
                return [];
            }

            $items   = [];
            $channel = $xml->channel ?? $xml;

            foreach ($channel->item ?? [] as $item) {
                $items[] = [
                    'title'       => (string) $item->title,
                    'description' => strip_tags((string) $item->description),
                    'link'        => (string) $item->link,
                    'pubDate'     => (string) $item->pubDate,
                    'ministry'    => $feed['ministry'],
                    'category'    => $feed['category'],
                ];
            }

            $this->line("  {$feed['ministry']}: " . count($items) . " items");
            return $items;

        } catch (\Exception $e) {
            $this->warn("Error ({$feed['ministry']}): " . $e->getMessage());
            return [];
        }
    }

    private function extractSchemesFromRss(array $items): void
    {
        // Batch items for AI processing
        $batch     = array_slice($items, 0, 10);
        $itemsText = '';

        foreach ($batch as $i => $item) {
            $itemsText .= ($i + 1) . ". Title: {$item['title']}\n";
            $itemsText .= "   Ministry: {$item['ministry']}\n";
            $itemsText .= "   Description: " . Str::limit($item['description'], 200) . "\n\n";
        }

        $prompt = "Extract government scheme information from these PIB/Ministry announcements. For each announcement that describes a scheme/yojana/programme, create a structured record.

RSS ITEMS:
{$itemsText}

For each valid scheme announcement, return a JSON object. Skip items that are just events/meetings/awards.

Return ONLY a valid JSON array:
[{
  \"name\": \"Scheme Name\",
  \"hindi_name\": \"हिंदी नाम if mentioned\",
  \"category\": \"category name\",
  \"ministry\": \"ministry name\",
  \"description\": \"brief description under 100 words\",
  \"benefit_value\": 0,
  \"benefit_type\": \"cash/service/scholarship/insurance/subsidy\",
  \"portal_url\": \"URL if mentioned else empty string\",
  \"helpline\": \"helpline if mentioned else null\",
  \"is_central\": true,
  \"state\": \"central\",
  \"eligibility\": {},
  \"documents_required\": [\"Aadhaar\"]
}]

Return ONLY JSON array.";

        try {
            $response = Http::timeout(60)
                ->withHeaders([
                    'x-api-key'         => config('services.anthropic.key'),
                    'anthropic-version' => '2023-06-01',
                    'content-type'      => 'application/json',
                ])->post('https://api.anthropic.com/v1/messages', [
                    'model'      => 'claude-sonnet-4-5',
                    'max_tokens' => 4000,
                    'messages'   => [['role' => 'user', 'content' => $prompt]],
                ]);

            $text = $response->json('content.0.text') ?? '';
            $text = preg_replace('/```json\s*/i', '', $text);
            $text = preg_replace('/```\s*/i', '', $text);
            $text = trim($text);

            $start   = strpos($text, '[');
            $end     = strrpos($text, ']');
            if ($start === false || $end === false) return;

            $jsonText = substr($text, $start, $end - $start + 1);
            $schemes  = json_decode($jsonText, true, 512, JSON_INVALID_UTF8_IGNORE);

            if (!is_array($schemes)) return;

            $this->saveSchemes($schemes);

        } catch (\Exception $e) {
            $this->error('AI extraction failed: ' . $e->getMessage());
        }
    }

    private function fetchViaAI(): void
    {
        $month  = now()->format('F Y');
        $prompt = "List 20 recent government scheme announcements from India for {$month}. Focus on newly launched or updated schemes from PIB press releases.

Return ONLY a valid JSON array:
[{
  \"name\": \"Scheme Name\",
  \"hindi_name\": \"हिंदी नाम\",
  \"category\": \"category\",
  \"ministry\": \"Ministry Name\",
  \"description\": \"Description under 100 words\",
  \"benefit_value\": 0,
  \"benefit_type\": \"cash/service/scholarship\",
  \"portal_url\": \"\",
  \"helpline\": null,
  \"is_central\": true,
  \"state\": \"central\",
  \"eligibility\": {},
  \"documents_required\": [\"Aadhaar\"]
}]

Return ONLY JSON array.";

        try {
            $response = Http::timeout(120)
                ->withHeaders([
                    'x-api-key'         => config('services.anthropic.key'),
                    'anthropic-version' => '2023-06-01',
                    'content-type'      => 'application/json',
                ])->post('https://api.anthropic.com/v1/messages', [
                    'model'      => 'claude-sonnet-4-5',
                    'max_tokens' => 6000,
                    'messages'   => [['role' => 'user', 'content' => $prompt]],
                ]);

            $text = $response->json('content.0.text') ?? '';
            $text = preg_replace('/```json\s*/i', '', $text);
            $text = preg_replace('/```\s*/i', '', $text);
            $text = trim($text);

            $start   = strpos($text, '[');
            $end     = strrpos($text, ']');
            if ($start === false || $end === false) return;

            $jsonText = substr($text, $start, $end - $start + 1);
            $schemes  = json_decode($jsonText, true, 512, JSON_INVALID_UTF8_IGNORE);

            if (is_array($schemes)) {
                $this->saveSchemes($schemes);
            }
        } catch (\Exception $e) {
            $this->error('AI fetch failed: ' . $e->getMessage());
        }
    }

    private function saveSchemes(array $schemes): void
    {
        $added   = 0;
        $skipped = 0;

        foreach ($schemes as $data) {
            if (empty($data['name'])) continue;

            $slug = Str::slug($data['name']);

            if (Scheme::where('slug', $slug)->orWhere('name', $data['name'])->exists()) {
                $skipped++;
                continue;
            }

            $category = SchemeCategory::where('name', 'like', '%' . explode(' ', $data['category'] ?? 'Social')[0] . '%')
                ->first() ?? SchemeCategory::first();

            try {
                Scheme::create([
                    'name'                   => $data['name'],
                    'hindi_name'             => $data['hindi_name'] ?? null,
                    'slug'                   => $slug,
                    'category_id'            => $category->id,
                    'ministry'               => $data['ministry'] ?? 'Government of India',
                    'description'            => $data['description'] ?? '',
                    'benefit_value'          => is_numeric($data['benefit_value'] ?? null) ? (float) $data['benefit_value'] : 0,
                    'benefit_type'           => $data['benefit_type'] ?? 'service',
                    'portal_url'             => $data['portal_url'] ?? '',
                    'helpline'               => $data['helpline'] ?? null,
                    'eligibility_rules_json' => $data['eligibility'] ?? [],
                    'documents_required_json'=> $data['documents_required'] ?? ['Aadhaar'],
                    'is_central'             => $data['is_central'] ?? true,
                    'state'                  => $data['state'] ?? 'central',
                    'is_active'              => true,
                ]);
                $added++;
                $this->line("  [+] {$data['name']}");
            } catch (\Exception $e) {
                $this->warn("  [!] {$data['name']} — " . $e->getMessage());
            }
        }

        $this->info("=== RSS Scrape Complete: +{$added} new, -{$skipped} skipped ===");
    }
}