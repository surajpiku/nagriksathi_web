<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Grievance;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $applications = Application::with('scheme')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json(['success' => true, 'data' => $applications]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'scheme_id'        => 'required|exists:schemes,id',
            'reference_number' => 'nullable|string',
        ]);

        $application = Application::create([
            'user_id'          => $request->user()->id,
            'scheme_id'        => $request->scheme_id,
            'reference_number' => $request->reference_number,
            'status'           => 'submitted',
            'submitted_at'     => now(),
        ]);

        return response()->json(['success' => true, 'data' => $application]);
    }

    public function grievance(Request $request, $id)
    {
        $request->validate(['description' => 'required|string']);

        $application = Application::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $grievance = Grievance::create([
            'user_id'        => $request->user()->id,
            'application_id' => $application->id,
            'rti_text'       => $request->description,
            'status'         => 'filed',
            'filed_at'       => now(),
        ]);

        return response()->json(['success' => true, 'data' => $grievance]);
    }
}