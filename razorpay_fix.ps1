# NagrikSathi - Razorpay Fix Script
# Run from: E:\nagriksathi-api\
# Usage: .\razorpay_fix.ps1

Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "  NagrikSathi - Razorpay Diagnostic Fix  " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

# STEP 1: Check PaymentOrder model
Write-Host "[1/7] Checking PaymentOrder model..." -ForegroundColor Yellow

if (Test-Path "app\Models\PaymentOrder.php") {
    Write-Host "  OK PaymentOrder model exists" -ForegroundColor Green
} else {
    Write-Host "  MISSING - creating PaymentOrder model..." -ForegroundColor Red

    $modelContent = '<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentOrder extends Model
{
    protected $fillable = [
        ' + "'" + 'user_id' + "'" + ',
        ' + "'" + 'plan_id' + "'" + ',
        ' + "'" + 'razorpay_order_id' + "'" + ',
        ' + "'" + 'razorpay_payment_id' + "'" + ',
        ' + "'" + 'razorpay_signature' + "'" + ',
        ' + "'" + 'amount' + "'" + ',
        ' + "'" + 'currency' + "'" + ',
        ' + "'" + 'billing_cycle' + "'" + ',
        ' + "'" + 'status' + "'" + ',
        ' + "'" + 'paid_at' + "'" + ',
    ];

    protected $casts = [
        ' + "'" + 'paid_at' + "'" + ' => ' + "'" + 'datetime' + "'" + ',
        ' + "'" + 'amount' + "'" + '  => ' + "'" + 'decimal:2' + "'" + ',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, ' + "'" + 'plan_id' + "'" + ');
    }
}'

    [System.IO.File]::WriteAllText("$PWD\app\Models\PaymentOrder.php", $modelContent, [System.Text.UTF8Encoding]::new($false))
    Write-Host "  CREATED PaymentOrder model" -ForegroundColor Green
}

# STEP 2: Check payment_orders migration
Write-Host ""
Write-Host "[2/7] Checking payment_orders migration..." -ForegroundColor Yellow

$migrationExists = Get-ChildItem "database\migrations" | Where-Object { $_.Name -match "payment_orders" }
if ($migrationExists) {
    Write-Host "  OK migration exists: $($migrationExists.Name)" -ForegroundColor Green
} else {
    Write-Host "  MISSING - creating migration..." -ForegroundColor Red

    $timestamp = Get-Date -Format "yyyy_MM_dd_HHmmss"
    $migPath = "database\migrations\${timestamp}_create_payment_orders_table.php"

    $migContent = '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(' + "'" + 'payment_orders' + "'" + ', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger(' + "'" + 'user_id' + "'" + ');
            $table->unsignedBigInteger(' + "'" + 'plan_id' + "'" + ');
            $table->string(' + "'" + 'razorpay_order_id' + "'" + ')->unique();
            $table->string(' + "'" + 'razorpay_payment_id' + "'" + ')->nullable();
            $table->string(' + "'" + 'razorpay_signature' + "'" + ')->nullable();
            $table->decimal(' + "'" + 'amount' + "'" + ', 10, 2);
            $table->string(' + "'" + 'currency' + "'" + ', 3)->default(' + "'" + 'INR' + "'" + ');
            $table->enum(' + "'" + 'billing_cycle' + "'" + ', [' + "'" + 'monthly' + "'" + ', ' + "'" + 'yearly' + "'" + '])->default(' + "'" + 'monthly' + "'" + ');
            $table->enum(' + "'" + 'status' + "'" + ', [' + "'" + 'created' + "'" + ', ' + "'" + 'paid' + "'" + ', ' + "'" + 'failed' + "'" + '])->default(' + "'" + 'created' + "'" + ');
            $table->timestamp(' + "'" + 'paid_at' + "'" + ')->nullable();
            $table->timestamps();

            $table->foreign(' + "'" + 'user_id' + "'" + ')->references(' + "'" + 'id' + "'" + ')->on(' + "'" + 'users' + "'" + ')->onDelete(' + "'" + 'cascade' + "'" + ');
            $table->foreign(' + "'" + 'plan_id' + "'" + ')->references(' + "'" + 'id' + "'" + ')->on(' + "'" + 'subscription_plans' + "'" + ')->onDelete(' + "'" + 'restrict' + "'" + ');
            $table->index(' + "'" + 'razorpay_order_id' + "'" + ');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(' + "'" + 'payment_orders' + "'" + ');
    }
};'

    [System.IO.File]::WriteAllText("$PWD\$migPath", $migContent, [System.Text.UTF8Encoding]::new($false))
    Write-Host "  CREATED migration: $migPath" -ForegroundColor Green
}

