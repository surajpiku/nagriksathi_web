<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use Illuminate\Http\Request;

class LifeEventController extends Controller
{
    private array $eventBundles = [
        'baby_born'   => ['Pradhan Mantri Matru Vandana Yojana', 'ICDS', 'Janani Suraksha Yojana'],
        'job_lost'    => ['MGNREGA', 'PM Rozgar Yojana', 'PMKVY'],
        'married'     => ['Sukanya Samriddhi Yojana', 'PM Awas Yojana', 'SHG Loans'],
        'retired'     => ['IGNOAPS', 'Atal Pension Yojana', 'Senior Citizen Savings Scheme'],
        'disability'  => ['NSAP Disability Pension', 'ADIP Scheme', 'Disability Certificate'],
        'farmer'      => ['PM-KISAN', 'PMFBY', 'Kisan Credit Card'],
    ];

    public function index()
    {
        $events = collect($this->eventBundles)->map(fn($schemes, $type) => [
            'type'    => $type,
            'label'   => ucwords(str_replace('_', ' ', $type)),
            'schemes' => $schemes,
        ])->values();

        return response()->json(['success' => true, 'data' => $events]);
    }

    public function trigger(Request $request, string $type)
    {
        if (!isset($this->eventBundles[$type])) {
            return response()->json(['success' => false, 'message' => 'Unknown life event'], 404);
        }

        $user    = $request->user();
        $schemes = $this->eventBundles[$type];

        // Create alert for this life event
        Alert::create([
            'user_id'  => $user->id,
            'type'     => 'new_scheme',
            'title'    => 'New schemes available for you!',
            'message'  => 'Based on your life event, you may be eligible for: ' . implode(', ', $schemes),
            'urgency'  => 'high',
            'sent_at'  => now(),
        ]);

        return response()->json([
            'success' => true,
            'event'   => $type,
            'schemes' => $schemes,
            'message' => 'Life event processed. Check your alerts!',
        ]);
    }
}