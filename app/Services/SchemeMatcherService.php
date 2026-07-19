<?php

namespace App\Services;

use App\Models\Scheme;
use App\Models\User;
use App\Models\UserSchemeMatch;
use Carbon\Carbon;

class SchemeMatcherService
{
    public function matchForUser(User $user): array
    {
        $profile = $user->profile;

        if (!$profile) {
            return ['matched' => 0, 'message' => 'Profile incomplete'];
        }

        $schemes  = Scheme::where('is_active', true)->get();
        $matched  = 0;

        foreach ($schemes as $scheme) {
            $rules  = $scheme->eligibility_rules_json ?? [];
            $status = $this->checkEligibility($profile, $rules);

            UserSchemeMatch::updateOrCreate(
                ['user_id' => $user->id, 'scheme_id' => $scheme->id],
                [
                    'eligibility_status' => $status,
                    'match_score'        => $status === 'eligible' ? 100 : 0,
                    'benefit_value'      => $scheme->benefit_value,
                    'matched_at'         => now(),
                ]
            );

            if ($status === 'eligible') $matched++;
        }

        return ['matched' => $matched, 'total' => $schemes->count()];
    }

    private function checkEligibility($profile, array $rules): string
    {
        // Age check
        if (isset($rules['min_age']) || isset($rules['max_age'])) {
            $age = $profile->dob
                ? Carbon::parse($profile->dob)->age
                : null;

            if (!$age) return 'needs_docs';

            if (isset($rules['min_age']) && $age < $rules['min_age']) return 'ineligible';
            if (isset($rules['max_age']) && $age > $rules['max_age']) return 'ineligible';
        }

        // Income check
        if (isset($rules['max_income'])) {
            if ($profile->annual_income > $rules['max_income']) return 'ineligible';
        }

        // BPL check
        if (isset($rules['bpl_status']) && $rules['bpl_status'] === true) {
            if (!$profile->bpl_status) return 'ineligible';
        }

        // Gender check
        if (isset($rules['gender'])) {
            if ($profile->gender !== $rules['gender']) return 'ineligible';
        }

        // Occupation check
        if (isset($rules['occupation'])) {
            if ($profile->occupation !== $rules['occupation']) return 'ineligible';
        }

        // Caste category check
        if (isset($rules['caste_category'])) {
            $allowed = (array) $rules['caste_category'];
            if (!in_array($profile->caste_category, $allowed)) return 'ineligible';
        }

        // Land check
        if (isset($rules['land_acres']['min'])) {
            if ($profile->land_acres < $rules['land_acres']['min']) return 'ineligible';
        }

        return 'eligible';
    }
}