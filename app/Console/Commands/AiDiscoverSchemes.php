<?php

namespace App\Console\Commands;

use App\Models\Scheme;
use App\Models\SchemeCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AiDiscoverSchemes extends Command
{
    protected $signature   = 'schemes:ai-discover {--count=10 : Number of schemes to discover}';
    protected $description = 'Use Claude AI to discover and update government schemes';

    public function handle()
    {
        $this->info('Running AI scheme discovery...');

        $existingNames = Scheme::pluck('name')->take(30)->join(', ');
        $categories    = SchemeCategory::pluck('name')->join(', ');
        $count         = $this->option('count');
        $currentMonth  = now()->format('F Y');

        $prompt = "You are a government scheme expert for India. For {$currentMonth}, provide {$count} current government schemes.

TASK 1 - UPDATE existing schemes with latest info (benefit amounts, portal URLs, helplines):
Existing schemes: {$existingNames}

TASK 2 - Add NEW schemes not in the above list. Focus on:
- Central government schemes (PM, Ministry schemes)
- State schemes (Bihar, UP, Rajasthan, MP)
- Financial assistance schemes
- Health schemes (Ayushman, Jan Aushadhi)
- Education scholarships
- Housing schemes (PM Awas)
- Agriculture schemes (PM Kisan, Fasal Bima)
- Women empowerment schemes
- Youth/skill development
- SC/ST/OBC welfare schemes
- Bihar state specific schemes

Categories available: {$categories}

Return ONLY a valid JSON array, no markdown, no explanation:
[
  {
    \"name\": \"PM Jan Dhan Yojana\",
    \"hindi_name\": \"प्रधानमंत्री जन धन योजना\",
    \"category\": \"Financial Benefits\",
    \"ministry\": \"Ministry of Finance\",
    \"description\": \"Zero balance bank account with RuPay debit card and accident insurance.\",
    \"benefit_value\": 200000,
    \"benefit_type\": \"insurance\",
    \"portal_url\": \"https://pmjdy.gov.in\",
    \"helpline\": \"1800-11-0001\",
    \"is_central\": true,
    \"state\": \"central\",
    \"eligibility\": {
      \"min_age\": 10,
      \"requires_bank_account\": false
    },
    \"documents_required\": [\"Aadhaar\", \"Photo\"],
    \"apply_url\": \"https://pmjdy.gov.in/scheme\",
    \"deadline\": null
  }
]";

        try {
            $response = Http::timeout(120)
                ->withHeaders([
                    'x-api-key'         => config('services.anthropic.key'),
                    'anthropic-version' => '2023-06-01',
                    'content-type'      => 'application/json',
                ])->post('https://api.anthropic.com/v1/messages', [
                    'model'      => 'claude-sonnet-4-5',
                    'max_tokens' => 4000,
                    'messages'   => [['role' => 'user', 'content' => $prompt]],
                ]);

            $rawText = $response->json('content.0.text') ?? '';
            $this->line('Raw: ' . substr($rawText, 0, 80) . '...');

            // Clean JSON
            $rawText = preg_replace('/```json\s*/i', '', $rawText);
            $rawText = preg_replace('/```\s*/i', '', $rawText);
            $rawText = trim($rawText);

            // Extract JSON array
            if (preg_match('/\[.*\]/s', $rawText, $matches)) {
                $rawText = $matches[0];
            }

            $schemes = json_decode($rawText, true);

            if (!is_array($schemes)) {
                $this->error('Invalid JSON: ' . substr($rawText, 0, 300));
                return 1;
            }

            $added   = 0;
            $updated = 0;
            $skipped = 0;

            foreach ($schemes as $data) {
                if (empty($data['name'])) continue;

                $slug     = Str::slug($data['name']);
                $existing = Scheme::where('slug', $slug)
                    ->orWhere('name', $data['name'])
                    ->first();

                if ($existing) {
                    // Update with fresh AI data
                    $updateData = [];

                    if (!empty($data['benefit_value']) && (float)$data['benefit_value'] !== (float)$existing->benefit_value) {
                        $updateData['benefit_value'] = $data['benefit_value'];
                    }
                    if (!empty($data['portal_url']) && $data['portal_url'] !== $existing->portal_url) {
                        $updateData['portal_url'] = $data['portal_url'];
                    }
                    if (!empty($data['helpline']) && $data['helpline'] !== $existing->helpline) {
                        $updateData['helpline'] = $data['helpline'];
                    }
                    if (!empty($data['description']) && strlen($data['description']) > strlen($existing->description ?? '')) {
                        $updateData['description'] = $data['description'];
                    }
                    if (!empty($data['eligibility'])) {
                        $updateData['eligibility_rules_json'] = $data['eligibility'];
                    }
                    if (!empty($data['deadline'])) {
                        $updateData['deadline'] = $data['deadline'];
                    }

                    if (!empty($updateData)) {
                        $existing->update($updateData);
                        $updated++;
                        $this->line("Updated: {$data['name']} — " . implode(', ', array_keys($updateData)));
                    } else {
                        $skipped++;
                    }
                    continue;
                }

                // Add new scheme
                $categoryName = $data['category'] ?? '';
                $category     = SchemeCategory::where('name', $categoryName)
                    ->orWhere('name', 'like', '%' . explode(' ', $categoryName)[0] . '%')
                    ->first() ?? SchemeCategory::first();

                Scheme::create([
                    'name'                   => $data['name'],
                    'hindi_name'             => $data['hindi_name'] ?? null,
                    'slug'                   => $slug,
                    'category_id'            => $category->id,
                    'ministry'               => $data['ministry'] ?? 'Government of India',
                    'description'            => $data['description'] ?? '',
                    'benefit_value'          => $data['benefit_value'] ?? 0,
                    'benefit_type'           => $data['benefit_type'] ?? 'service',
                    'portal_url'             => $data['portal_url'] ?? '',
                    'helpline'               => $data['helpline'] ?? null,
                    'eligibility_rules_json' => $data['eligibility'] ?? [],
                    'is_central'             => $data['is_central'] ?? true,
                    'state'                  => $data['state'] ?? 'central',
                    'deadline'               => $data['deadline'] ?? null,
                    'is_active'              => false,
                ]);

                $added++;
                $this->info("Drafted: {$data['name']}");
            }

            $this->info("=== AI Scheme Discovery Complete ===");
            $this->info("Added:   {$added} (review at /admin/schemes)");
            $this->info("Updated: {$updated}");
            $this->info("Skipped: {$skipped}");

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}