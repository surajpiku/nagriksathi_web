<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SathiConversation;
use App\Models\SathiTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SathiController extends Controller
{
    private function getMessageLimit(string $tier): int
    {
        return match($tier) {
            'plus' => 200,
            'pro'  => PHP_INT_MAX,
            default => 20,
        };
    }

    public function message(Request $request)
{
    $request->validate(['message' => 'required|string|max:1000']);

    $user  = $request->user();
    $limits       = $this->getUserLimits($user);
    $totalAllowed = (int) ($limits['ai_messages'] ?? 20);
    $used         = SathiConversation::where('user_id', $user->id)
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->count();

    // Check limit
    if ($totalAllowed !== -1 && $used >= $totalAllowed) {
        return response()->json([
            'success'       => false,
            'limit_reached' => true,
            'message'       => $user->language === 'hi'
                ? 'Is mahine ki AI message limit khatam ho gayi. Upgrade karein!'
                : 'Monthly AI message limit reached. Please upgrade!',
            'used'          => $used,
            'limit'         => $totalAllowed,
            'upgrade_url'   => '/subscription',
        ], 429);
    }

    $profile = $user->profile;
    $schemes = $user->schemeMatches()->with('scheme')->get()
                    ->map(fn($m) => "- {$m->scheme->name}: {$m->scheme->description}")
                    ->join("\n");

    $systemPrompt = "You are Sathi, a friendly government advisor for Indian citizens.
Respond in " . ($user->language === 'hi' ? 'Hindi' : 'English') . ".
Be warm, simple, and always give actionable guidance with exact portal links.

USER PROFILE:
Name: {$profile?->name} | State: {$profile?->state}
Age: {$profile?->age} | Occupation: {$profile?->occupation}
Income: Rs.{$profile?->annual_income}/year
Category: {$profile?->caste_category} | BPL: " . ($profile?->bpl_status ? 'Yes' : 'No') . "

MATCHED SCHEMES:
{$schemes}";

    $response = Http::withHeaders([
        'x-api-key'         => config('services.anthropic.key'),
        'anthropic-version' => '2023-06-01',
        'content-type'      => 'application/json',
    ])->post('https://api.anthropic.com/v1/messages', [
        'model'      => 'claude-sonnet-4-5',
        'max_tokens' => 1024,
        'system'     => $systemPrompt,
        'messages'   => [
            ['role' => 'user', 'content' => $request->message],
        ],
    ]);

    $responseData = $response->json();
    \Log::info('Claude API Response', $responseData);
    $aiReply = $responseData['content'][0]['text'] ?? 'Sorry, I could not process your request.';

    SathiConversation::create([
        'user_id'       => $user->id,
        'channel'       => 'app',
        'messages_json' => [
            ['role' => 'user',      'content' => $request->message],
            ['role' => 'assistant', 'content' => $aiReply],
        ],
        'ai_tokens_used'=> $response->json('usage.output_tokens') ?? 0,
    ]);

    // Recalculate after saving
    $newUsed      = $used + 1;
    $messagesLeft = $totalAllowed === -1 ? 999 : max(0, $totalAllowed - $newUsed);

    return response()->json([
        'success'       => true,
        'reply'         => $aiReply,
        'messages_left' => $messagesLeft,
        'messages_used' => $newUsed,
    ]);
}

    public function history(Request $request)
    {
        $history = SathiConversation::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        return response()->json(['success' => true, 'data' => $history]);
    }

    public function createTask(Request $request)
    {
        $request->validate([
            'task_type'   => 'required|string',
            'description' => 'required|string',
        ]);

        $task = SathiTask::create([
            'user_id'     => $request->user()->id,
            'task_type'   => $request->task_type,
            'description' => $request->description,
            'status'      => 'open',
            'priority'    => 'medium',
            'channel'     => 'app',
        ]);

        return response()->json(['success' => true, 'task' => $task]);
    }
    private function getUserLimits($user): array
{
    $subscription = $user->subscription ?? null;

    if (!$subscription) {
        $freePlan = \App\Models\SubscriptionPlan::where('slug', 'free')->first();
        return $freePlan?->limits_json ?? ['ai_messages' => 20];
    }

    return $subscription->plan->limits_json ?? ['ai_messages' => 20];
}
}
