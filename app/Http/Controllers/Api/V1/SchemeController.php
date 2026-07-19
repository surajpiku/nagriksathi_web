<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Scheme;
use App\Models\UserSchemeMatch;
use Illuminate\Http\Request;

class SchemeController extends Controller
{
    public function index(Request $request)
    {
        $schemes = Scheme::with('category')
            ->where('is_active', true)
            ->when($request->category, fn($q) => $q->where('category_id', $request->category))
            ->when($request->state, fn($q) => $q->where('state', $request->state))
            ->when($request->is_central, fn($q) => $q->where('is_central', $request->is_central))
            ->orderBy('benefit_value', 'desc')
            ->paginate(20);

        return response()->json(['success' => true, 'data' => $schemes]);
    }

    public function matched(Request $request)
    {
        $user = $request->user();

        $matches = UserSchemeMatch::with('scheme.category')
            ->where('user_id', $user->id)
            ->where('eligibility_status', 'eligible')
            ->orderBy('match_score', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $matches]);
    }

    public function benefitTotal(Request $request)
    {
        $user = $request->user();

        $total = UserSchemeMatch::where('user_id', $user->id)
            ->where('eligibility_status', 'eligible')
            ->sum('benefit_value');

        $breakdown = UserSchemeMatch::with('scheme.category')
            ->where('user_id', $user->id)
            ->where('eligibility_status', 'eligible')
            ->get()
            ->groupBy('scheme.category.name')
            ->map(fn($group) => $group->sum('benefit_value'));

        return response()->json([
            'success'   => true,
            'total'     => $total,
            'breakdown' => $breakdown,
        ]);
    }

    public function show($id)
    {
        $scheme = Scheme::with(['category', 'steps'])->findOrFail($id);

        return response()->json(['success' => true, 'data' => $scheme]);
    }
}