# STEP 3: Check user_subscriptions has starts_at / ends_at
Write-Host ""
Write-Host "[3/7] Checking user_subscriptions columns..." -ForegroundColor Yellow

$subMig = Get-ChildItem "database\migrations" | Where-Object { $_.Name -match "user_subscriptions" } | Select-Object -First 1
if ($subMig) {
    $subContent = Get-Content $subMig.FullName -Raw
    if ($subContent -match "starts_at") {
        Write-Host "  OK starts_at / ends_at columns exist" -ForegroundColor Green
    } else {
        Write-Host "  MISSING starts_at/ends_at - creating fix migration..." -ForegroundColor Red

        $timestamp2 = Get-Date -Format "yyyy_MM_dd_HHmmss"
        Start-Sleep -Milliseconds 200
        $fixPath = "database\migrations\${timestamp2}_add_starts_ends_to_user_subscriptions.php"

        $fixContent = '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(' + "'" + 'user_subscriptions' + "'" + ', function (Blueprint $table) {
            if (!Schema::hasColumn(' + "'" + 'user_subscriptions' + "'" + ', ' + "'" + 'starts_at' + "'" + ')) {
                $table->timestamp(' + "'" + 'starts_at' + "'" + ')->nullable();
            }
            if (!Schema::hasColumn(' + "'" + 'user_subscriptions' + "'" + ', ' + "'" + 'ends_at' + "'" + ')) {
                $table->timestamp(' + "'" + 'ends_at' + "'" + ')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table(' + "'" + 'user_subscriptions' + "'" + ', function (Blueprint $table) {
            $table->dropColumn([' + "'" + 'starts_at' + "'" + ', ' + "'" + 'ends_at' + "'" + ']);
        });
    }
};'

        [System.IO.File]::WriteAllText("$PWD\$fixPath", $fixContent, [System.Text.UTF8Encoding]::new($false))
        Write-Host "  CREATED fix migration: $fixPath" -ForegroundColor Green
    }
} else {
    Write-Host "  WARNING: user_subscriptions migration not found" -ForegroundColor Yellow
}

# STEP 4: Replace RazorpayService.php
Write-Host ""
Write-Host "[4/7] Installing fixed RazorpayService.php..." -ForegroundColor Yellow

$svcContent = '<?php

namespace App\Services;

