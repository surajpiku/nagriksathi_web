<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index(Request $request)
    {
        $alerts = Alert::where('user_id', $request->user()->id)
            ->orderByRaw("FIELD(urgency, 'critical', 'high', 'medium', 'low')")
            ->latest()
            ->get();

        return response()->json(['success' => true, 'data' => $alerts]);
    }

    public function markRead(Request $request, $id)
    {
        $alert = Alert::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $alert->update(['read_at' => now()]);

        return response()->json(['success' => true, 'message' => 'Alert marked as read']);
    }
}