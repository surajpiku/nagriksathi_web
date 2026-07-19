<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Scheme;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $request->validate(['q' => 'required|min:2']);

        $results = Scheme::search($request->q)
            ->where('is_active', true)
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $results,
            'total'   => $results->count(),
        ]);
    }

    public function suggest(Request $request)
    {
        $request->validate(['q' => 'required|min:2']);

        $suggestions = Scheme::where('is_active', true)
            ->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%")
                  ->orWhere('hindi_name', 'like', "%{$request->q}%");
            })
            ->limit(8)
            ->pluck('name');

        return response()->json([
            'success' => true,
            'data'    => $suggestions,
        ]);
    }
}