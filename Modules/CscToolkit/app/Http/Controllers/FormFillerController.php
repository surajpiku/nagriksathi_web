<?php

namespace Modules\CscToolkit\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Modules\CscToolkit\Models\FormTemplate;

class FormFillerController extends Controller
{
    public function index()
    {
        $forms = FormTemplate::where('is_active', true)->get();
        return view('csctoolkit::tools.form-filler', compact('forms'));
    }

    public function show($id)
    {
        $form = FormTemplate::where('form_id', $id)->firstOrFail();
        return response()->json(['success' => true, 'data' => $form]);
    }

    public function autofill(Request $request, $id)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        $form    = FormTemplate::where('form_id', $id)->firstOrFail();
        $user    = \App\Models\User::with('profile')->findOrFail($request->user_id);
        $profile = $user->profile;

        // Build prompt for Claude
        $prompt = "You are a form-filling assistant for Indian government forms.
        
Form: {$form->form_name}
Portal: {$form->portal_url}

User Profile:
Name: {$profile->name}
DOB: {$profile->dob}
State: {$profile->state}
District: {$profile->district}
Occupation: {$profile->occupation}
Annual Income: {$profile->annual_income}
Category: {$profile->caste_category}

Form Fields:
" . collect($form->fields_json)->map(fn($f) => "- {$f['label']} (maps to: {$f['maps_to']})")->join("\n") . "

Return ONLY a JSON object with field IDs as keys and filled values. 
For unknown fields return empty string.
Format: {\"field_id\": \"value\"}";

        try {
            $response = Http::withHeaders([
                'x-api-key'         => config('services.anthropic.key'),
                'anthropic-version' => '2023-06-01',
                'content-type'      => 'application/json',
            ])->timeout(30)->post('https://api.anthropic.com/v1/messages', [
                'model'      => 'claude-sonnet-4-5',
                'max_tokens' => 1000,
                'messages'   => [['role' => 'user', 'content' => $prompt]],
            ]);

            $text   = $response->json('content.0.text') ?? '{}';
            $text   = preg_replace('/```json|```/', '', $text);
            $filled = json_decode(trim($text), true) ?? [];

            return response()->json([
                'success' => true,
                'form'    => $form,
                'filled'  => $filled,
                'message' => count($filled) . ' fields auto-filled',
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}