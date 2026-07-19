<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionLimitService;
use Closure;
use Illuminate\Http\Request;

class CheckDocumentLimit
{
    public function __construct(private SubscriptionLimitService $limits) {}

    public function handle(Request $request, Closure $next)
    {
        $check = $this->limits->canUploadDocument($request->user());
        if (!$check['allowed']) {
            return response()->json($this->limits->upgradeResponse('document_vault', $check), 403);
        }
        return $next($request);
    }
}