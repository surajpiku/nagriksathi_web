<?php

namespace Modules\CscToolkit\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PassportPhotoController extends Controller
{
    private array $presets = [
        'passport_india'  => ['name' => 'Passport (India)',    'width' => 35, 'height' => 45, 'dpi' => 300, 'max_kb' => 500,  'bg' => 'white'],
        'aadhaar'         => ['name' => 'Aadhaar Card',        'width' => 35, 'height' => 45, 'dpi' => 200, 'max_kb' => 100,  'bg' => 'white'],
        'voter_id'        => ['name' => 'Voter ID',            'width' => 35, 'height' => 45, 'dpi' => 200, 'max_kb' => 100,  'bg' => 'white'],
        'pan_card'        => ['name' => 'PAN Card',            'width' => 25, 'height' => 35, 'dpi' => 200, 'max_kb' => 100,  'bg' => 'white'],
        'upsc'            => ['name' => 'UPSC Exam',           'width' => 35, 'height' => 45, 'dpi' => 300, 'max_kb' => 300,  'bg' => 'white'],
        'ssc'             => ['name' => 'SSC Exam',            'width' => 20, 'height' => 25, 'dpi' => 200, 'max_kb' => 50,   'bg' => 'white'],
        'sbi_po'          => ['name' => 'SBI PO',              'width' => 35, 'height' => 45, 'dpi' => 200, 'max_kb' => 100,  'bg' => 'white'],
        'ibps'            => ['name' => 'IBPS Exam',           'width' => 35, 'height' => 45, 'dpi' => 200, 'max_kb' => 100,  'bg' => 'white'],
        'railway'         => ['name' => 'Railway (RRB)',       'width' => 35, 'height' => 45, 'dpi' => 200, 'max_kb' => 100,  'bg' => 'white'],
        'driving_license' => ['name' => 'Driving License',    'width' => 35, 'height' => 45, 'dpi' => 200, 'max_kb' => 100,  'bg' => 'white'],
    ];

    public function index()
    {
        return view('csctoolkit::tools.passport-photo', ['presets' => $this->presets]);
    }

    public function presets()
    {
        return response()->json(['success' => true, 'presets' => $this->presets]);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'image'  => 'required|image|max:10240',
            'preset' => 'required|string',
        ]);

        $preset = $this->presets[$request->preset] ?? $this->presets['passport_india'];
        $path   = $request->file('image')->store('toolkit/passport', 'public');

        return response()->json([
            'success'       => true,
            'processed_url' => Storage::url($path),
            'preset'        => $preset,
            'message'       => "Photo generated for {$preset['name']}",
        ]);
    }

    public function removeBackground(Request $request)
    {
        $request->validate(['image' => 'required|image|max:10240']);

        $apiKey = config('csctoolkit.removebg_api_key');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Remove.bg API key not configured',
            ], 422);
        }

        try {
            $response = Http::withHeaders(['X-Api-Key' => $apiKey])
                ->attach('image_file', file_get_contents($request->file('image')->path()), 'image.jpg')
                ->post('https://api.remove.bg/v1.0/removebg');

            if ($response->successful()) {
                $path = 'toolkit/nobg/' . uniqid() . '.png';
                Storage::disk('public')->put($path, $response->body());

                return response()->json([
                    'success' => true,
                    'url'     => Storage::url($path),
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Background removal failed'], 422);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}