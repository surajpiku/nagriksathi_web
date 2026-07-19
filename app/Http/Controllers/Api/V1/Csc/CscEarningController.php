<?php
namespace App\Http\Controllers\Api\V1\Csc;

use App\Http\Controllers\Controller;
use App\Models\CscEarning;
use Illuminate\Http\Request;

class CscEarningController extends Controller
{
    public function index(Request $request)
    {
        $agent    = $request->user()->cscAgent;
        $earnings = CscEarning::where('seva_mitra_id', $agent->id)
            ->latest()
            ->paginate(20);

        return response()->json(['success' => true, 'data' => $earnings]);
    }

    public function summary(Request $request)
    {
        $agent = $request->user()->cscAgent;

        $today   = CscEarning::where('seva_mitra_id', $agent->id)->today()->sum('net_amount');
        $month   = CscEarning::where('seva_mitra_id', $agent->id)->thisMonth()->sum('net_amount');
        $total   = CscEarning::where('seva_mitra_id', $agent->id)->sum('net_amount');
        $pending = CscEarning::where('seva_mitra_id', $agent->id)
            ->where('payment_status', 'pending')->sum('net_amount');

        return response()->json([
            'success' => true,
            'today'   => $today,
            'month'   => $month,
            'total'   => $total,
            'pending' => $pending,
        ]);
    }

    public function requestWithdrawal(Request $request)
    {
        $agent   = $request->user()->cscAgent;
        $pending = CscEarning::where('seva_mitra_id', $agent->id)
            ->where('payment_status', 'pending')
            ->sum('net_amount');

        if ($pending < 100) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum withdrawal amount is ₹100',
            ], 422);
        }

        CscEarning::where('seva_mitra_id', $agent->id)
            ->where('payment_status', 'pending')
            ->update(['payment_status' => 'processing']);

        return response()->json([
            'success' => true,
            'message' => "Withdrawal request of ₹{$pending} submitted. Will be processed in 2-3 days.",
            'amount'  => $pending,
        ]);
    }
}
