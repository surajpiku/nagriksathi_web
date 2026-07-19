<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionLimitService;
use Closure;
use Illuminate\Http\Request;

class CheckAiMessageLimit
{
    public function __construct(private SubscriptionLimitService $limits) {}

    public function handle(Request $request, Closure $next)
    {
        $check = $this->limits->canSendAiMessage($request->user());
        if (!$check['allowed']) {
            return response()->json($this->limits->upgradeResponse('ai_messages_per_month', $check), 403);
        }
        return $next($request);
    }
}