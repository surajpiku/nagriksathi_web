<?php
namespace App\Http\Controllers\Api\V1\Csc;

use App\Http\Controllers\Controller;
use App\Models\CscCustomer;
use App\Models\CscEarning;
use Illuminate\Http\Request;

class CscCustomerController extends Controller
{
    public function index(Request $request)
    {
        $agent     = $request->user()->cscAgent;
        $customers = CscCustomer::where('seva_mitra_id', $agent->id)
            ->latest()
            ->paginate(20);

        return response()->json(['success' => true, 'data' => $customers]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'task_type'     => 'required|string',
        ]);

        $agent = $request->user()->cscAgent;

        // Get next token number for today
        $lastToken = CscCustomer::where('seva_mitra_id', $agent->id)
            ->whereDate('visited_at', today())
            ->max('token_number') ?? 0;

        $customer = CscCustomer::create([
            'seva_mitra_id'    => $agent->id,
            'user_id'         => $request->user_id ?? null,
            'customer_name'   => $request->customer_name,
            'customer_phone'  => $request->customer_phone ?? null,
            'task_type'       => $request->task_type,
            'task_description'=> $request->task_description ?? null,
            'token_number'    => $lastToken + 1,
            'status'          => 'waiting',
        ]);

        return response()->json(['success' => true, 'data' => $customer]);
    }

    public function start(Request $request, $id)
    {
        $agent    = $request->user()->cscAgent;
        $customer = CscCustomer::where('seva_mitra_id', $agent->id)->findOrFail($id);
        $customer->update(['status' => 'in_progress']);

        return response()->json(['success' => true, 'data' => $customer]);
    }

    public function complete(Request $request, $id)
    {
        $request->validate([
            'amount_charged' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,upi,platform',
        ]);

        $agent    = $request->user()->cscAgent;
        $customer = CscCustomer::where('seva_mitra_id', $agent->id)->findOrFail($id);

        $gross      = $request->amount_charged;
        $commission = $gross * 0.15;
        $net        = $gross * 0.85;

        $customer->update([
            'status'              => 'completed',
            'amount_charged'      => $gross,
            'platform_commission' => $commission,
            'agent_earning'       => $net,
            'payment_method'      => $request->payment_method,
            'completed_at'        => now(),
        ]);

        // Create earning record
        CscEarning::create([
            'seva_mitra_id'        => $agent->id,
            'csc_customer_id'     => $customer->id,
            'earning_type'        => 'customer_service',
            'gross_amount'        => $gross,
            'commission_deducted' => $commission,
            'net_amount'          => $net,
            'payment_status'      => 'pending',
        ]);

        // Update agent stats
        $agent->increment('tasks_completed');
        $agent->increment('customers_served');

        return response()->json(['success' => true, 'data' => $customer]);
    }

    public function cancel(Request $request, $id)
    {
        $agent    = $request->user()->cscAgent;
        $customer = CscCustomer::where('seva_mitra_id', $agent->id)->findOrFail($id);
        $customer->update(['status' => 'cancelled']);

        return response()->json(['success' => true, 'message' => 'Cancelled']);
    }

    public function dailyReport(Request $request)
    {
        $agent = $request->user()->cscAgent;

        $report = CscCustomer::where('seva_mitra_id', $agent->id)
            ->whereDate('visited_at', today())
            ->get();

        $total    = $report->where('status', 'completed')->sum('amount_charged');
        $earnings = $report->where('status', 'completed')->sum('agent_earning');

        return response()->json([
            'success'   => true,
            'date'      => today()->format('d M Y'),
            'customers' => $report->count(),
            'completed' => $report->where('status', 'completed')->count(),
            'cancelled' => $report->where('status', 'cancelled')->count(),
            'total_charged' => $total,
            'agent_earnings'=> $earnings,
            'data'      => $report,
        ]);
    }
}