use App\Models\PaymentOrder;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Models\User;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RazorpayService
{
    private Api $api;

    public function __construct()
    {
        $this->api = new Api(
            config(' + "'" + 'services.razorpay.key_id' + "'" + '),
            config(' + "'" + 'services.razorpay.key_secret' + "'" + ')
        );
    }

    public function createOrder(int $userId, int $planId, string $billingCycle): array
    {
        $plan   = SubscriptionPlan::findOrFail($planId);
        $amount = $billingCycle === ' + "'" + 'yearly' + "'" + '
            ? $plan->price_yearly
            : $plan->price_monthly;

        if ($amount == 0) {
            return [' + "'" + 'success' + "'" + ' => false, ' + "'" + 'message' + "'" + ' => ' + "'" + 'This is a free plan' + "'" + '];
        }

        try {
            $order = $this->api->order->create([
                ' + "'" + 'amount' + "'" + '          => (int) ($amount * 100),
                ' + "'" + 'currency' + "'" + '        => ' + "'" + 'INR' + "'" + ',
                ' + "'" + 'receipt' + "'" + '         => ' + "'" + 'nagrik_' + "'" + ' . $userId . ' + "'" + '_' + "'" + ' . time(),
                ' + "'" + 'payment_capture' + "'" + ' => 1,
                ' + "'" + 'notes' + "'" + '           => [
                    ' + "'" + 'user_id' + "'" + '       => $userId,
                    ' + "'" + 'plan_id' + "'" + '       => $planId,
                    ' + "'" + 'billing_cycle' + "'" + ' => $billingCycle,
                    ' + "'" + 'plan_name' + "'" + '     => $plan->name,
                ],
            ]);

            PaymentOrder::create([
                ' + "'" + 'user_id' + "'" + '           => $userId,
                ' + "'" + 'plan_id' + "'" + '           => $planId,
                ' + "'" + 'razorpay_order_id' + "'" + ' => $order->id,
                ' + "'" + 'amount' + "'" + '            => $amount,
                ' + "'" + 'currency' + "'" + '          => ' + "'" + 'INR' + "'" + ',
                ' + "'" + 'billing_cycle' + "'" + '     => $billingCycle,
                ' + "'" + 'status' + "'" + '            => ' + "'" + 'created' + "'" + ',
            ]);

            Log::info(' + "'" + 'Razorpay order created' + "'" + ', [
                ' + "'" + 'order_id' + "'" + ' => $order->id,
                ' + "'" + 'user_id' + "'" + '  => $userId,
                ' + "'" + 'amount' + "'" + '   => $amount,
            ]);

            return [
                ' + "'" + 'success' + "'" + '       => true,
                ' + "'" + 'order_id' + "'" + '      => $order->id,
                ' + "'" + 'amount' + "'" + '        => (int) ($amount * 100),
                ' + "'" + 'currency' + "'" + '      => ' + "'" + 'INR' + "'" + ',
                ' + "'" + 'key_id' + "'" + '        => config(' + "'" + 'services.razorpay.key_id' + "'" + '),
                ' + "'" + 'plan_name' + "'" + '     => $plan->name,
                ' + "'" + 'billing_cycle' + "'" + ' => $billingCycle,
            ];

        } catch (\Exception $e) {
            Log::error(' + "'" + 'Razorpay createOrder failed' + "'" + ', [' + "'" + 'error' + "'" + ' => $e->getMessage(), ' + "'" + 'user_id' + "'" + ' => $userId]);
            return [' + "'" + 'success' + "'" + ' => false, ' + "'" + 'message' + "'" + ' => ' + "'" + 'Order creation failed: ' + "'" + ' . $e->getMessage()];
        }
    }

    public function verifyPayment(string $orderId, string $paymentId, string $signature): bool
    {
        $expectedSignature = hash_hmac(
            ' + "'" + 'sha256' + "'" + ',
            $orderId . ' + "'" + '|' + "'" + ' . $paymentId,
            config(' + "'" + 'services.razorpay.key_secret' + "'" + ')
        );

        $isValid = hash_equals($expectedSignature, $signature);

        Log::info(' + "'" + 'Razorpay signature verify' + "'" + ', [
            ' + "'" + 'order_id' + "'" + '   => $orderId,
            ' + "'" + 'payment_id' + "'" + ' => $paymentId,
            ' + "'" + 'is_valid' + "'" + '   => $isValid,
        ]);

        return $isValid;
    }

    public function activateSubscription(string $orderId, string $paymentId, string $signature): array
    {
        if (!$this->verifyPayment($orderId, $paymentId, $signature)) {
            return [' + "'" + 'success' + "'" + ' => false, ' + "'" + 'message' + "'" + ' => ' + "'" + 'Payment verification failed. Invalid signature.' + "'" + '];
        }

        $order = PaymentOrder::where(' + "'" + 'razorpay_order_id' + "'" + ', $orderId)->first();

        if (!$order) {
            Log::error(' + "'" + 'PaymentOrder not found after payment' + "'" + ', [' + "'" + 'razorpay_order_id' + "'" + ' => $orderId]);
            return [' + "'" + 'success' + "'" + ' => false, ' + "'" + 'message' + "'" + ' => ' + "'" + 'Order not found. Contact support with ID: ' + "'" + ' . $orderId];
        }

        if ($order->status === ' + "'" + 'paid' + "'" + ') {
            $sub = UserSubscription::where(' + "'" + 'user_id' + "'" + ', $order->user_id)->where(' + "'" + 'status' + "'" + ', ' + "'" + 'active' + "'" + ')->latest()->first();
            return [' + "'" + 'success' + "'" + ' => true, ' + "'" + 'message' + "'" + ' => ' + "'" + 'Already activated.' + "'" + ', ' + "'" + 'ends_at' + "'" + ' => $sub?->ends_at?->format(' + "'" + 'd M Y' + "'" + ')];
        }

        try {
            $endsAt = $order->billing_cycle === ' + "'" + 'yearly' + "'" + ' ? now()->addYear() : now()->addMonth();

            DB::transaction(function () use ($order, $paymentId, $signature, $endsAt) {
                $order->update([
                    ' + "'" + 'razorpay_payment_id' + "'" + ' => $paymentId,
                    ' + "'" + 'razorpay_signature' + "'" + '  => $signature,
                    ' + "'" + 'status' + "'" + '              => ' + "'" + 'paid' + "'" + ',
                    ' + "'" + 'paid_at' + "'" + '             => now(),
                ]);

                UserSubscription::where(' + "'" + 'user_id' + "'" + ', $order->user_id)
                    ->where(' + "'" + 'status' + "'" + ', ' + "'" + 'active' + "'" + ')
                    ->update([' + "'" + 'status' + "'" + ' => ' + "'" + 'cancelled' + "'" + ', ' + "'" + 'cancelled_at' + "'" + ' => now()]);

                UserSubscription::create([
                    ' + "'" + 'user_id' + "'" + '       => $order->user_id,
                    ' + "'" + 'plan_id' + "'" + '       => $order->plan_id,
                    ' + "'" + 'billing_cycle' + "'" + ' => $order->billing_cycle,
                    ' + "'" + 'status' + "'" + '        => ' + "'" + 'active' + "'" + ',
                    ' + "'" + 'starts_at' + "'" + '     => now(),
                    ' + "'" + 'ends_at' + "'" + '       => $endsAt,
                ]);

                User::where(' + "'" + 'id' + "'" + ', $order->user_id)->update([
                    ' + "'" + 'subscription_tier' + "'" + ' => optional($order->plan)->slug ?? ' + "'" + 'paid' + "'" + ',
                ]);
            });

            Log::info(' + "'" + 'Subscription activated' + "'" + ', [' + "'" + 'user_id' + "'" + ' => $order->user_id, ' + "'" + 'plan_id' + "'" + ' => $order->plan_id]);

            return [
                ' + "'" + 'success' + "'" + ' => true,
                ' + "'" + 'message' + "'" + ' => ' + "'" + 'Subscription activated!' + "'" + ',
                ' + "'" + 'ends_at' + "'" + ' => $endsAt->format(' + "'" + 'd M Y' + "'" + '),
            ];

        } catch (\Exception $e) {
            Log::error(' + "'" + 'Subscription activation failed' + "'" + ', [' + "'" + 'order_id' + "'" + ' => $orderId, ' + "'" + 'error' + "'" + ' => $e->getMessage()]);
            return [' + "'" + 'success' + "'" + ' => false, ' + "'" + 'message' + "'" + ' => ' + "'" + 'Activation failed: ' + "'" + ' . $e->getMessage()];
        }
    }

    public function verifyWebhookSignature(string $body, string $signature): bool
    {
        $expectedSignature = hash_hmac(' + "'" + 'sha256' + "'" + ', $body, config(' + "'" + 'services.razorpay.webhook_secret' + "'" + '));
        return hash_equals($expectedSignature, $signature);
    }
}'

