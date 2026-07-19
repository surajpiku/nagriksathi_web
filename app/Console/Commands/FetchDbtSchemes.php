<?php

namespace App\Console\Commands;

use App\Models\Scheme;
use App\Models\SchemeCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FetchDbtSchemes extends Command
{
    protected $signature   = 'schemes:fetch-dbt {--count=30 : Number of schemes to fetch}';
    protected $description = 'Fetch Direct Benefit Transfer schemes from dbtbharat.gov.in via AI';

    public function handle()
    {
        $this->info('=== Fetching DBT Bharat Schemes ===');

        $count = (int) $this->option('count');

        $prompt = "List {$count} REAL government schemes from DBT Bharat portal (dbtbharat.gov.in). These are Direct Benefit Transfer schemes where money/benefits go directly to beneficiary's bank account via Aadhaar.

Include schemes across all ministries:
- PM Kisan (Agriculture)
- PM Matru Vandana (Women)
- Scholarship schemes (Education)
- Pension schemes (Social Welfare)
- LPG Subsidy (Energy)
- MGNREGA wages (Rural)
- Pradhan Mantri Awas Yojana (Housing)
- Jan Aushadhi (Health)
- Ujjwala Yojana (Energy)
- Fasal Bima (Agriculture)
- Shram Yogi Maandhan (Labour)
- National Social Assistance (Elderly/Disabled)

Return ONLY a valid JSON array:
[{
  \"name\": \"PM Kisan Samman Nidhi\",
  \"hindi_name\": \"प्रधानमंत्री किसान सम्मान निधि\",
  \"category\": \"Agriculture\",
  \"ministry\": \"Ministry of Agriculture and Farmers Welfare\",
  \"description\": \"Annual income support of Rs 6000 to small and marginal farmer families paid in 3 installments of Rs 2000 directly to bank account.\",
  \"benefit_value\": 6000,
  \"benefit_type\": \"cash\",
  \"portal_url\": \"https://pmkisan.gov.in\",
  \"form_url\": \"https://pmkisan.gov.in/RegistrationForm.aspx\",
  \"helpline\": \"155261\",
  \"is_central\": true,
  \"state\": \"central\",
  \"eligibility\": {
    \"occupation\": \"farmer\",
    \"max_land_acres\": 5,
    \"excluded\": [\"income_tax_payer\", \"government_employee\"]
  },
  \"documents_required\": [\"Aadhaar\", \"Bank Passbook\", \"Land Records\", \"Photo\"],
  \"deadline\": null
}]

Return ONLY JSON array, no markdown, no explanation.";

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
                $this->error('No JSON array found');
                return 1;
            }

            $jsonText = substr($text, $start, $end - $start + 1);
            $jsonText = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $jsonText);
            $schemes  = json_decode($jsonText, true, 512, JSON_INVALID_UTF8_IGNORE);

            if (!is_array($schemes)) {
                $this->error('JSON parse failed: ' . json_last_error_msg());
                return 1;
            }

            $this->info('AI returned ' . count($schemes) . ' DBT schemes');

            $added   = 0;
            $skipped = 0;

            foreach ($schemes as $data) {
                if (empty($data['name'])) continue;

                $slug = Str::slug($data['name']);

                if (Scheme::where('slug', $slug)->orWhere('name', $data['name'])->exists()) {
                    // Update documents_required if missing
                    $existing = Scheme::where('slug', $slug)->first();
                    if ($existing && empty($existing->documents_required_json) && !empty($data['documents_required'])) {
                        $existing->update(['documents_required_json' => $data['documents_required']]);
                        $this->line("  Updated docs: {$data['name']}");
                    }
                    $skipped++;
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
                        'benefit_type'           => $data['benefit_type'] ?? 'cash',
                        'portal_url'             => $data['portal_url'] ?? 'https://dbtbharat.gov.in',
                        'form_url'               => $data['form_url'] ?? null,
                        'helpline'               => $data['helpline'] ?? null,
                        'eligibility_rules_json' => $data['eligibility'] ?? [],
                        'documents_required_json'=> $data['documents_required'] ?? ['Aadhaar', 'Bank Passbook'],
                        'is_central'             => true,
                        'state'                  => 'central',
                        'deadline'               => null,
                        'is_active'              => true,
                    ]);
                    $added++;
                    $this->line("  [+] {$data['name']}");
                } catch (\Exception $e) {
                    $this->warn("  [!] {$data['name']} — " . $e->getMessage());
                }
            }

            $this->info("=== DBT Fetch Complete: +{$added} new, -{$skipped} skipped ===");

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function detectCategory(string $category, string $name): object
    {
        $map = [
            'Financial'  => ['financial', 'cash', 'loan', 'insurance', 'banking', 'subsidy'],
            'Health'     => ['health', 'medical', 'aushadhi', 'wellness', 'hospital'],
            'Education'  => ['education', 'scholarship', 'school', 'college', 'student'],
            'Housing'    => ['housing', 'awas', 'shelter', 'ujjwala', 'lpg', 'sanitation'],
            'Agriculture'=> ['agriculture', 'kisan', 'farmer', 'fasal', 'rural', 'bima'],
            'Women'      => ['women', 'mahila', 'matru', 'beti', 'child', 'vandana'],
            'Employment' => ['employment', 'skill', 'rozgar', 'mgnrega', 'maandhan'],
            'Elderly'    => ['pension', 'elderly', 'disabled', 'divyang', 'social assistance'],
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