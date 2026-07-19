<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Services\RazorpayService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(private RazorpayService $razorpay) {}

    // GET /plans
    public function plans(Request $request)
    {
        $type  = $request->query('type', 'citizen');
        $plans = SubscriptionPlan::active()
            ->where('type', $type)
            ->get();

        return response()->json(['success' => true, 'data' => $plans]);
    }

    // GET /subscriptions/current
    public function current(Request $request)
    {
        $user         = $request->user();
        $subscription = $user->subscription;

        return response()->json([
            'success'      => true,
            'plan'         => $subscription?->plan ?? ['slug' => 'free', 'name' => 'Free Sathi'],
            'subscription' => $subscription,
            'is_free'      => !$subscription,
            'ends_at'      => $subscription?->ends_at?->format('d M Y'),
        ]);
    }

    // POST /subscriptions/create-order
    public function createOrder(Request $request)
    {
        $request->validate([
            'plan_id'       => 'required|exists:subscription_plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
        ]);

        $result = $this->razorpay->createOrder(
            $request->user()->id,
            $request->plan_id,
            $request->billing_cycle
        );

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    // POST /subscriptions/verify-payment
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'required|string',
        ]);

        $result = $this->razorpay->activateSubscription(
            $request->razorpay_order_id,
            $request->razorpay_payment_id,
            $request->razorpay_signature
        );

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    // POST /subscriptions/cancel
    public function cancel(Request $request)
    {
        $user         = $request->user();
        $subscription = $user->subscription;

        if (!$subscription) {
            return response()->json(['success' => false, 'message' => 'No active subscription'], 404);
        }

        $subscription->update([
            'status'              => 'cancelled',
            'cancelled_at'        => now(),
            'cancellation_reason' => $request->reason ?? 'User cancelled',
        ]);

        $user->update(['subscription_tier' => 'free']);

        return response()->json([
            'success' => true,
            'message' => 'Subscription cancelled. Access continues till ' . $subscription->ends_at->format('d M Y'),
        ]);
    }

    // GET /subscriptions/history
    public function history(Request $request)
    {
        $orders = $request->user()->paymentOrders()
            ->with('plan')
            ->latest()
            ->get();

        return response()->json(['success' => true, 'data' => $orders]);
    }
}