[System.IO.File]::WriteAllText("$PWD\app\Services\RazorpayService.php", $svcContent, [System.Text.UTF8Encoding]::new($false))
Write-Host "  DONE RazorpayService.php replaced" -ForegroundColor Green

# STEP 5: Check UserSubscription model casts
Write-Host ""
Write-Host "[5/7] Checking UserSubscription model..." -ForegroundColor Yellow

if (Test-Path "app\Models\UserSubscription.php") {
    $usContent = Get-Content "app\Models\UserSubscription.php" -Raw
    if ($usContent -match "starts_at") {
        Write-Host "  OK UserSubscription model has starts_at cast" -ForegroundColor Green
    } else {
        Write-Host "  WARNING: starts_at not in casts - please manually add it" -ForegroundColor Yellow
        Write-Host "  Add this inside protected casts array:" -ForegroundColor Yellow
        Write-Host "    'starts_at' => 'datetime'," -ForegroundColor White
        Write-Host "    'ends_at'   => 'datetime'," -ForegroundColor White
    }
} else {
    Write-Host "  WARNING: UserSubscription model not found" -ForegroundColor Yellow
}

# STEP 6: Run migrations
Write-Host ""
Write-Host "[6/7] Running migrations..." -ForegroundColor Yellow
php artisan migrate --force
Write-Host "  DONE migrations complete" -ForegroundColor Green

# STEP 7: Clear caches
Write-Host ""
Write-Host "[7/7] Clearing caches..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan optimize:clear
Write-Host "  DONE caches cleared" -ForegroundColor Green

Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "  All done! Test steps:" -ForegroundColor Cyan
Write-Host "  1. Go to http://localhost:8000/subscription" -ForegroundColor White
Write-Host "  2. Pick a paid plan and pay" -ForegroundColor White
Write-Host "  3. Test card: 4111 1111 1111 1111" -ForegroundColor White
Write-Host "     CVV: 123  Expiry: any future date" -ForegroundColor White
Write-Host "  4. Check storage/logs/laravel.log on failure" -ForegroundColor White
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""