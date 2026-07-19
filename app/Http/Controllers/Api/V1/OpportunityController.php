<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Opportunity;
use App\Models\OpportunityCategory;
use App\Models\UserOpportunityMatch;
use Illuminate\Http\Request;

class OpportunityController extends Controller
{
    public function index(Request $request)
    {
        $query = Opportunity::with('category')->where('is_active', true);

        // Filter by category slug or id
        if ($request->category) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by level
        if ($request->level) {
            $query->where('level', $request->level);
        }

        // Filter by state
        if ($request->state_code) {
            $query->where('state_code', $request->state_code);
        }

        // Filter by featured
        if ($request->is_featured) {
            $query->where('is_featured', true);
        }

        // Search by name or conducting body
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('hindi_name', 'like', "%{$search}%")
                  ->orWhere('conducting_body', 'like', "%{$search}%")
                  ->orWhere('post_name', 'like', "%{$search}%");
            });
        }

        // Filter by exam date range
        if ($request->exam_after) {
            $query->where('exam_date', '>=', $request->exam_after);
        }

        // Only upcoming (not expired)
        if ($request->upcoming) {
            $query->where(function ($q) {
                $q->whereNull('apply_end')
                  ->orWhere('apply_end', '>=', now()->toDateString());
            });
        }

        // Sort
        $sortBy  = $request->sort_by ?? 'apply_end';
        $sortDir = $request->sort_dir ?? 'asc';
        $allowedSorts = ['apply_end', 'exam_date', 'created_at', 'vacancy_count'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderByRaw("{$sortBy} IS NULL, {$sortBy} {$sortDir}");
        } else {
            $query->latest();
        }

        $perPage = min((int) ($request->per_page ?? 20), 50);

        $paginated = $query->paginate($perPage);

        return response()->json([
            'success'     => true,
            'data'        => $paginated->items(),
            'pagination'  => [
                'total'        => $paginated->total(),
                'per_page'     => $paginated->perPage(),
                'current_page' => $paginated->currentPage(),
                'last_page'    => $paginated->lastPage(),
                'has_more'     => $paginated->hasMorePages(),
            ],
        ]);
    }

    public function matched(Request $request)
    {
        $user    = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'success' => true,
                'data'    => [],
                'message' => 'Complete your profile to see matched jobs',
            ]);
        }

        $query = Opportunity::with('category')->where('is_active', true);

        // Only upcoming
        $query->where(function ($q) {
            $q->whereNull('apply_end')
              ->orWhere('apply_end', '>=', now()->toDateString());
        });

        $opportunities = $query->get();
        $matches = [];

        foreach ($opportunities as $opp) {
            $rules  = $opp->eligibility_rules_json ?? [];
            $status = 'eligible';
            $score  = 100;

            // Age check
            $age = $profile->dob ? \Carbon\Carbon::parse($profile->dob)->age : null;
            if ($age) {
                if (isset($rules['min_age']) && $age < $rules['min_age']) { $status = 'ineligible'; continue; }
                if (isset($rules['max_age']) && $age > $rules['max_age']) { $status = 'age_bar'; $score -= 30; }
            }

            // Gender check
            if (isset($rules['gender']) && $rules['gender'] !== 'any' && $rules['gender'] !== $profile->gender) {
                continue;
            }

            // Income check
            if (isset($rules['max_income']) && $profile->annual_income > $rules['max_income']) {
                continue;
            }

            // Caste category check
            if (isset($rules['caste_category'])) {
                $allowed = (array) $rules['caste_category'];
                if (!empty($allowed) && !in_array($profile->caste_category, $allowed)) {
                    $score -= 20;
                }
            }

            // State preference — boost score if state matches
            if ($opp->state_code && $profile->state_code === $opp->state_code) {
                $score += 10;
            }

            UserOpportunityMatch::updateOrCreate(
                ['user_id' => $user->id, 'opportunity_id' => $opp->id],
                ['eligibility_status' => $status, 'match_score' => $score, 'matched_at' => now()]
            );

            $matches[] = [
                'opportunity'        => $opp,
                'eligibility_status' => $status,
                'match_score'        => min(100, $score),
            ];
        }

        // Sort by score
        usort($matches, fn($a, $b) => $b['match_score'] <=> $a['match_score']);

        return response()->json([
            'success' => true,
            'data'    => $matches,
            'total'   => count($matches),
        ]);
    }

    public function show(Opportunity $opportunity)
    {
        return response()->json([
            'success' => true,
            'data'    => $opportunity->load('category'),
        ]);
    }

    public function categories()
    {
        return response()->json([
            'success' => true,
            'data'    => OpportunityCategory::where('is_active', true)
                ->orderBy('display_order')
                ->get(),
        ]);
    }

    public function deadlines()
    {
        $upcoming = Opportunity::with('category')
            ->where('is_active', true)
            ->whereNotNull('apply_end')
            ->where('apply_end', '>=', now()->toDateString())
            ->where('apply_end', '<=', now()->addDays(30)->toDateString())
            ->orderBy('apply_end')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $upcoming,
            'count'   => $upcoming->count(),
        ]);
    }

    public function save(Request $request, Opportunity $opportunity)
    {
        $request->user()->savedOpportunities()->syncWithoutDetaching([$opportunity->id]);
        return response()->json(['success' => true, 'message' => 'Job saved successfully']);
    }

    public function markApplied(Request $request, Opportunity $opportunity)
    {
        UserOpportunityMatch::updateOrCreate(
            ['user_id' => $request->user()->id, 'opportunity_id' => $opportunity->id],
            ['has_applied' => true, 'applied_on' => now()]
        );

        return response()->json(['success' => true, 'message' => 'Marked as applied']);
    }
}