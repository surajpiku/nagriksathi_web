<?php

namespace App\Console\Commands;

use App\Models\Opportunity;
use App\Models\OpportunityCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AiDiscoverOpportunities extends Command
{
    protected $signature   = 'opportunities:ai-discover {--count=15 : Number of opportunities to discover}';
    protected $description = 'Use Claude AI to discover and update government job notifications';

    public function handle()
    {
        $this->info('Running AI opportunity discovery...');

        $existingNames = Opportunity::pluck('name')->take(30)->join(', ');
        $categories    = OpportunityCategory::pluck('name')->join(', ');
        $count         = $this->option('count');
        $currentMonth  = now()->format('F Y');

        $prompt = "You are a government job expert for India. For {$currentMonth}, provide {$count} current government job notifications.

TASK 1 - UPDATE existing jobs with latest info (dates, vacancies):
Existing jobs: {$existingNames}

TASK 2 - Add NEW jobs not in the above list. Focus on:
- SSC (CGL, CHSL, MTS, CPO, GD)
- UPSC (CSE, CDS, NDA, CAPF, EPFO)
- Railway (RRB NTPC, Group D, ALP, JE)
- Banking (SBI PO/Clerk, IBPS PO/Clerk/RRB)
- State PSC (Bihar BPSC, UP UPPSC, Rajasthan RPSC)
- Police (Bihar Police, UP Police, CRPF, BSF, CISF)
- Army/Navy/Air Force (Agniveer, Technical Entry)
- Teaching (CTET, BTET, NVS, KVS, TGT/PGT)
- Health (AIIMS, ESIC, NHM, UPPSC Medical)
- Bihar/UP district level jobs

Categories: {$categories}

Return ONLY a valid JSON array. No markdown, no explanation:
[
  {
    \"name\": \"SSC CGL 2026\",
    \"hindi_name\": \"एसएससी सीजीएल 2026\",
    \"category\": \"Government Services\",
    \"conducting_body\": \"Staff Selection Commission\",
    \"post_name\": \"Group B and C Posts\",
    \"level\": \"central\",
    \"state_code\": null,
    \"description\": \"SSC CGL 2026 notification for Group B and C posts in central ministries.\",
    \"vacancy_count\": 17000,
    \"salary_range\": \"Rs.25,500-81,100/month\",
    \"eligibility\": {\"min_age\": 18, \"max_age\": 32, \"min_education\": \"graduate\"},
    \"documents_required\": [\"Aadhaar\", \"Graduation Certificate\", \"Photo\"],
    \"apply_url\": \"https://ssc.gov.in\",
    \"official_site\": \"https://ssc.gov.in\",
    \"apply_start\": \"2026-03-01\",
    \"apply_end\": \"2026-06-30\",
    \"exam_date\": \"2026-09-15\"
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
                    'max_tokens' => 8000,
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

            $opportunities = json_decode($rawText, true);

            if (!is_array($opportunities)) {
                $this->error('Invalid JSON: ' . substr($rawText, 0, 300));
                return 1;
            }

            $added   = 0;
            $updated = 0;
            $skipped = 0;

            foreach ($opportunities as $data) {
                if (empty($data['name'])) continue;

                $slug     = Str::slug($data['name']);
                $existing = Opportunity::where('slug', $slug)
                    ->orWhere('name', $data['name'])
                    ->first();

                if ($existing) {
                    // Update with fresh AI data
                    $updateData = [];

                    if (!empty($data['apply_end'])) {
                        try {
                            $newDate = \Carbon\Carbon::parse($data['apply_end'])->format('Y-m-d');
                            if ($newDate !== $existing->apply_end?->format('Y-m-d')) {
                                $updateData['apply_end'] = $newDate;
                            }
                        } catch (\Exception $e) {}
                    }
                    if (!empty($data['exam_date'])) {
                        try {
                            $updateData['exam_date'] = \Carbon\Carbon::parse($data['exam_date'])->format('Y-m-d');
                        } catch (\Exception $e) {}
                    }
                    if (!empty($data['vacancy_count']) && (int)$data['vacancy_count'] !== (int)$existing->vacancy_count) {
                        $updateData['vacancy_count'] = $data['vacancy_count'];
                    }
                    if (!empty($data['salary_range']) && $data['salary_range'] !== $existing->salary_range) {
                        $updateData['salary_range'] = $data['salary_range'];
                    }
                    if (!empty($data['apply_url']) && $data['apply_url'] !== $existing->apply_url) {
                        $updateData['apply_url'] = $data['apply_url'];
                    }
                    if (!empty($data['description']) && strlen($data['description']) > strlen($existing->description ?? '')) {
                        $updateData['description'] = $data['description'];
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

                // Add new opportunity
                $categoryName = $data['category'] ?? '';
                $category     = OpportunityCategory::where('name', $categoryName)
                    ->orWhere('name', 'like', '%' . explode(' ', $categoryName)[0] . '%')
                    ->first() ?? OpportunityCategory::first();

                Opportunity::create([
                    'name'                    => $data['name'],
                    'hindi_name'              => $data['hindi_name'] ?? null,
                    'slug'                    => $slug,
                    'category_id'             => $category->id,
                    'conducting_body'         => $data['conducting_body'] ?? 'Government of India',
                    'post_name'               => $data['post_name'] ?? 'Various Posts',
                    'level'                   => $data['level'] ?? 'central',
                    'state_code'              => $data['state_code'] ?? null,
                    'description'             => $data['description'] ?? '',
                    'eligibility_rules_json'  => $data['eligibility'] ?? [],
                    'documents_required_json' => $data['documents_required'] ?? ['Aadhaar', 'Photo'],
                    'vacancy_count'           => $data['vacancy_count'] ?? null,
                    'salary_range'            => $data['salary_range'] ?? null,
                    'apply_url'               => $data['apply_url'] ?? null,
                    'official_site'           => $data['official_site'] ?? null,
                    'apply_start'             => $data['apply_start'] ?? null,
                    'apply_end'               => $data['apply_end'] ?? null,
                    'exam_date'               => $data['exam_date'] ?? null,
                    'is_active'               => false,
                    'is_featured'             => false,
                ]);

                $added++;
                $this->info("Drafted: {$data['name']}");
            }

            $this->info("=== AI Discovery Complete ===");
            $this->info("Added:   {$added} (review at /admin/opportunities)");
            $this->info("Updated: {$updated}");
            $this->info("Skipped: {$skipped}");

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}