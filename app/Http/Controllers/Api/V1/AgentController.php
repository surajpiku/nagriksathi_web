<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CscAgent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    // GET /api/v1/agents/nearby
    public function nearby(Request $request)
    {
        $query = CscAgent::with('user')
            ->where('status', 'verified');

        // Filter by pincode
        if ($request->pincode) {
            $query->where('pincode', $request->pincode);
        }

        // Filter by district
        if ($request->district) {
            $query->where('district', 'like', '%' . $request->district . '%');
        }

        // Filter by state
        if ($request->state) {
            $query->where('state', 'like', '%' . $request->state . '%');
        }

        // Filter by GPS coordinates — find within ~50km radius
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
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $agents,
            'total'   => $agents->count(),
        ]);
    }

    // GET /api/v1/agents/{id}
    public function show($id)
    {
        $agent = CscAgent::with('user', 'reviews')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $agent,
        ]);
    }

    // POST /api/v1/agents/register
    public function register(Request $request)
    {
        $request->validate([
            'centre_name' => 'required|string|max:100',
            'agent_type'  => 'required|in:official_vle,sathi_partner,retailer',
            'state'       => 'required|string',
            'district'    => 'required|string',
            'pincode'     => 'required|digits:6',
            'address'     => 'required|string|max:300',
        ]);

        $existing = CscAgent::where('user_id', $request->user()->id)->first();
        if ($existing) {
            return response()->json(['success' => false, 'message' => 'Already registered as Seva Mitra'], 422);
        }

        $agent = CscAgent::create([
            'user_id'     => $request->user()->id,
            'centre_name' => $request->centre_name,
            'agent_type'  => $request->agent_type,
            'state'       => $request->state,
            'state_code'  => $request->state_code,
            'district'    => $request->district,
            'block'       => $request->block,
            'village'     => $request->village,
            'pincode'     => $request->pincode,
            'address'     => $request->address,
            'status'      => 'pending',
        ]);

        return response()->json(['success' => true, 'data' => $agent], 201);
    }
}