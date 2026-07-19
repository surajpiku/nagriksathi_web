<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\StateMaster;
use App\Models\DistrictMaster;
use App\Models\BlockMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    // GET /location/states
    public function states()
    {
        return response()->json([
            'success' => true,
            'data'    => StateMaster::where('is_active', true)
                            ->orderBy('name')
                            ->get(['id', 'name', 'hindi_name', 'code', 'type', 'capital']),
        ]);
    }

    // GET /location/districts/{stateId}
    public function districts($stateId)
    {
        return response()->json([
            'success' => true,
            'data'    => DistrictMaster::where('state_id', $stateId)
                            ->where('is_active', true)
                            ->orderBy('name')
                            ->get(['id', 'name', 'hindi_name', 'code']),
        ]);
    }

    // GET /location/districts-by-code/{stateCode}
    public function districtsByCode($stateCode)
    {
        $state = StateMaster::where('code', strtoupper($stateCode))->first();

        if (!$state) {
            return response()->json(['success' => false, 'message' => 'State not found'], 404);
        }

        return response()->json([
            'success' => true,
            'state'   => $state,
            'data'    => DistrictMaster::where('state_id', $state->id)
                            ->where('is_active', true)
                            ->orderBy('name')
                            ->get(['id', 'name', 'hindi_name']),
        ]);
    }

    // GET /location/blocks/{districtId}
    public function blocks($districtId)
    {
        return response()->json([
            'success' => true,
            'data'    => BlockMaster::where('district_id', $districtId)
                            ->where('is_active', true)
                            ->orderBy('name')
                            ->get(['id', 'name', 'hindi_name']),
        ]);
    }

    // GET /location/pincode/{pincode}
    public function lookupPincode($pincode)
    {
        try {
            $response = Http::timeout(10)
                ->get("https://api.postalpincode.in/pincode/{$pincode}");

            $data = $response->json();

            if ($data[0]['Status'] === 'Success') {
                $post     = $data[0]['PostOffice'][0];
                $stateName = $post['State'];
                $state    = StateMaster::where('name', 'like', "%{$stateName}%")->first();

                return response()->json([
                    'success'   => true,
                    'state'     => $post['State'],
                    'state_id'  => $state?->id,
                    'state_code'=> $state?->code,
                    'district'  => $post['District'],
                    'block'     => $post['Block'] ?? null,
                    'region'    => $post['Region'] ?? null,
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Pincode not found']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Pincode lookup failed']);
        }
    }

    // PUT /profile/location
    public function updateLocation(Request $request)
    {
        $request->validate([
            'state'      => 'nullable|string',
            'state_code' => 'nullable|string|max:5',
            'district'   => 'nullable|string',
            'pincode'    => 'nullable|digits:6',
            'area_type'  => 'nullable|in:rural,urban,semi_urban',
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
        ]);

        $profile = $request->user()->profile;

        if (!$profile) {
            return response()->json(['success' => false, 'message' => 'Profile not found'], 404);
        }

        $profile->update([
            'state'               => $request->state ?? $profile->state,
            'state_code'          => $request->state_code ?? $profile->state_code,
            'district'            => $request->district ?? $profile->district,
            'pincode'             => $request->pincode ?? $profile->pincode,
            'area_type'           => $request->area_type ?? $profile->area_type,
            'latitude'            => $request->latitude ?? $profile->latitude,
            'longitude'           => $request->longitude ?? $profile->longitude,
            'location_verified'   => true,
            'location_updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'profile' => $profile,
        ]);
    }

    // GET /api/v1/location/subdistricts/{districtId}
public function subdistricts($districtId)
{
    $subdistricts = \App\Models\SubdistrictMaster::where('district_id', $districtId)
        ->where('is_active', true)
        ->orderBy('name')
        ->get(['id', 'name', 'hindi_name', 'type']);

    return response()->json(['success' => true, 'data' => $subdistricts]);
}

// GET /api/v1/location/gram-panchayats/{subdistrictId}
public function gramPanchayats($subdistrictId)
{
    $gps = \App\Models\GramPanchayat::where('subdistrict_id', $subdistrictId)
        ->where('is_active', true)
        ->orderBy('name')
        ->get(['id', 'name', 'hindi_name']);

    return response()->json(['success' => true, 'data' => $gps]);
}
// GET /api/v1/location/blocks-search?q=muz&district_id=1
public function blocksSearch(Request $request)
{
    $query = \App\Models\BlockMaster::query();
    
    if ($request->q) {
        $query->where('name', 'like', '%' . $request->q . '%');
    }
    if ($request->district_id) {
        $query->where('district_id', $request->district_id);
    }
    if ($request->state_id) {
        $query->whereHas('district', fn($q) => $q->where('state_id', $request->state_id));
    }

    $blocks = $query->limit(10)->get(['id', 'name', 'hindi_name']);
    return response()->json(['success' => true, 'data' => $blocks]);
}

}