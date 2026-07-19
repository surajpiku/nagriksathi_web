<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionLimitService;
use Closure;
use Illuminate\Http\Request;

class CheckCscDailyQueue
{
    public function __construct(private SubscriptionLimitService $limits) {}

    public function handle(Request $request, Closure $next)
    {
        $check = $this->limits->canAddCustomerToday($request->user());
        if (!$check['allowed']) {
            return response()->json($this->limits->upgradeResponse('Daily customer queue', $check, 'seva_mitra'), 403);
        }
        return $next($request);
    }
}