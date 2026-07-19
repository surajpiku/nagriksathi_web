<?php

namespace Modules\CscToolkit\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OcrController extends Controller
{
    public function index()
    {
        return view('csctoolkit::tools.ocr-extractor');
    }

   public function extract(Request $request)
{
    $request->validate(['image' => 'required|image|max:10240']);

    try {
        $base64    = base64_encode(file_get_contents($request->file('image')->path()));
        $mimeType  = $request->file('image')->getMimeType();

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'x-api-key'         => config('services.anthropic.key'),
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model'      => 'claude-sonnet-4-5',
            'max_tokens' => 1024,
            'messages'   => [[
                'role'    => 'user',
                'content' => [
                    [
                        'type'   => 'image',
                        'source' => [
                            'type'       => 'base64',
                            'media_type' => $mimeType,
                            'data'       => $base64,
                        ],
                    ],
                    [
                        'type' => 'text',
                        'text' => 'Extract all text from this document image. Then identify and return structured data in JSON format with these fields if present: name, dob, gender, address, id_number, expiry_date, document_type, father_name, phone, email. Return response as JSON with keys: raw_text (string) and structured (object). Only return valid JSON, no other text.',
                    ],
                ],
            ]],
        ]);

        $data     = $response->json();
        $content  = $data['content'][0]['text'] ?? '{}';

        // Parse JSON response
       // Strip markdown code fences
$content = trim($content);
$content = preg_replace('/^```json\s*/m', '', $content);
$content = preg_replace('/^```\s*/m', '', $content);
$content = trim($content);

// Parse JSON response
$parsed     = json_decode($content, true);
$rawText    = $parsed['raw_text']   ?? $content;
$structured = $parsed['structured'] ?? new \stdClass();

        return response()->json([
            'success'    => true,
            'raw_text'   => $rawText,
            'structured' => $structured,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'OCR failed: ' . $e->getMessage(),
        ], 500);
    }
}
    private function parseDocumentText(string $text): array
    {
        $data = [];

        // Name
        if (preg_match('/(?:Name|नाम|TO)[:\s]+([A-Z][A-Z\s]{2,30})/i', $text, $m))
            $data['name'] = trim($m[1]);

        // DOB
        if (preg_match('/(\d{2}[\/\-]\d{2}[\/\-]\d{4})/', $text, $m))
            $data['dob'] = $m[1];

        // Aadhaar
        if (preg_match('/\d{4}\s\d{4}\s\d{4}/', $text, $m))
            $data['aadhaar'] = $m[0];

        // PAN
        if (preg_match('/[A-Z]{5}\d{4}[A-Z]/', $text, $m))
            $data['pan'] = $m[0];

        // Father name
        if (preg_match('/(?:Father|S\/O|D\/O|W\/O)[:\s]+([A-Z][A-Z\s]{2,30})/i', $text, $m))
            $data['father_name'] = trim($m[1]);

        // Gender
        if (preg_match('/\b(MALE|FEMALE|पुरुष|महिला)\b/i', $text, $m))
            $data['gender'] = strtolower($m[1]);

        // Pincode
        if (preg_match('/\b(\d{6})\b/', $text, $m))
            $data['pincode'] = $m[1];

        // Phone
        if (preg_match('/\b([6-9]\d{9})\b/', $text, $m))
            $data['phone'] = $m[1];

        return $data;
    }
}