<?php

namespace App\Services;

use App\Models\User;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\DB;

class SubscriptionLimitService
{
    public function getLimits(User $user): array
    {
        $tier = $user->subscription_tier ?? 'free';
        $plan = SubscriptionPlan::where('slug', $tier)->first();
        if (!$plan) {
            return [
                'ai_messages_per_month'    => 20,
                'document_vault'           => 5,
                'family_members'           => 2,
                'ocr_per_month'            => 2,
                'form_filling_per_month'   => 0,
                'doc_generation_per_month' => 1,
                'human_sathi_sessions'     => 0,
                'daily_customer_queue'     => 20,
                'ai_toolkit'               => false,
            ];
        }
        return $plan->limits_json ?? [];
    }

    public function getLimit(User $user, string $key, mixed $default = 0): mixed
    {
        return $this->getLimits($user)[$key] ?? $default;
    }

    public function canSendAiMessage(User $user): array
    {
        $limit = $this->getLimit($user, 'ai_messages_per_month', 20);
        if ($limit === -1) return ['allowed' => true, 'used' => null, 'limit' => -1, 'remaining' => -1];
        $used = DB::table('sathi_conversations')->where('user_id', $user->id)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        return ['allowed' => $used < $limit, 'used' => $used, 'limit' => $limit, 'remaining' => max(0, $limit - $used)];
    }

    public function canUploadDocument(User $user): array
    {
        $limit = $this->getLimit($user, 'document_vault', 5);
        if ($limit === -1) return ['allowed' => true, 'used' => null, 'limit' => -1, 'remaining' => -1];
        $used = DB::table('user_documents')->where('user_id', $user->id)->count();
        return ['allowed' => $used < $limit, 'used' => $used, 'limit' => $limit, 'remaining' => max(0, $limit - $used)];
    }

    public function canAddFamilyMember(User $user): array
    {
        $limit = $this->getLimit($user, 'family_members', 2);
        if ($limit === -1) return ['allowed' => true, 'used' => null, 'limit' => -1, 'remaining' => -1];
        $used = DB::table('family_members')->where('user_id', $user->id)->count();
        return ['allowed' => $used < $limit, 'used' => $used, 'limit' => $limit, 'remaining' => max(0, $limit - $used)];
    }

    public function canUseOcr(User $user): array
    {
        $limit = $this->getLimit($user, 'ocr_per_month', 2);
        if ($limit === -1) return ['allowed' => true, 'used' => null, 'limit' => -1, 'remaining' => -1];
        $used = DB::table('user_documents')->where('user_id', $user->id)->whereNotNull('ocr_text')->whereMonth('updated_at', now()->month)->whereYear('updated_at', now()->year)->count();
        return ['allowed' => $used < $limit, 'used' => $used, 'limit' => $limit, 'remaining' => max(0, $limit - $used)];
    }

    public function canAddCustomerToday(User $user): array
    {
        $limit = $this->getLimit($user, 'daily_customer_queue', 20);
        if ($limit === -1) return ['allowed' => true, 'used' => null, 'limit' => -1, 'remaining' => -1];
        $agentId = DB::table('seva_mitras')->where('user_id', $user->id)->value('id');
        $used = $agentId ? DB::table('csc_customers')->where('seva_mitra_id', $agentId)->whereDate('created_at', now()->toDateString())->count() : 0;
        return ['allowed' => $used < $limit, 'used' => $used, 'limit' => $limit, 'remaining' => max(0, $limit - $used)];
    }

    public function hasAiToolkitAccess(User $user): bool
    {
        return (bool) $this->getLimit($user, 'ai_toolkit', false);
    }

    public function upgradeResponse(string $feature, array $check, string $userType = 'citizen'): array
    {
        $upgradePlan = $userType === 'seva_mitra' ? 'Sathi CSC Pro' : 'Sathi Plus';
        $upgradeSlug = $userType === 'seva_mitra' ? 'sathi-csc-pro' : 'sathi-plus';
        return [
            'success'       => false,
            'limit_reached' => true,
            'feature'       => $feature,
            'used'          => $check['used'] ?? null,
            'limit'         => $check['limit'] ?? null,
            'message'       => "Aapki {$feature} limit poori ho gayi. Upgrade karein.",
            'upgrade'       => [
                'plan'    => $upgradePlan,
                'slug'    => $upgradeSlug,
                'url'     => '/subscription',
                'message' => "Upgrade to {$upgradePlan} for more access",
            ],
        ];
    }
}