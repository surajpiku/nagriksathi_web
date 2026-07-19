<?php

namespace App\Http\Controllers\Api\V1\Csc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CscDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user  = $request->user();
        $agent = $user->cscAgent;

        if (!$agent) {
            return response()->json([
                'success' => false,
                'message' => 'Seva Mitra profile not found',
            ], 404);
        }

        $todayCustomers  = $agent->customers()->today()->count();
        $todayDone       = $agent->customers()->today()->completed()->count();
        $todayEarnings   = $agent->earnings()->today()->sum('net_amount');
        $monthlyTasks    = $agent->customers()->thisMonth()->count();
        $monthlyEarnings = $agent->earnings()->thisMonth()->sum('net_amount');
        $queue           = $agent->customers()->waiting()->orderBy('token_number')->get();
        $pendingTasks    = $agent->customers()->where('status', 'in_progress')->get();

        return response()->json([
            'success' => true,
            'agent'   => $agent,
            'today'   => [
                'customers' => $todayCustomers,
                'done'      => $todayDone,
                'earnings'  => $todayEarnings,
            ],
            'monthly' => [
                'tasks'    => $monthlyTasks,
                'earnings' => $monthlyEarnings,
            ],
            'queue'        => $queue,
            'pending_tasks'=> $pendingTasks,
            'rating'       => $agent->rating,
        ]);
    }
}
