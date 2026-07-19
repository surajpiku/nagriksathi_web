<?php

namespace App\Console\Commands;

use App\Models\Scheme;
use App\Models\SchemeCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FetchMySchemeState extends Command
{
    protected $signature   = 'schemes:fetch-state {--state=Bihar : State name} {--all : Fetch all states}';
    protected $description = 'Fetch state-wise schemes from MyScheme.gov.in and AI';

    private array $states = [
        'Bihar', 'Uttar Pradesh', 'Rajasthan', 'Madhya Pradesh', 'Maharashtra',
        'West Bengal', 'Gujarat', 'Karnataka', 'Tamil Nadu', 'Andhra Pradesh',
        'Telangana', 'Odisha', 'Jharkhand', 'Haryana', 'Punjab',
        'Chhattisgarh', 'Assam', 'Kerala', 'Uttarakhand', 'Himachal Pradesh',
        'Delhi', 'Jammu and Kashmir', 'Goa', 'Manipur', 'Meghalaya',
        'Nagaland', 'Tripura', 'Arunachal Pradesh', 'Mizoram', 'Sikkim',
        'Puducherry', 'Chandigarh', 'Ladakh',
    ];

    private array $stateCodes = [
        'Bihar' => 'BR', 'Uttar Pradesh' => 'UP', 'Rajasthan' => 'RJ',
        'Madhya Pradesh' => 'MP', 'Maharashtra' => 'MH', 'West Bengal' => 'WB',
        'Gujarat' => 'GJ', 'Karnataka' => 'KA', 'Tamil Nadu' => 'TN',
        'Andhra Pradesh' => 'AP', 'Telangana' => 'TS', 'Odisha' => 'OR',
        'Jharkhand' => 'JH', 'Haryana' => 'HR', 'Punjab' => 'PB',
        'Chhattisgarh' => 'CG', 'Assam' => 'AS', 'Kerala' => 'KL',
        'Uttarakhand' => 'UK', 'Himachal Pradesh' => 'HP', 'Delhi' => 'DL',
        'Jammu and Kashmir' => 'JK', 'Goa' => 'GA', 'Manipur' => 'MN',
        'Meghalaya' => 'ML', 'Nagaland' => 'NL', 'Tripura' => 'TR',
        'Arunachal Pradesh' => 'AR', 'Mizoram' => 'MZ', 'Sikkim' => 'SK',
        'Puducherry' => 'PY', 'Chandigarh' => 'CH', 'Ladakh' => 'LA',
    ];

    public function handle()
    {
        if ($this->option('all')) {
            foreach ($this->states as $state) {
                $this->fetchForState($state);
                sleep(2); // Rate limit
            }
        } else {
            $this->fetchForState($this->option('state'));
        }
        return 0;
    }

    private function fetchForState(string $state)
    {
        $this->info("=== Fetching schemes for: {$state} ===");
        $stateCode = $this->stateCodes[$state] ?? $state;
        $added = 0;
        $updated = 0;

        // Try MyScheme.gov.in first
        $schemes = $this->fetchFromMyScheme($state, $stateCode);

        // Always also run AI discovery for gaps
        $aiSchemes = $this->fetchViaAI($state, $stateCode);
        $schemes   = array_merge($schemes, $aiSchemes);

        foreach ($schemes as $data) {
            if (empty($data['name'])) continue;

            $slug     = Str::slug($data['name']);
            $existing = Scheme::where('slug', $slug)->orWhere('name', $data['name'])->first();

            if ($existing) {
                $updateData = [];
                if (!empty($data['portal_url']) && $data['portal_url'] !== $existing->portal_url) $updateData['portal_url'] = $data['portal_url'];
                if (!empty($data['benefit_value']) && (float)$data['benefit_value'] > 0)          $updateData['benefit_value'] = $data['benefit_value'];
                if (!empty($data['helpline']))                                                      $updateData['helpline'] = $data['helpline'];
                if (!empty($updateData)) { $existing->update($updateData); $updated++; }
                continue;
            }

            $category = $this->detectCategory($data['category'] ?? '', $data['name']);

            Scheme::create([
                'name'                   => $data['name'],
                'hindi_name'             => $data['hindi_name'] ?? null,
                'slug'                   => $slug,
                'category_id'            => $category->id,
                'ministry'               => $data['ministry'] ?? 'Government of ' . $state,
                'description'            => $data['description'] ?? '',
                'benefit_value'          => $data['benefit_value'] ?? 0,
                'benefit_type'           => $data['benefit_type'] ?? 'service',
                'portal_url'             => $data['portal_url'] ?? '',
                'helpline'               => $data['helpline'] ?? null,
                'eligibility_rules_json' => $data['eligibility'] ?? [],
                'is_central'             => false,
                'state'                  => $stateCode,
                'is_active'              => true,
            ]);

            $added++;
            $this->line("  Added: {$data['name']}");
        }

        $this->info("  Result: Added {$added}, Updated {$updated}");
    }

    private function fetchFromMyScheme(string $state, string $stateCode): array
    {
        $this->line("  Trying MyScheme.gov.in API...");
        $results = [];

        $urls = [
            "https://api.myscheme.gov.in/search/v4/schemes?lang=en&q=&state=" . urlencode($state) . "&pageSize=100&page=1",
            "https://www.myscheme.gov.in/api/v1/schemes?stateCode={$stateCode}&limit=100",
        ];

        foreach ($urls as $url) {
            try {
                $response = Http::timeout(30)
                    ->withHeaders(['Accept' => 'application/json', 'User-Agent' => 'NagrikSathi/1.0'])
                    ->get($url);

                if ($response->successful()) {
                    $data    = $response->json();
                    $schemes = $data['data']['schemes'] ?? $data['schemes'] ?? $data['data'] ?? [];

                    if (!empty($schemes) && is_array($schemes)) {
                        $this->info("  MyScheme returned " . count($schemes) . " schemes!");
                        foreach ($schemes as $s) {
                            $results[] = [
                                'name'         => $s['schemeName'] ?? $s['name'] ?? '',
                                'hindi_name'   => $s['schemeNameHindi'] ?? null,
                                'description'  => strip_tags($s['briefDescription'] ?? $s['description'] ?? ''),
                                'ministry'     => $s['ministryName'] ?? $s['nodal_ministry'] ?? 'State Government',
                                'benefit_value'=> $s['benefitAmount'] ?? 0,
                                'benefit_type' => 'service',
                                'portal_url'   => $s['schemeUrl'] ?? $s['url'] ?? '',
                                'helpline'     => $s['helplineNumber'] ?? null,
                                'category'     => $s['category'] ?? '',
                                'eligibility'  => [],
                            ];
                        }
                        break;
                    }
                }
            } catch (\Exception $e) {
                $this->warn("  URL failed: " . $e->getMessage());
            }
        }

        return $results;
    }

    private function fetchViaAI(string $state, string $stateCode): array
    {
        $this->line("  Running AI discovery for {$state}...");

        $existingCount = Scheme::where('state', $stateCode)->count();
        $this->line("  Existing {$state} schemes in DB: {$existingCount}");

        try {
            $prompt = "List 20 government welfare schemes SPECIFICALLY for {$state} state in India.


Include these types:
1. Chief Minister schemes (Mukhyamantri Yojana)
2. State agriculture subsidies
3. State education scholarships  
4. State women empowerment schemes
5. State housing schemes
6. State health schemes
7. SC/ST/OBC/EWS specific state schemes
8. State skill/employment schemes
9. State pension schemes
10. State farmer welfare schemes

Return ONLY a valid JSON array with NO markdown:
[{
  \"name\": \"Scheme Name\",
  \"hindi_name\": \"हिंदी नाम\",
  \"category\": \"Women & Child\",
  \"ministry\": \"Department Name, {$state}\",
  \"description\": \"Brief description under 100 words.\",
  \"benefit_value\": 50000,
  \"benefit_type\": \"cash\",
  \"portal_url\": \"https://example.gov.in\",
  \"helpline\": \"1800-xxx-xxxx\",
  \"eligibility\": {\"state\": \"{$stateCode}\"}
}]";
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
$this->line("  AI response length: " . strlen($text));
$this->line("  AI raw response: " . substr($text, 0, 200));
$this->line("  AI raw response: " . substr($text, 0, 200)); // ADD THIS LINE
// Ensure UTF-8 encoding
$text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');

// Clean JSON
$text = preg_replace('/```json\s*/i', '', $text);
$text = preg_replace('/```\s*/i', '', $text);
$text = trim($text);

$start = strpos($text, '[');
$end   = strrpos($text, ']');

if ($start !== false && $end !== false) {
    $text = substr($text, $start, $end - $start + 1);
}

// Try json_decode
$schemes = json_decode($text, true, 512, JSON_INVALID_UTF8_IGNORE);

if (!is_array($schemes)) {
    // Try fixing common JSON issues
    $text    = preg_replace('/[\x00-\x1F\x7F]/u', '', $text);
    $schemes = json_decode($text, true);
}

if (!is_array($schemes)) {
    $this->error("  JSON parse error: " . json_last_error_msg());
    return [];
}

        } catch (\Exception $e) {
            $this->error("  AI error: " . $e->getMessage());
        }

        return [];
    }

    private function detectCategory(string $category, string $name): object
    {
        $map = [
            'Financial Benefits' => ['financial', 'cash', 'loan', 'credit', 'mudra', 'subsidy', 'arthik'],
            'Health Services'    => ['health', 'medical', 'hospital', 'ayushman', 'swasthya', 'aushadhi'],
            'Education'          => ['education', 'scholarship', 'school', 'college', 'vidya', 'shiksha', 'student'],
            'Housing'            => ['housing', 'awas', 'ghar', 'home', 'flat'],
            'Agriculture'        => ['agriculture', 'kisan', 'farmer', 'krishi', 'fasal', 'beej', 'kheti'],
            'Women & Child'      => ['women', 'mahila', 'kanya', 'beti', 'matru', 'child', 'balika'],
            'Employment & Skill' => ['employment', 'rozgar', 'skill', 'kaushal', 'job', 'training'],
            'Elderly & Disabled' => ['pension', 'elderly', 'disabled', 'vridha', 'divyang', 'handicap'],
            'Business & MSME'    => ['business', 'msme', 'enterprise', 'udyam', 'startup'],
        ];

        $searchText = strtolower($category . ' ' . $name);

        foreach ($map as $categoryName => $keywords) {
            foreach ($keywords as $kw) {
                if (str_contains($searchText, $kw)) {
                    $cat = SchemeCategory::where('name', 'like', "%{$categoryName}%")
                        ->orWhere('name', 'like', '%' . explode(' ', $categoryName)[0] . '%')
                        ->first();
                    if ($cat) return $cat;
                }
            }
        }

        return SchemeCategory::first();
    }
}