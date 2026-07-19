<?php

namespace App\Console\Commands;

use App\Models\Scheme;
use App\Models\SchemeCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FetchMySchemeData extends Command
{
    protected $signature   = 'schemes:fetch-myscheme {--limit=50 : Number of schemes to fetch} {--offset=0 : Offset for pagination} {--all : Fetch all schemes (multiple pages)}';
    protected $description = 'Fetch schemes from MyScheme.gov.in official API';

    private string $apiBase = 'https://api.myscheme.gov.in/search/v4/schemes';

    public function handle()
    {
        $this->info('=== Fetching from MyScheme.gov.in API ===');

        if ($this->option('all')) {
            $this->fetchAll();
        } else {
            $this->fetchPage((int) $this->option('offset'), (int) $this->option('limit'));
        }

        return 0;
    }

    private function fetchAll(): void
    {
        $offset  = 0;
        $limit   = 50;
        $total   = 0;
        $page    = 1;

        do {
            $this->info("--- Page {$page} (offset: {$offset}) ---");
            $count = $this->fetchPage($offset, $limit);
            $total += $count;
            $offset += $limit;
            $page++;
            sleep(2); // Rate limit
        } while ($count === $limit); // Stop when fewer results than limit

        $this->info("=== Total processed: {$total} ===");
    }

    private function fetchPage(int $offset, int $limit): int
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'NagrikSathi/1.0 (nagriksathi.com)',
                    'Accept'     => 'application/json',
                ])
                ->get($this->apiBase, [
                    'keyword'  => '',
                    'lang'     => 'en',
                    'from'     => $offset,
                    'size'     => $limit,
                ]);

            if (!$response->successful()) {
                $this->warn("API error: {$response->status()} — trying fallback");
                return $this->fetchViaAI($offset, $limit);
            }

            $data    = $response->json();
            $schemes = $data['data']['hits'] ?? $data['hits'] ?? $data['data'] ?? [];

            if (empty($schemes)) {
                $this->warn('No schemes in response — trying fallback AI fetch');
                return $this->fetchViaAI($offset, $limit);
            }

            $this->info('API returned ' . count($schemes) . ' schemes');
            return $this->saveSchemes($schemes, 'myscheme_api');

        } catch (\Exception $e) {
            $this->warn('API failed: ' . $e->getMessage() . ' — using AI fallback');
            return $this->fetchViaAI($offset, $limit);
        }
    }

    private function fetchViaAI(int $offset, int $limit): int
    {
        $this->line('  Running AI fallback for MyScheme data...');

        $categories = [
            'Agriculture,Rural & Environment',
            'Banking,Financial Services and Insurance',
            'Business & Entrepreneurship',
            'Education & Learning',
            'Health & Wellness',
            'Housing & Shelter',
            'Public Safety,Law & Justice',
            'Science,IT & Communications',
            'Skills & Employment',
            'Social welfare & Empowerment',
            'Sports & Culture',
            'Transport & Infrastructure',
            'Travel & Tourism',
            'Utility & Sanitation',
            'Women and Child',
        ];

        $categoryStr = implode(', ', $categories);
        $page        = ($offset / $limit) + 1;

        $prompt = "You are a government scheme expert for India. List {$limit} REAL central government schemes from MyScheme.gov.in (page {$page}).

Focus on schemes from these categories: {$categoryStr}

Include schemes for all beneficiary types: SC, ST, OBC, EWS, Women, Farmers, Students, Disabled, Elderly, BPL families.

Return ONLY a valid JSON array:
[{
  \"name\": \"PM Kisan Samman Nidhi\",
  \"hindi_name\": \"प्रधानमंत्री किसान सम्मान निधि\",
  \"category\": \"Agriculture\",
  \"ministry\": \"Ministry of Agriculture and Farmers Welfare\",
  \"description\": \"Income support of Rs 6000 per year to farmer families in three installments of Rs 2000 each.\",
  \"benefit_value\": 6000,
  \"benefit_type\": \"cash\",
  \"portal_url\": \"https://pmkisan.gov.in\",
  \"form_url\": \"https://pmkisan.gov.in/RegistrationForm.aspx\",
  \"helpline\": \"155261\",
  \"is_central\": true,
  \"eligibility\": {\"occupation\": \"farmer\", \"land_required\": true},
  \"documents_required\": [\"Aadhaar\", \"Bank Passbook\", \"Land Records\"],
  \"benefit_type\": \"cash\",
  \"deadline\": null
}]

Return ONLY JSON, no explanation, no markdown.";

        try {
            $response = Http::timeout(120)
                ->withHeaders([
                    'x-api-key'         => config('services.anthropic.key'),
                    'anthropic-version' => '2023-06-01',
                    'content-type'      => 'application/json',
                ])->post('https://api.anthropic.com/v1/messages', [
                    'model'      => 'claude-sonnet-4-5',
                    'max_tokens' => 8000,
                    'messages'   => [['role' => 'user', 'content' => $prompt]],
                ]);

            $text = $response->json('content.0.text') ?? '';
            $text = preg_replace('/```json\s*/i', '', $text);
            $text = preg_replace('/```\s*/i', '', $text);
            $text = trim($text);

            $start = strpos($text, '[');
            $end   = strrpos($text, ']');
            if ($start === false || $end === false) {
                $this->error('No JSON array in AI response');
                return 0;
            }

            $jsonText = substr($text, $start, $end - $start + 1);
            $jsonText = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $jsonText);
            $schemes  = json_decode($jsonText, true, 512, JSON_INVALID_UTF8_IGNORE);

            if (!is_array($schemes)) {
                $this->error('JSON parse failed: ' . json_last_error_msg());
                return 0;
            }

            $this->info('AI returned ' . count($schemes) . ' schemes');
            return $this->saveSchemes($schemes, 'ai_myscheme');

        } catch (\Exception $e) {
            $this->error('AI fallback failed: ' . $e->getMessage());
            return 0;
        }
    }

    private function saveSchemes(array $schemes, string $source): int
    {
        $added   = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($schemes as $raw) {
            // Handle both API format and AI format
            $data = $this->normalizeScheme($raw);

            if (empty($data['name'])) continue;

            $slug     = Str::slug($data['name']);
            $existing = Scheme::where('slug', $slug)
                ->orWhere('name', $data['name'])
                ->first();

            if ($existing) {
                // Update if we have better data
                $updates = [];
                if (!empty($data['portal_url']) && empty($existing->portal_url)) {
                    $updates['portal_url'] = $data['portal_url'];
                }
                if (!empty($data['helpline']) && empty($existing->helpline)) {
                    $updates['helpline'] = $data['helpline'];
                }
                if (!empty($data['benefit_value']) && empty($existing->benefit_value)) {
                    $updates['benefit_value'] = $data['benefit_value'];
                }
                if (!empty($data['documents_required']) && empty($existing->documents_required_json)) {
                    $updates['documents_required_json'] = $data['documents_required'];
                }

                if (!empty($updates)) {
                    $existing->update($updates);
                    $updated++;
                } else {
                    $skipped++;
                }
                continue;
            }

            $category = $this->detectCategory($data['category'] ?? '', $data['name']);

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
                    'form_url'               => $data['form_url'] ?? null,
                    'helpline'               => $data['helpline'] ?? null,
                    'eligibility_rules_json' => $data['eligibility'] ?? [],
                    'documents_required_json'=> $data['documents_required'] ?? [],
                    'is_central'             => $data['is_central'] ?? true,
                    'state'                  => $data['state'] ?? 'central',
                    'deadline'               => $data['deadline'] ?? null,
                    'is_active'              => true,
                ]);
                $added++;
                $this->line("  [+] {$data['name']}");
            } catch (\Exception $e) {
                $this->warn("  [!] Failed: {$data['name']} — " . $e->getMessage());
            }
        }

        $this->info("  Saved: +{$added} new, ~{$updated} updated, -{$skipped} skipped");
        return $added + $updated;
    }

    private function normalizeScheme(array $raw): array
    {
        // Handle MyScheme.gov.in API response format
        if (isset($raw['_source'])) {
            $s = $raw['_source'];
            return [
                'name'               => $s['schemeName'] ?? $s['name'] ?? '',
                'hindi_name'         => $s['schemeNameHi'] ?? $s['hindi_name'] ?? null,
                'category'           => $s['schemeCategory'] ?? $s['category'] ?? '',
                'ministry'           => $s['ministry'] ?? $s['nodal_ministry'] ?? 'Government of India',
                'description'        => $s['briefDescription'] ?? $s['description'] ?? '',
                'benefit_value'      => $s['benefitAmount'] ?? 0,
                'benefit_type'       => $s['benefitType'] ?? 'service',
                'portal_url'         => $s['schemeUrl'] ?? $s['portal_url'] ?? '',
                'form_url'           => $s['applicationUrl'] ?? null,
                'helpline'           => $s['helplineNo'] ?? null,
                'eligibility'        => $s['eligibility'] ?? [],
                'documents_required' => $s['documents'] ?? [],
                'is_central'         => true,
                'state'              => 'central',
                'deadline'           => null,
            ];
        }

        // Already in our AI format
        return $raw;
    }

    private function detectCategory(string $category, string $name): object
    {
        $map = [
            'Financial'  => ['financial', 'cash', 'loan', 'credit', 'subsidy', 'insurance', 'banking'],
            'Health'     => ['health', 'medical', 'hospital', 'ayushman', 'wellness', 'nurse'],
            'Education'  => ['education', 'scholarship', 'school', 'college', 'learning', 'student'],
            'Housing'    => ['housing', 'awas', 'shelter', 'home', 'sanitation', 'utility'],
            'Agriculture'=> ['agriculture', 'kisan', 'farmer', 'rural', 'environment', 'fasal'],
            'Women'      => ['women', 'mahila', 'kanya', 'beti', 'child', 'matru', 'balika'],
            'Employment' => ['employment', 'skill', 'job', 'rozgar', 'business', 'msme', 'startup'],
            'Elderly'    => ['pension', 'elderly', 'disabled', 'divyang', 'welfare', 'empowerment'],
        ];

        $searchText = strtolower($category . ' ' . $name);

        foreach ($map as $keyword => $terms) {
            foreach ($terms as $term) {
                if (str_contains($searchText, $term)) {
                    $cat = SchemeCategory::where('name', 'like', "%{$keyword}%")->first();
                    if ($cat) return $cat;
                }
            }
        }

        return SchemeCategory::first();
    }
}