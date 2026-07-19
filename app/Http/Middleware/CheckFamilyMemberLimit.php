<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionLimitService;
use Closure;
use Illuminate\Http\Request;

class CheckFamilyMemberLimit
{
    public function __construct(private SubscriptionLimitService $limits) {}

    public function handle(Request $request, Closure $next)
    {
        $check = $this->limits->canAddFamilyMember($request->user());
        if (!$check['allowed']) {
            return response()->json($this->limits->upgradeResponse('family_members', $check), 403);
        }
        return $next($request);
    }
}