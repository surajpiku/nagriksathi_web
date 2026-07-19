# NagrikSathi - Subscription Limits Install Script
# Run from: E:\nagriksathi-api\
# Usage: .\subscription_limits_install.ps1

Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "  Subscription Limits - Full Install      " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

# STEP 1: Create SubscriptionLimitService
Write-Host "[1/8] Creating SubscriptionLimitService..." -ForegroundColor Yellow

$svc = '<?php

namespace App\Services;

use App\Models\User;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\DB;

class SubscriptionLimitService
{
    public function getLimits(User $user): array
    {
        $tier = $user->subscription_tier ?? ' + "'" + 'free' + "'" + ';
        $plan = SubscriptionPlan::where(' + "'" + 'slug' + "'" + ', $tier)->first();
        if (!$plan) {
            return [
                ' + "'" + 'ai_messages_per_month' + "'" + '    => 20,
                ' + "'" + 'document_vault' + "'" + '           => 5,
                ' + "'" + 'family_members' + "'" + '           => 2,
                ' + "'" + 'ocr_per_month' + "'" + '            => 2,
                ' + "'" + 'form_filling_per_month' + "'" + '   => 0,
                ' + "'" + 'doc_generation_per_month' + "'" + ' => 1,
                ' + "'" + 'human_sathi_sessions' + "'" + '     => 0,
                ' + "'" + 'daily_customer_queue' + "'" + '     => 20,
                ' + "'" + 'ai_toolkit' + "'" + '               => false,
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
        $limit = $this->getLimit($user, ' + "'" + 'ai_messages_per_month' + "'" + ', 20);
        if ($limit === -1) return [' + "'" + 'allowed' + "'" + ' => true, ' + "'" + 'used' + "'" + ' => null, ' + "'" + 'limit' + "'" + ' => -1, ' + "'" + 'remaining' + "'" + ' => -1];
        $used = DB::table(' + "'" + 'sathi_conversations' + "'" + ')->where(' + "'" + 'user_id' + "'" + ', $user->id)->whereMonth(' + "'" + 'created_at' + "'" + ', now()->month)->whereYear(' + "'" + 'created_at' + "'" + ', now()->year)->count();
        return [' + "'" + 'allowed' + "'" + ' => $used < $limit, ' + "'" + 'used' + "'" + ' => $used, ' + "'" + 'limit' + "'" + ' => $limit, ' + "'" + 'remaining' + "'" + ' => max(0, $limit - $used)];
    }

    public function canUploadDocument(User $user): array
    {
        $limit = $this->getLimit($user, ' + "'" + 'document_vault' + "'" + ', 5);
        if ($limit === -1) return [' + "'" + 'allowed' + "'" + ' => true, ' + "'" + 'used' + "'" + ' => null, ' + "'" + 'limit' + "'" + ' => -1, ' + "'" + 'remaining' + "'" + ' => -1];
        $used = DB::table(' + "'" + 'user_documents' + "'" + ')->where(' + "'" + 'user_id' + "'" + ', $user->id)->count();
        return [' + "'" + 'allowed' + "'" + ' => $used < $limit, ' + "'" + 'used' + "'" + ' => $used, ' + "'" + 'limit' + "'" + ' => $limit, ' + "'" + 'remaining' + "'" + ' => max(0, $limit - $used)];
    }

    public function canAddFamilyMember(User $user): array
    {
        $limit = $this->getLimit($user, ' + "'" + 'family_members' + "'" + ', 2);
        if ($limit === -1) return [' + "'" + 'allowed' + "'" + ' => true, ' + "'" + 'used' + "'" + ' => null, ' + "'" + 'limit' + "'" + ' => -1, ' + "'" + 'remaining' + "'" + ' => -1];
        $used = DB::table(' + "'" + 'family_members' + "'" + ')->where(' + "'" + 'user_id' + "'" + ', $user->id)->count();
        return [' + "'" + 'allowed' + "'" + ' => $used < $limit, ' + "'" + 'used' + "'" + ' => $used, ' + "'" + 'limit' + "'" + ' => $limit, ' + "'" + 'remaining' + "'" + ' => max(0, $limit - $used)];
    }

    public function canUseOcr(User $user): array
    {
        $limit = $this->getLimit($user, ' + "'" + 'ocr_per_month' + "'" + ', 2);
        if ($limit === -1) return [' + "'" + 'allowed' + "'" + ' => true, ' + "'" + 'used' + "'" + ' => null, ' + "'" + 'limit' + "'" + ' => -1, ' + "'" + 'remaining' + "'" + ' => -1];
        $used = DB::table(' + "'" + 'user_documents' + "'" + ')->where(' + "'" + 'user_id' + "'" + ', $user->id)->whereNotNull(' + "'" + 'ocr_text' + "'" + ')->whereMonth(' + "'" + 'updated_at' + "'" + ', now()->month)->whereYear(' + "'" + 'updated_at' + "'" + ', now()->year)->count();
        return [' + "'" + 'allowed' + "'" + ' => $used < $limit, ' + "'" + 'used' + "'" + ' => $used, ' + "'" + 'limit' + "'" + ' => $limit, ' + "'" + 'remaining' + "'" + ' => max(0, $limit - $used)];
    }

    public function canAddCustomerToday(User $user): array
    {
        $limit = $this->getLimit($user, ' + "'" + 'daily_customer_queue' + "'" + ', 20);
        if ($limit === -1) return [' + "'" + 'allowed' + "'" + ' => true, ' + "'" + 'used' + "'" + ' => null, ' + "'" + 'limit' + "'" + ' => -1, ' + "'" + 'remaining' + "'" + ' => -1];
        $agentId = DB::table(' + "'" + 'seva_mitras' + "'" + ')->where(' + "'" + 'user_id' + "'" + ', $user->id)->value(' + "'" + 'id' + "'" + ');
        $used = $agentId ? DB::table(' + "'" + 'csc_customers' + "'" + ')->where(' + "'" + 'seva_mitra_id' + "'" + ', $agentId)->whereDate(' + "'" + 'created_at' + "'" + ', now()->toDateString())->count() : 0;
        return [' + "'" + 'allowed' + "'" + ' => $used < $limit, ' + "'" + 'used' + "'" + ' => $used, ' + "'" + 'limit' + "'" + ' => $limit, ' + "'" + 'remaining' + "'" + ' => max(0, $limit - $used)];
    }

    public function hasAiToolkitAccess(User $user): bool
    {
        return (bool) $this->getLimit($user, ' + "'" + 'ai_toolkit' + "'" + ', false);
    }

    public function upgradeResponse(string $feature, array $check, string $userType = ' + "'" + 'citizen' + "'" + '): array
    {
        $upgradePlan = $userType === ' + "'" + 'seva_mitra' + "'" + ' ? ' + "'" + 'Sathi CSC Pro' + "'" + ' : ' + "'" + 'Sathi Plus' + "'" + ';
        $upgradeSlug = $userType === ' + "'" + 'seva_mitra' + "'" + ' ? ' + "'" + 'sathi-csc-pro' + "'" + ' : ' + "'" + 'sathi-plus' + "'" + ';
        return [
            ' + "'" + 'success' + "'" + '       => false,
            ' + "'" + 'limit_reached' + "'" + ' => true,
            ' + "'" + 'feature' + "'" + '       => $feature,
            ' + "'" + 'used' + "'" + '          => $check[' + "'" + 'used' + "'" + '] ?? null,
            ' + "'" + 'limit' + "'" + '         => $check[' + "'" + 'limit' + "'" + '] ?? null,
            ' + "'" + 'message' + "'" + '       => "Aapki {$feature} limit poori ho gayi. Upgrade karein.",
            ' + "'" + 'upgrade' + "'" + '       => [
                ' + "'" + 'plan' + "'" + '    => $upgradePlan,
                ' + "'" + 'slug' + "'" + '    => $upgradeSlug,
                ' + "'" + 'url' + "'" + '     => ' + "'" + '/subscription' + "'" + ',
                ' + "'" + 'message' + "'" + ' => "Upgrade to {$upgradePlan} for more access",
            ],
        ];
    }
}'

[System.IO.File]::WriteAllText("$PWD\app\Services\SubscriptionLimitService.php", $svc, [System.Text.UTF8Encoding]::new($false))
Write-Host "  DONE SubscriptionLimitService created" -ForegroundColor Green

# STEP 2: Create Middlewares
Write-Host ""
Write-Host "[2/8] Creating middleware files..." -ForegroundColor Yellow

$middlewares = @{
    "CheckAiMessageLimit" = "ai_messages_per_month";
    "CheckDocumentLimit" = "document_vault";
    "CheckFamilyMemberLimit" = "family_members";
}

foreach ($mw in $middlewares.GetEnumerator()) {
    $mwContent = '<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionLimitService;
use Closure;
use Illuminate\Http\Request;

class ' + $mw.Key + '
{
    public function __construct(private SubscriptionLimitService $limits) {}

    public function handle(Request $request, Closure $next)
    {
        $check = $this->limits->' + $(if ($mw.Key -eq "CheckAiMessageLimit") { "canSendAiMessage" } elseif ($mw.Key -eq "CheckDocumentLimit") { "canUploadDocument" } else { "canAddFamilyMember" }) + '($request->user());
        if (!$check[' + "'" + 'allowed' + "'" + ']) {
            return response()->json($this->limits->upgradeResponse(' + "'" + $mw.Value + "'" + ', $check), 403);
        }
        return $next($request);
    }
}'
    [System.IO.File]::WriteAllText("$PWD\app\Http\Middleware\$($mw.Key).php", $mwContent, [System.Text.UTF8Encoding]::new($false))
}

# CheckCscDailyQueue
$cscQueue = '<?php

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
        if (!$check[' + "'" + 'allowed' + "'" + ']) {
            return response()->json($this->limits->upgradeResponse(' + "'" + 'Daily customer queue' + "'" + ', $check, ' + "'" + 'seva_mitra' + "'" + '), 403);
        }
        return $next($request);
    }
}'
[System.IO.File]::WriteAllText("$PWD\app\Http\Middleware\CheckCscDailyQueue.php", $cscQueue, [System.Text.UTF8Encoding]::new($false))

