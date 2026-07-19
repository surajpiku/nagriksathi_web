<?php

namespace App\Http\Middleware;

use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;
use Closure;
use Illuminate\Http\Request;

class CheckSubscriptionLimit 
{
    // Feature → limit key mapping
    private array $featureLimits = [
        'sathi.message'    => 'ai_messages',
        'document.store'   => 'documents',
        'family.store'     => 'family_members',
        'ocr.extract'      => 'ocr_extractions',
        'form.fill'        => 'form_fills',
    ];

    public function handle(Request $request, Closure $next, string $feature)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        // Get user's active plan limits
        $limits = $this->getUserLimits($user);
        $limitKey = $this->featureLimits[$feature] ?? null;

        if (!$limitKey || !isset($limits[$limitKey])) {
            return $next($request);
        }

        $limit = (int) $limits[$limitKey];

        // -1 means unlimited
        if ($limit === -1) {
            return $next($request);
        }

        // Count usage this month
        $usage = $this->getUsage($user, $feature);

        if ($usage >= $limit) {
            $planName = $user->subscription?->plan?->name ?? 'Free Sathi';
            return response()->json([
                'success'       => false,
                'limit_reached' => true,
                'message'       => "Aapki {$planName} plan ki {$feature} limit khatam ho gayi hai.",
                'used'          => $usage,
                'limit'         => $limit,
                'upgrade_url'   => '/subscription',
                'plan'          => $planName,
            ], 429);
        }

        // Add usage info to request
        $request->merge([
            '_limit_used'  => $usage,
            '_limit_total' => $limit,
        ]);

        return $next($request);
    }

    private function getUserLimits($user): array
    {
        $subscription = $user->subscription;

        if (!$subscription || !$subscription->isActive()) {
            // Return free plan limits
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

    private function getUsage($user, string $feature): int
    {
        return match($feature) {
            'sathi.message' => \App\Models\SathiConversation::where('user_id', $user->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),

            'document.store' => \App\Models\UserDocument::where('user_id', $user->id)
                ->count(),

            'family.store' => \App\Models\FamilyMember::where('user_id', $user->id)
                ->count(),

            'ocr.extract' => \App\Models\UserDocument::where('user_id', $user->id)
                ->where('ocr_status', 'completed')
                ->whereMonth('updated_at', now()->month)
                ->count(),

            default => 0,
        };
    }
}