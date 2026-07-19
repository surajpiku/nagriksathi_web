<?php

namespace Modules\CscToolkit\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SignatureController extends Controller
{
    public function index()
    {
        return view('csctoolkit::tools.signature');
    }

    public function stamps()
    {
        return response()->json([
            'success' => true,
            'stamps'  => [
                ['id' => 'csc_official',   'name' => 'CSC Official Stamp',    'type' => 'circular'],
                ['id' => 'verified',       'name' => 'Verified by Agent',     'type' => 'rectangular'],
                ['id' => 'attested',       'name' => 'Attested True Copy',    'type' => 'rectangular'],
                ['id' => 'received',       'name' => 'Received',              'type' => 'circular'],
            ]
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'signature'   => 'required|string', // base64
            'stamp_type'  => 'nullable|string',
            'agent_name'  => 'nullable|string',
            'agent_id'    => 'nullable|string',
            'date'        => 'nullable|string',
        ]);

        $signatureData = base64_decode(
            preg_replace('#^data:image/\w+;base64,#i', '', $request->signature)
        );

        $path = 'toolkit/signatures/' . uniqid() . '.png';
        Storage::disk('public')->put($path, $signatureData);

        return response()->json([
            'success'       => true,
            'signature_url' => Storage::url($path),
            'message'       => 'Signature saved successfully',
        ]);
    }
}