# CheckAiToolkitAccess
$toolkit = '<?php

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
                ' + "'" + 'success' + "'" + '  => false,
                ' + "'" + 'limit_reached' + "'" + ' => true,
                ' + "'" + 'feature' + "'" + '  => ' + "'" + 'AI Toolkit' + "'" + ',
                ' + "'" + 'message' + "'" + '  => ' + "'" + 'AI Toolkit sirf Sathi CSC Pro plan mein available hai.' + "'" + ',
                ' + "'" + 'upgrade' + "'" + '  => [' + "'" + 'plan' + "'" + ' => ' + "'" + 'Sathi CSC Pro' + "'" + ', ' + "'" + 'url' + "'" + ' => ' + "'" + '/subscription' + "'" + '],
            ], 403);
        }
        return $next($request);
    }
}'
[System.IO.File]::WriteAllText("$PWD\app\Http\Middleware\CheckAiToolkitAccess.php", $toolkit, [System.Text.UTF8Encoding]::new($false))

Write-Host "  DONE 5 middleware files created" -ForegroundColor Green

# STEP 3: Register middleware in bootstrap/app.php
Write-Host ""
Write-Host "[3/8] Registering middleware aliases in bootstrap/app.php..." -ForegroundColor Yellow

$appBootstrap = Get-Content "bootstrap\app.php" -Raw

