<?php

namespace Modules\CscToolkit\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function index()
    {
        return view('csctoolkit::tools.photo-processor');
    }

    public function process(Request $request)
    {
        $request->validate(['image' => 'required|image|max:10240']);

        $path = $request->file('image')->store('toolkit/processed', 'public');

        return response()->json([
            'success'       => true,
            'processed_url' => Storage::url($path),
            'message'       => 'Image processed successfully',
        ]);
    }

    public function save(Request $request)
    {
        $request->validate([
            'image_data' => 'required|string',
            'filename'   => 'required|string',
        ]);

        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->image_data));
        $path      = 'toolkit/processed/' . uniqid() . '_' . $request->filename;

        Storage::disk('public')->put($path, $imageData);

        return response()->json([
            'success' => true,
            'url'     => Storage::url($path),
        ]);
    }
}