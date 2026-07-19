<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FamilyMember;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    public function index(Request $request)
    {
        $members = FamilyMember::where('user_id', $request->user()->id)->get();

        return response()->json(['success' => true, 'data' => $members]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string',
            'relationship' => 'required|string',
            'dob'          => 'nullable|date',
            'gender'       => 'nullable|in:male,female,other',
        ]);

        $user   = $request->user();
        $limits = ['free' => 2, 'plus' => 5, 'pro' => PHP_INT_MAX];
        $limit  = $limits[$user->subscription_tier];
        $count  = FamilyMember::where('user_id', $user->id)->count();

        if ($count >= $limit) {
            return response()->json([
                'success' => false,
                'message' => 'Family member limit reached. Please upgrade your plan.',
            ], 429);
        }

        $member = FamilyMember::create([
            'user_id'      => $user->id,
            'name'         => $request->name,
            'relationship' => $request->relationship,
            'dob'          => $request->dob,
            'gender'       => $request->gender,
            'profile_json' => $request->profile_json,
        ]);

        return response()->json(['success' => true, 'data' => $member]);
    }

    public function destroy(Request $request, $id)
    {
        $member = FamilyMember::where('user_id', $request->user()->id)->findOrFail($id);
        $member->delete();

        return response()->json(['success' => true, 'message' => 'Member removed']);
    }
}