if ($appBootstrap -notmatch "CheckAiMessageLimit") {
    $middlewareBlock = '->withMiddleware(function (Middleware $middleware) {'
    $replacement = '->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            ' + "'" + 'check.ai.limit' + "'" + '      => \App\Http\Middleware\CheckAiMessageLimit::class,
            ' + "'" + 'check.doc.limit' + "'" + '     => \App\Http\Middleware\CheckDocumentLimit::class,
            ' + "'" + 'check.family.limit' + "'" + '  => \App\Http\Middleware\CheckFamilyMemberLimit::class,
            ' + "'" + 'check.csc.queue' + "'" + '     => \App\Http\Middleware\CheckCscDailyQueue::class,
            ' + "'" + 'check.ai.toolkit' + "'" + '    => \App\Http\Middleware\CheckAiToolkitAccess::class,
        ]);'

    $appBootstrap = $appBootstrap -replace [regex]::Escape($middlewareBlock), $replacement
    [System.IO.File]::WriteAllText("$PWD\bootstrap\app.php", $appBootstrap, [System.Text.UTF8Encoding]::new($false))
    Write-Host "  DONE middleware aliases registered" -ForegroundColor Green
} else {
    Write-Host "  SKIP already registered" -ForegroundColor Yellow
}

# STEP 4: Patch api.php routes with middleware
Write-Host ""
Write-Host "[4/8] Patching api.php with limit middleware..." -ForegroundColor Yellow

$apiContent = Get-Content "routes\api.php" -Raw

