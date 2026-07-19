<?php

namespace App\Console\Commands;

use App\Models\Scheme;
use App\Models\SchemeCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FetchNspScholarships extends Command
{
    protected $signature   = 'schemes:fetch-nsp {--count=40 : Number of scholarships to fetch}';
    protected $description = 'Fetch scholarships from National Scholarship Portal (scholarships.gov.in)';

    public function handle()
    {
        $this->info('=== Fetching NSP Scholarships ===');

        // Try direct NSP API first
        $apiData = $this->tryNspApi();

        if (!empty($apiData)) {
            $this->saveSchemes($apiData);
        } else {
            // Fallback to AI
            $this->fetchViaAI((int) $this->option('count'));
        }

        return 0;
    }

    private function tryNspApi(): array
    {
        try {
            $response = Http::timeout(15)
                ->withHeaders(['User-Agent' => 'NagrikSathi/1.0'])
                ->get('https://scholarships.gov.in/public/SchemeData/getSchemes', [
                    'pageNo'   => 1,
                    'pageSize' => 50,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['schemeList'])) {
                    $this->info('NSP API returned ' . count($data['schemeList']) . ' scholarships');
                    return $data['schemeList'];
                }
            }
        } catch (\Exception $e) {
            $this->warn('NSP API unavailable: ' . $e->getMessage());
        }

        return [];
    }

    private function fetchViaAI(int $count): void
    {
        $this->line('Using AI to fetch scholarship data...');

        $prompt = "List {$count} REAL scholarships from India's National Scholarship Portal (scholarships.gov.in). Include ALL types:

1. Central Scholarships (Ministry of Education):
   - Pre-Matric Scholarship for SC/ST/OBC
   - Post-Matric Scholarship for SC/ST/OBC
   - Merit-cum-Means Scholarship
   - National Fellowship
   - PM Scholarship Scheme

2. Minority Scholarships (Ministry of Minority Affairs):
   - Pre-Matric Scholarship for Minorities
   - Post-Matric Scholarship for Minorities
   - Merit-cum-Means for Minorities
   - Begum Hazrat Mahal Scholarship (Girls)
   - Maulana Azad Fellowship

3. OBC/EBC Scholarships (Ministry of Social Justice):
   - Post-Matric Scholarship for OBC
   - Top Class Education for OBC

4. Disabled Scholarships (DEPwD):
   - National Fellowship for Disabled
   - Pre-Matric Scholarship for Disabled

5. State Scholarships (Bihar focus):
   - Bihar Post Matric Scholarship
   - Chief Minister's Girl Child Scholarship
   - Bihar Student Credit Card

Return ONLY a valid JSON array:
[{
  \"name\": \"Post-Matric Scholarship for SC Students\",
  \"hindi_name\": \"अनुसूचित जाति के छात्रों के लिए पोस्ट मैट्रिक छात्रवृत्ति\",
  \"category\": \"Education\",
  \"ministry\": \"Ministry of Social Justice and Empowerment\",
  \"description\": \"Financial assistance to SC students studying at post-matriculation or post-secondary stage to enable them to complete their education.\",
  \"benefit_value\": 23000,
  \"benefit_type\": \"scholarship\",
  \"portal_url\": \"https://scholarships.gov.in\",
  \"form_url\": \"https://scholarships.gov.in/fresh/newstudentRegistration\",
  \"helpline\": \"0120-6619540\",
  \"is_central\": true,
  \"state\": \"central\",
  \"eligibility\": {
    \"caste_category\": [\"SC\"],
    \"education_level\": \"post_matric\",
    \"max_family_income\": 250000
  },
  \"documents_required\": [\"Aadhaar\", \"Caste Certificate\", \"Income Certificate\", \"Marksheet\", \"Bank Passbook\", \"Bonafide Certificate\"],
  \"deadline\": null
}]

Return ONLY JSON array, no markdown.";

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

            $start    = strpos($text, '[');
            $end      = strrpos($text, ']');
            if ($start === false || $end === false) {
                $this->error('No JSON found in response');
                return;
            }

            $jsonText     = substr($text, $start, $end - $start + 1);
            $jsonText     = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $jsonText);
            $scholarships = json_decode($jsonText, true, 512, JSON_INVALID_UTF8_IGNORE);

            if (!is_array($scholarships)) {
                $this->error('JSON parse failed: ' . json_last_error_msg());
                return;
            }

            $this->info('AI returned ' . count($scholarships) . ' scholarships');
            $this->saveSchemes($scholarships);

        } catch (\Exception $e) {
            $this->error('AI fetch failed: ' . $e->getMessage());
        }
    }

    private function saveSchemes(array $schemes): void
    {
        $educationCategory = SchemeCategory::where('name', 'like', '%Education%')->first()
            ?? SchemeCategory::first();

        $added   = 0;
        $skipped = 0;

        foreach ($schemes as $data) {
            // Handle NSP API format
            if (isset($data['schemeName'])) {
                $data = [
                    'name'               => $data['schemeName'],
                    'category'           => 'Education',
                    'ministry'           => $data['ministryName'] ?? 'Ministry of Education',
                    'description'        => $data['schemeDesc'] ?? '',
                    'benefit_value'      => 0,
                    'benefit_type'       => 'scholarship',
                    'portal_url'         => 'https://scholarships.gov.in',
                    'is_central'         => true,
                    'state'              => 'central',
                    'eligibility'        => [],
                    'documents_required' => ['Aadhaar', 'Marksheet', 'Bank Passbook'],
                ];
            }

            if (empty($data['name'])) continue;

            $slug = Str::slug($data['name']);

            if (Scheme::where('slug', $slug)->orWhere('name', $data['name'])->exists()) {
                $skipped++;
                continue;
            }

            try {
                Scheme::create([
                    'name'                   => $data['name'],
                    'hindi_name'             => $data['hindi_name'] ?? null,
                    'slug'                   => $slug,
                    'category_id'            => $educationCategory->id,
                    'ministry'               => $data['ministry'] ?? 'Ministry of Education',
                    'description'            => $data['description'] ?? '',
                    'benefit_value'          => is_numeric($data['benefit_value'] ?? null) ? (float) $data['benefit_value'] : 0,
                    'benefit_type'           => $data['benefit_type'] ?? 'scholarship',
                    'portal_url'             => $data['portal_url'] ?? 'https://scholarships.gov.in',
                    'form_url'               => $data['form_url'] ?? 'https://scholarships.gov.in/fresh/newstudentRegistration',
                    'helpline'               => $data['helpline'] ?? '0120-6619540',
                    'eligibility_rules_json' => $data['eligibility'] ?? [],
                    'documents_required_json'=> $data['documents_required'] ?? ['Aadhaar', 'Marksheet', 'Bank Passbook'],
                    'is_central'             => $data['is_central'] ?? true,
                    'state'                  => $data['state'] ?? 'central',
                    'deadline'               => $data['deadline'] ?? null,
                    'is_active'              => true,
                ]);
                $added++;
                $this->line("  [+] {$data['name']}");
            } catch (\Exception $e) {
                $this->warn("  [!] {$data['name']} — " . $e->getMessage());
            }
        }

        $this->info("=== NSP Fetch Complete: +{$added} new, -{$skipped} skipped ===");
    }
}