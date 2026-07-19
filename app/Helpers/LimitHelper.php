<?php

namespace App\Helpers;

use App\Models\SubscriptionPlan;
use App\Models\User;

class LimitHelper
{
    public static function check(User $user, string $limitKey): array
    {
        $limits = self::getUserLimits($user);
        $limit  = (int) ($limits[$limitKey] ?? 0);

        if ($limit === -1) {
            return ['allowed' => true, 'unlimited' => true, 'used' => 0, 'limit' => -1];
        }

        $used = self::getUsage($user, $limitKey);

        return [
            'allowed'   => $used < $limit,
            'unlimited' => false,
            'used'      => $used,
            'limit'     => $limit,
            'remaining' => max(0, $limit - $used),
        ];
    }

    public static function getUserLimits(User $user): array
    {
        $subscription = $user->subscription;

        if (!$subscription || !$subscription->isActive()) {
            $freePlan = SubscriptionPlan::where('slug', 'free')->first();
            return $freePlan?->limits_json ?? [
                'ai_messages'    => 20,
                'documents'      => 5,
                'family_members' => 2,
                'ocr_extractions'=> 2,
                'form_fills'     => 0,
            ];
        }

        return $subscription->plan->limits_json ?? [];
    }

    public static function getUsage(User $user, string $limitKey): int
    {
        return match($limitKey) {
            'ai_messages' => \App\Models\SathiConversation::where('user_id', $user->id)
                ->whereMonth('created_at', now()->month)
                ->count(),

            'documents' => \App\Models\UserDocument::where('user_id', $user->id)
                ->count(),

            'family_members' => \App\Models\FamilyMember::where('user_id', $user->id)
                ->count(),

            'ocr_extractions' => \App\Models\UserDocument::where('user_id', $user->id)
                ->where('ocr_status', 'completed')
                ->whereMonth('updated_at', now()->month)
                ->count(),

            'form_fills' => 0, // Track separately when form filler is used

            default => 0,
        };
    }

    public static function formatLimitResponse(string $feature, int $used, int $limit): array
    {
        return [
            'success'       => false,
            'limit_reached' => true,
            'message'       => "Aapki {$feature} limit is month khatam ho gayi. Upgrade karein unlimited access ke liye.",
            'used'          => $used,
            'limit'         => $limit,
            'upgrade_url'   => '/subscription',
        ];
    }
}