# Patch Sathi message route
if ($apiContent -notmatch "check.ai.limit") {
    $apiContent = $apiContent -replace "Route::post\('/sathi/message'", "Route::post('/sathi/message')->middleware('check.ai.limit') //"
    Write-Host "  PATCHED sathi/message route" -ForegroundColor Green
} else {
    Write-Host "  SKIP sathi/message already patched" -ForegroundColor Yellow
}

# Patch documents store route
if ($apiContent -notmatch "check.doc.limit") {
    $apiContent = $apiContent -replace "Route::post\('/documents'", "Route::post('/documents')->middleware('check.doc.limit') //"
    Write-Host "  PATCHED documents store route" -ForegroundColor Green
} else {
    Write-Host "  SKIP documents already patched" -ForegroundColor Yellow
}

# Patch family members store route
if ($apiContent -notmatch "check.family.limit") {
    $apiContent = $apiContent -replace "Route::post\('/family/members'", "Route::post('/family/members')->middleware('check.family.limit') //"
    Write-Host "  PATCHED family/members route" -ForegroundColor Green
} else {
    Write-Host "  SKIP family already patched" -ForegroundColor Yellow
}

# Patch CSC customer add route
if ($apiContent -notmatch "check.csc.queue") {
    $apiContent = $apiContent -replace "Route::post\('/customers'", "Route::post('/customers')->middleware('check.csc.queue') //"
    Write-Host "  PATCHED csc/customers route" -ForegroundColor Green
} else {
    Write-Host "  SKIP csc customers already patched" -ForegroundColor Yellow
}

[System.IO.File]::WriteAllText("$PWD\routes\api.php", $apiContent, [System.Text.UTF8Encoding]::new($false))

# STEP 5: Update limits_json in subscription_plans seeder values
Write-Host ""
Write-Host "[5/8] Checking subscription_plans limits_json in DB..." -ForegroundColor Yellow
php artisan tinker --execute="App\Models\SubscriptionPlan::all()->each(function(`$p) { echo `$p->slug . ': ' . json_encode(`$p->limits_json) . PHP_EOL; });"

# STEP 6: Update SubscriptionPlan model
Write-Host ""
Write-Host "[6/8] Checking SubscriptionPlan model..." -ForegroundColor Yellow

if (Test-Path "app\Models\SubscriptionPlan.php") {
    $planModel = Get-Content "app\Models\SubscriptionPlan.php" -Raw
    if ($planModel -notmatch "limits_json.*array") {
        Write-Host "  WARNING: Check that limits_json is cast as array in SubscriptionPlan model" -ForegroundColor Yellow
        Write-Host "  Add this to casts: 'limits_json' => 'array'" -ForegroundColor White
    } else {
        Write-Host "  OK limits_json cast exists" -ForegroundColor Green
    }
}

# STEP 7: Clear caches
Write-Host ""
Write-Host "[7/8] Clearing caches..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan optimize:clear
Write-Host "  DONE" -ForegroundColor Green

# STEP 8: Test limit service
Write-Host ""
Write-Host "[8/8] Testing SubscriptionLimitService..." -ForegroundColor Yellow
php artisan tinker --execute="
`$svc = new App\Services\SubscriptionLimitService();
`$user = App\Models\User::find(2);
echo 'Tier: ' . `$user->subscription_tier . PHP_EOL;
echo 'AI limit: ' . json_encode(`$svc->canSendAiMessage(`$user)) . PHP_EOL;
echo 'Doc limit: ' . json_encode(`$svc->canUploadDocument(`$user)) . PHP_EOL;
echo 'Family limit: ' . json_encode(`$svc->canAddFamilyMember(`$user)) . PHP_EOL;
"

Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "  Done! Subscription limits are now wired." -ForegroundColor Cyan
Write-Host ""
Write-Host "  Middleware applied to:" -ForegroundColor White
Write-Host "  POST /v1/sathi/message     -> check.ai.limit" -ForegroundColor White
Write-Host "  POST /v1/documents         -> check.doc.limit" -ForegroundColor White
Write-Host "  POST /v1/family/members    -> check.family.limit" -ForegroundColor White
Write-Host "  POST /v1/csc/customers     -> check.csc.queue" -ForegroundColor White
Write-Host "  CSC Toolkit AI tools       -> check.ai.toolkit" -ForegroundColor White
Write-Host ""
Write-Host "  Next: Go to /admin and check Subscription Plans" -ForegroundColor Yellow
Write-Host "  Update limits_json for each plan if needed." -ForegroundColor Yellow
Write-Host "==========================================" -ForegroundColor Cyan