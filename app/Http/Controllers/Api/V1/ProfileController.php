<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use App\Models\Scheme;
use App\Models\UserSchemeMatch;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $profile = $request->user()->profile;
        return response()->json(['success' => true, 'profile' => $profile]);
    }

    public function update(Request $request)
    {
        $user    = $request->user();
        $profile = $user->profile;

        $validated = $request->validate([
            'name'            => 'nullable|string|max:100',
            'dob'             => 'nullable|date',
            'gender'          => 'nullable|in:male,female,other',
            'state'           => 'nullable|string|max:100',
            'state_code'      => 'nullable|string|max:5',
            'district'        => 'nullable|string|max:100',
            'district_code'   => 'nullable|string|max:10',
            'block'           => 'nullable|string|max:100',
            'village'         => 'nullable|string|max:100',
            'city'            => 'nullable|string|max:100',
            'pincode'         => 'nullable|digits:6',
            'area_type'       => 'nullable|in:rural,urban,semi_urban',
            'gram_panchayat'  => 'nullable|string|max:100',
            'locality_name'   => 'nullable|string|max:100',
            'ward_number'     => 'nullable|string|max:20',
            'occupation'      => 'nullable|string|max:100',
            'annual_income'   => 'nullable|numeric|min:0',
            'caste_category'  => 'nullable|in:general,obc,sc,st,ews',
            'bpl_status'      => 'nullable|boolean',
            'land_acres'      => 'nullable|numeric|min:0',
            'house_type'      => 'nullable|in:pucca,semi_pucca,kutcha',
            'has_vehicle'     => 'nullable|boolean',
        ]);

        // Calculate location depth
        $depth = 0;
        if (!empty($validated['state']))          $depth = 1;
        if (!empty($validated['district']))       $depth = 2;
        if (!empty($validated['block']))          $depth = 3;
        if (!empty($validated['gram_panchayat'])) $depth = 4;
        if (!empty($validated['village']))        $depth = 5;

        // Check profile completeness
        $requiredFields = ['name', 'dob', 'gender', 'state', 'district', 'occupation', 'annual_income', 'caste_category'];
        $filled = collect($requiredFields)->filter(fn($f) => !empty($validated[$f] ?? $profile?->$f))->count();
        $isComplete = $filled >= 6;

        $profileData = array_merge($validated, [
            'location_depth'    => $depth,
            'location_complete' => $depth >= 2,
            'is_complete'       => $isComplete,
            'location_updated_at' => now(),
        ]);

        if ($profile) {
            $profile->update($profileData);
        } else {
            $profile = UserProfile::create(array_merge($profileData, ['user_id' => $user->id]));
        }

        // Update user name if provided
        if (!empty($validated['name'])) {
            $user->update(['name' => $validated['name']]);
        }

        // Run scheme matcher
        $this->runSchemeMatcher($user, $profile->fresh());

        return response()->json([
            'success' => true,
            'profile' => $profile->fresh(),
            'message' => 'Profile updated successfully',
        ]);
    }

    private function runSchemeMatcher($user, $profile): void
    {
        try {
            $schemes = Scheme::where('is_active', true)->get();

            // Calculate age from DOB
            $age = $profile->dob ? \Carbon\Carbon::parse($profile->dob)->age : null;

            foreach ($schemes as $scheme) {
                $rules    = $scheme->eligibility_rules_json ?? [];
                $eligible = true;

                // Income check
                if (!empty($rules['max_income']) && $profile->annual_income > $rules['max_income']) {
                    $eligible = false;
                }

                // Age checks
                if ($age !== null) {
                    if (!empty($rules['min_age']) && $age < $rules['min_age']) $eligible = false;
                    if (!empty($rules['max_age']) && $age > $rules['max_age']) $eligible = false;
                }

                // Gender check
                if (!empty($rules['gender']) && $rules['gender'] !== $profile->gender) {
                    $eligible = false;
                }

                // BPL check
                if (!empty($rules['bpl_only']) && !$profile->bpl_status) {
                    $eligible = false;
                }

                // Occupation check
                if (!empty($rules['occupation']) && $rules['occupation'] !== $profile->occupation) {
                    $eligible = false;
                }

                // Caste category check
                if (!empty($rules['caste_category'])) {
                    $allowed = (array) $rules['caste_category'];
                    if (!in_array($profile->caste_category, $allowed)) {
                        $eligible = false;
                    }
                }

                // State check for state schemes
                if (!empty($rules['state']) && $profile->state_code !== $rules['state']) {
                    $eligible = false;
                }

                UserSchemeMatch::updateOrCreate(
                    ['user_id' => $user->id, 'scheme_id' => $scheme->id],
                    [
                        'eligibility_status' => $eligible ? 'eligible' : 'ineligible',
                        'match_score'        => $eligible ? 80 : 0,
                        'benefit_value'      => $scheme->benefit_value,
                    ]
                );
            }
        } catch (\Exception $e) {
            \Log::error('Scheme matcher error: ' . $e->getMessage());
        }
    }

    public function nagrikScore(Request $request)
    {
        $user    = $request->user();
        $profile = $user->profile;
        $score   = 0;

        // Profile completeness — 400 pts
        if ($profile) {
            $fields = ['name', 'dob', 'gender', 'state', 'district', 'occupation', 'annual_income', 'caste_category'];
            $filled = collect($fields)->filter(fn($f) => !empty($profile->$f))->count();
            $score += intval(($filled / count($fields)) * 400);

            // Location depth bonus — 100 pts
            $score += min(($profile->location_depth ?? 0) * 20, 100);
        }

        // Documents — 200 pts
        $docCount = $user->documents()->count();
        $score    += min($docCount * 40, 200);

        // Schemes claimed — 200 pts
        $claimed = $user->schemeMatches()->whereNotNull('claimed_at')->count();
        $score   += min($claimed * 50, 200);

        // Conversations — 100 pts
        $chats = $user->conversations()->count();
        $score += min($chats * 20, 100);

        // Family members — 100 pts
        $family = $user->familyMembers()->count();
        $score  += min($family * 50, 100);

        $score = min($score, 1000);
        $user->update(['nagrik_score' => $score]);

        return response()->json([
            'success'    => true,
            'score'      => $score,
            'breakdown'  => [
                'profile'  => $profile ? intval((collect(['name','dob','gender','state','district','occupation','annual_income','caste_category'])->filter(fn($f) => !empty($profile->$f))->count() / 8) * 400) : 0,
                'location' => min(($profile->location_depth ?? 0) * 20, 100),
                'documents'=> min($docCount * 40, 200),
                'claimed'  => min($claimed * 50, 200),
                'chats'    => min($chats * 20, 100),
                'family'   => min($family * 50, 100),
            ],
        ]);
    }

    public function updateLocation(Request $request)
    {
        $user    = $request->user();
        $profile = $user->profile;

        $validated = $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($profile) {
            $profile->update([
                'latitude'        => $validated['latitude'],
                'longitude'       => $validated['longitude'],
                'location_depth'  => max($profile->location_depth ?? 0, 6),
                'gps_captured_at' => now(),
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Location updated']);
    }
}