<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PaymentOrder;
use App\Models\UserSubscription;
use App\Services\RazorpayService;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function __construct(private RazorpayService $razorpay) {}

    public function razorpay(Request $request)
    {
        $body      = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature');

        if (!$this->razorpay->verifyWebhookSignature($body, $signature)) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $event   = $request->input('event');
        $payload = $request->input('payload');

        match($event) {
            'payment.captured'       => $this->handlePaymentCaptured($payload),
            'subscription.activated' => $this->handleSubscriptionActivated($payload),
            'subscription.charged'   => $this->handleSubscriptionCharged($payload),
            'subscription.cancelled' => $this->handleSubscriptionCancelled($payload),
            'subscription.halted'    => $this->handleSubscriptionHalted($payload),
            default                  => null,
        };

        return response()->json(['success' => true]);
    }

    private function handlePaymentCaptured(array $payload): void
    {
        $paymentId = $payload['payment']['entity']['id'] ?? null;
        $orderId   = $payload['payment']['entity']['order_id'] ?? null;

        if (!$orderId) return;

        PaymentOrder::where('razorpay_order_id', $orderId)
            ->update([
                'razorpay_payment_id' => $paymentId,
                'status'              => 'paid',
                'paid_at'             => now(),
                'razorpay_response'   => $payload,
            ]);
    }

    private function handleSubscriptionActivated(array $payload): void
    {
        $subId  = $payload['subscription']['entity']['id'] ?? null;
        $planId = $payload['subscription']['entity']['plan_id'] ?? null;

        UserSubscription::where('razorpay_subscription_id', $subId)
            ->update(['status' => 'active']);
    }

    private function handleSubscriptionCharged(array $payload): void
    {
        // Renewal — extend subscription
        $subId = $payload['subscription']['entity']['id'] ?? null;
        UserSubscription::where('razorpay_subscription_id', $subId)
            ->update(['ends_at' => now()->addMonth(), 'status' => 'active']);
    }

    private function handleSubscriptionCancelled(array $payload): void
    {
        $subId = $payload['subscription']['entity']['id'] ?? null;
        UserSubscription::where('razorpay_subscription_id', $subId)
            ->update(['status' => 'cancelled', 'cancelled_at' => now()]);
    }

    private function handleSubscriptionHalted(array $payload): void
    {
        // Payment failed — halt subscription
        $subId = $payload['subscription']['entity']['id'] ?? null;
        UserSubscription::where('razorpay_subscription_id', $subId)
            ->update(['status' => 'expired']);
    }
}