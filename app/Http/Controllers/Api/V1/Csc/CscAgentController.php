<?php
namespace App\Http\Controllers\Api\V1\Csc;

use App\Http\Controllers\Controller;
use App\Models\CscAgent;
use App\Models\StateMaster;
use Illuminate\Http\Request;

class CscAgentController 
{
    // Public — register as agent
    public function register(Request $request)
    {
        $request->validate([
            'agent_type'    => 'required|in:official_vle,sathi_partner,partner_agent',
            'centre_name'   => 'nullable|string',
            'state'         => 'required|string',
            'state_code'    => 'required|string|max:5',
            'district'      => 'required|string',
            'pincode'       => 'required|digits:6',
            'languages_json'=> 'nullable|array',
            'services_json' => 'nullable|array',
        ]);

        $user = $request->user();

        // Check if already applied
        if (CscAgent::where('user_id', $user->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You have already submitted an application',
            ], 422);
        }

        $agent = CscAgent::create([
            'user_id'        => $user->id,
            'agent_type'     => $request->agent_type,
            'centre_name'    => $request->centre_name,
            'csc_id'         => $request->csc_id ?? null,
            'state'          => $request->state,
            'state_code'     => $request->state_code,
            'district'       => $request->district,
            'pincode'        => $request->pincode,
            'address'        => $request->address ?? null,
            'languages_json' => $request->languages_json ?? ['hi'],
            'services_json'  => $request->services_json ?? [],
            'upi_id'         => $request->upi_id ?? null,
            'status'         => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application submitted! Admin will review in 24-48 hours.',
            'data'    => $agent,
        ]);
    }

   public function nearby(Request $request)
{
    $query = CscAgent::where('status', 'verified')
        ->with('user:id,name,phone');

    // Filter by pincode (most specific)
    if ($request->pincode) {
        $query->where('pincode', $request->pincode);
    }
    // Filter by district
    elseif ($request->district) {
        $query->where('district', 'like', '%' . $request->district . '%');
    }
    // Filter by state
    elseif ($request->state) {
        $query->where('state', 'like', '%' . $request->state . '%');
    }
    // Filter by state_code (legacy)
    elseif ($request->state_code) {
        $query->where('state_code', $request->state_code);
    }

    // GPS coordinates filter
    if ($request->lat && $request->lng) {
        $lat = $request->lat;
        $lng = $request->lng;
        $query->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereRaw("
                (6371 * acos(cos(radians(?)) * cos(radians(latitude))
                * cos(radians(longitude) - radians(?))
                + sin(radians(?)) * sin(radians(latitude)))) < 50
            ", [$lat, $lng, $lat]);
    }

    $agents = $query->orderBy('rating', 'desc')
        ->orderBy('tasks_completed', 'desc')
        ->limit($request->limit ?? 20)
        ->get([
            'id', 'user_id', 'centre_name', 'agent_type',
            'state', 'state_code', 'district', 'block', 'village',
            'pincode', 'address', 'latitude', 'longitude',
            'rating', 'tasks_completed', 'services_json',
        ]);

    return response()->json([
        'success' => true,
        'data'    => $agents,
        'total'   => $agents->count(),
    ]);
}
    // Public — view single agent
    public function show($id)
    {
        $agent = CscAgent::where('status', 'verified')
            ->with(['user:id,name', 'reviews'])
            ->findOrFail($id);

        return response()->json(['success' => true, 'data' => $agent]);
    }

    // Protected — submit review
    public function review(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500',
        ]);

        $agent = CscAgent::findOrFail($id);

        \App\Models\AgentReview::create([
            'seva_mitra_id' => $agent->id,
            'user_id'      => $request->user()->id,
            'rating'       => $request->rating,
            'review'       => $request->review,
        ]);

        // Update agent average rating
        $avgRating = \App\Models\AgentReview::where('seva_mitra_id', $agent->id)->avg('rating');
        $agent->update(['rating' => round($avgRating, 2)]);

        return response()->json(['success' => true, 'message' => 'Review submitted!']);
    }

    // Agent profile
    public function profile(Request $request)
    {
        $agent = $request->user()->sevaMitra;
        return response()->json(['success' => true, 'data' => $agent]);
    }

    public function updateProfile(Request $request)
    {
        $agent = $request->user()->sevaMitra;
        $agent->update($request->only([
            'centre_name', 'address', 'upi_id',
            'services_json', 'languages_json', 'working_hours_json',
        ]));

        return response()->json(['success' => true, 'data' => $agent]);
    }
}
