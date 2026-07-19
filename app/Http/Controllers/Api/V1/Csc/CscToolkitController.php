<?php

namespace App\Http\Controllers\Api\V1\Csc;

use App\Http\Controllers\Controller;
use App\Models\CscCustomer;
use App\Models\CscEarning;
use App\Models\PortalStatus;
use App\Models\UserDocument;
use Illuminate\Http\Request;

class CscToolkitController extends Controller
{
    public function queue(Request $request)
    {
        $agent = $request->user()->cscAgent;

        $waiting    = CscCustomer::where('csc_agent_id', $agent->id)->where('status', 'waiting')->orderBy('token_number')->get();
        $inProgress = CscCustomer::where('csc_agent_id', $agent->id)->where('status', 'in_progress')->first();
        $todayDone  = CscCustomer::where('csc_agent_id', $agent->id)->whereDate('visited_at', today())->where('status', 'completed')->count();
        $nextToken  = CscCustomer::where('csc_agent_id', $agent->id)->whereDate('visited_at', today())->max('token_number') ?? 0;

        return response()->json([
            'success'       => true,
            'waiting'       => $waiting,
            'in_progress'   => $inProgress,
            'today_done'    => $todayDone,
            'next_token'    => $nextToken + 1,
            'waiting_count' => $waiting->count(),
        ]);
    }

    public function addToQueue(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'task_type'     => 'required|string',
        ]);

        $agent     = $request->user()->cscAgent;
        $lastToken = CscCustomer::where('csc_agent_id', $agent->id)->whereDate('visited_at', today())->max('token_number') ?? 0;

        $customer = CscCustomer::create([
            'csc_agent_id'     => $agent->id,
            'customer_name'    => $request->customer_name,
            'customer_phone'   => $request->customer_phone ?? null,
            'task_type'        => $request->task_type,
            'task_description' => $request->task_description ?? null,
            'token_number'     => $lastToken + 1,
            'status'           => 'waiting',
            'visited_at'       => now(),
        ]);

        return response()->json(['success' => true, 'data' => $customer, 'token_number' => $customer->token_number]);
    }

    public function startTask(Request $request, $id)
    {
        $agent    = $request->user()->cscAgent;
        $customer = CscCustomer::where('csc_agent_id', $agent->id)->findOrFail($id);
        $customer->update(['status' => 'in_progress']);
        return response()->json(['success' => true, 'data' => $customer]);
    }

    public function completeTask(Request $request, $id)
    {
        $request->validate([
            'amount_charged' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,upi,platform',
        ]);

        $agent    = $request->user()->cscAgent;
        $customer = CscCustomer::where('csc_agent_id', $agent->id)->findOrFail($id);

        $gross      = $request->amount_charged;
        $commission = $gross * 0.15;
        $net        = $gross - $commission;

        $customer->update([
            'status'              => 'completed',
            'amount_charged'      => $gross,
            'platform_commission' => $commission,
            'agent_earning'       => $net,
            'payment_method'      => $request->payment_method,
            'completed_at'        => now(),
        ]);

        CscEarning::create([
            'csc_agent_id'        => $agent->id,
            'csc_customer_id'     => $customer->id,
            'earning_type'        => 'customer_service',
            'gross_amount'        => $gross,
            'commission_deducted' => $commission,
            'net_amount'          => $net,
            'payment_status'      => 'pending',
        ]);

        $agent->increment('tasks_completed');
        return response()->json(['success' => true, 'data' => $customer]);
    }

    public function cancelTask(Request $request, $id)
    {
        $agent    = $request->user()->cscAgent;
        $customer = CscCustomer::where('csc_agent_id', $agent->id)->findOrFail($id);
        $customer->update(['status' => 'cancelled']);
        return response()->json(['success' => true, 'message' => 'Task cancelled']);
    }

    public function dailyReport(Request $request)
    {
        $agent     = $request->user()->cscAgent;
        $customers = CscCustomer::where('csc_agent_id', $agent->id)->whereDate('visited_at', today())->get();

        return response()->json([
            'success'        => true,
            'date'           => today()->format('d M Y'),
            'total'          => $customers->count(),
            'completed'      => $customers->where('status', 'completed')->count(),
            'cancelled'      => $customers->where('status', 'cancelled')->count(),
            'waiting'        => $customers->where('status', 'waiting')->count(),
            'total_charged'  => $customers->sum('amount_charged'),
            'agent_earnings' => $customers->sum('agent_earning'),
            'tasks'          => $customers,
        ]);
    }

    public function vault(Request $request, $customerId)
    {
        $agent    = $request->user()->cscAgent;
        $customer = CscCustomer::where('csc_agent_id', $agent->id)->where('id', $customerId)->firstOrFail();
        $docs     = UserDocument::where('user_id', $customer->user_id)->get(['id', 'doc_type', 'file_url', 'expiry_date', 'ocr_status', 'verified_at']);
        return response()->json(['success' => true, 'data' => $docs]);
    }

    public function uploadForCustomer(Request $request)
    {
        $request->validate([
            'user_id'  => 'required|exists:users,id',
            'doc_type' => 'required|string',
            'file'     => 'required|file|max:10240|mimes:jpg,jpeg,png,pdf',
        ]);

        $path = $request->file('file')->store("documents/{$request->user_id}", 'public');
        $doc  = UserDocument::create([
            'user_id'    => $request->user_id,
            'doc_type'   => $request->doc_type,
            'file_url'   => \Storage::url($path),
            'file_size'  => $request->file('file')->getSize(),
            'ocr_status' => 'pending',
        ]);
        return response()->json(['success' => true, 'data' => $doc]);
    }

    public function portalStatus()
    {
        $portals = PortalStatus::where('is_active', true)
            ->orderByRaw("FIELD(status, 'down', 'slow', 'unknown', 'online')")
            ->get(['id', 'portal_name', 'portal_url', 'status', 'response_time_ms', 'last_checked_at', 'down_since']);

        return response()->json([
            'success'      => true,
            'summary'      => [
                'online'  => $portals->where('status', 'online')->count(),
                'slow'    => $portals->where('status', 'slow')->count(),
                'down'    => $portals->where('status', 'down')->count(),
                'unknown' => $portals->where('status', 'unknown')->count(),
            ],
            'portals'      => $portals,
            'last_updated' => $portals->max('last_checked_at'),
        ]);
    }
}