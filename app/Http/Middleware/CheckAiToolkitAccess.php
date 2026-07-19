<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionLimitService;
use Closure;
use Illuminate\Http\Request;

class CheckAiToolkitAccess
{
    public function __construct(private SubscriptionLimitService $limits) {}

    public function handle(Request $request, Closure $next)
    {
        if (!$this->limits->hasAiToolkitAccess($request->user())) {
            return response()->json([
                'success'  => false,
                'limit_reached' => true,
                'feature'  => 'AI Toolkit',
                'message'  => 'AI Toolkit sirf Sathi CSC Pro plan mein available hai.',
                'upgrade'  => ['plan' => 'Sathi CSC Pro', 'url' => '/subscription'],
            ], 403);
        }
        return $next($request);
    }
}