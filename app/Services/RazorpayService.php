<?php

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
            config('services.razorpay.key_id'),
            config('services.razorpay.key_secret')
        );
    }

    public function createOrder(int $userId, int $planId, string $billingCycle): array
    {
        $plan   = SubscriptionPlan::findOrFail($planId);
        $amount = $billingCycle === 'yearly'
            ? $plan->price_yearly
            : $plan->price_monthly;

        if ($amount == 0) {
            return ['success' => false, 'message' => 'This is a free plan'];
        }

        try {
            $order = $this->api->order->create([
                'amount'          => (int) ($amount * 100),
                'currency'        => 'INR',
                'receipt'         => 'nagrik_' . $userId . '_' . time(),
                'payment_capture' => 1,
                'notes'           => [
                    'user_id'       => $userId,
                    'plan_id'       => $planId,
                    'billing_cycle' => $billingCycle,
                    'plan_name'     => $plan->name,
                ],
            ]);

            PaymentOrder::create([
                'user_id'           => $userId,
                'plan_id'           => $planId,
                'razorpay_order_id' => $order->id,
                'amount'            => $amount,
                'currency'          => 'INR',
                'billing_cycle'     => $billingCycle,
                'status'            => 'created',
            ]);

            Log::info('Razorpay order created', [
                'order_id' => $order->id,
                'user_id'  => $userId,
                'amount'   => $amount,
            ]);

            return [
                'success'       => true,
                'order_id'      => $order->id,
                'amount'        => (int) ($amount * 100),
                'currency'      => 'INR',
                'key_id'        => config('services.razorpay.key_id'),
                'plan_name'     => $plan->name,
                'billing_cycle' => $billingCycle,
            ];

        } catch (\Exception $e) {
            Log::error('Razorpay createOrder failed', ['error' => $e->getMessage(), 'user_id' => $userId]);
            return ['success' => false, 'message' => 'Order creation failed: ' . $e->getMessage()];
        }
    }

    public function verifyPayment(string $orderId, string $paymentId, string $signature): bool
    {
        $expectedSignature = hash_hmac(
            'sha256',
            $orderId . '|' . $paymentId,
            config('services.razorpay.key_secret')
        );

        $isValid = hash_equals($expectedSignature, $signature);

        Log::info('Razorpay signature verify', [
            'order_id'   => $orderId,
            'payment_id' => $paymentId,
            'is_valid'   => $isValid,
        ]);

        return $isValid;
    }

    public function activateSubscription(string $orderId, string $paymentId, string $signature): array
    {
        if (!$this->verifyPayment($orderId, $paymentId, $signature)) {
            return ['success' => false, 'message' => 'Payment verification failed. Invalid signature.'];
        }

        $order = PaymentOrder::where('razorpay_order_id', $orderId)->first();

        if (!$order) {
            Log::error('PaymentOrder not found after payment', ['razorpay_order_id' => $orderId]);
            return ['success' => false, 'message' => 'Order not found. Contact support with ID: ' . $orderId];
        }

        if ($order->status === 'paid') {
            $sub = UserSubscription::where('user_id', $order->user_id)->where('status', 'active')->latest()->first();
            return ['success' => true, 'message' => 'Already activated.', 'ends_at' => $sub?->ends_at?->format('d M Y')];
        }

        try {
            $endsAt = $order->billing_cycle === 'yearly' ? now()->addYear() : now()->addMonth();

            DB::transaction(function () use ($order, $paymentId, $signature, $endsAt) {
                $order->update([
                    'razorpay_payment_id' => $paymentId,
                    'razorpay_signature'  => $signature,
                    'status'              => 'paid',
                    'paid_at'             => now(),
                ]);

                UserSubscription::where('user_id', $order->user_id)
                    ->where('status', 'active')
                    ->update(['status' => 'cancelled', 'cancelled_at' => now()]);

                UserSubscription::create([
                    'user_id'       => $order->user_id,
                    'plan_id'       => $order->plan_id,
                    'billing_cycle' => $order->billing_cycle,
                    'status'        => 'active',
                    'starts_at'     => now(),
                    'ends_at'       => $endsAt,
                ]);

                User::where('id', $order->user_id)->update([
                    'subscription_tier' => optional($order->plan)->slug ?? 'paid',
                ]);
            });

            Log::info('Subscription activated', ['user_id' => $order->user_id, 'plan_id' => $order->plan_id]);

            return [
                'success' => true,
                'message' => 'Subscription activated!',
                'ends_at' => $endsAt->format('d M Y'),
            ];

        } catch (\Exception $e) {
            Log::error('Subscription activation failed', ['order_id' => $orderId, 'error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Activation failed: ' . $e->getMessage()];
        }
    }

    public function verifyWebhookSignature(string $body, string $signature): bool
    {
        $expectedSignature = hash_hmac('sha256', $body, config('services.razorpay.webhook_secret'));
        return hash_equals($expectedSignature, $signature);
    }
}