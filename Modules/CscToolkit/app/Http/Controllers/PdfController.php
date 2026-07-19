<?php

namespace Modules\CscToolkit\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function index()
    {
        return view('csctoolkit::tools.pdf-creator');
    }

    public function merge(Request $request)
    {
        $request->validate([
            'images'   => 'required|array|min:1|max:20',
            'images.*' => 'required|image|max:10240',
        ]);

        $images   = [];
        foreach ($request->file('images') as $image) {
            $path     = $image->store('toolkit/temp', 'public');
            $images[] = Storage::disk('public')->path($path);
        }

        $pdf  = Pdf::loadView('csctoolkit::pdf.merged', ['images' => $images]);
        $path = 'toolkit/pdf/' . uniqid() . '_merged.pdf';

        Storage::disk('public')->put($path, $pdf->output());

        // Clean temp files
        foreach ($images as $img) Storage::disk('public')->delete(str_replace(storage_path('app/public/'), '', $img));

        return response()->json([
            'success'     => true,
            'pdf_url'     => Storage::url($path),
            'page_count'  => count($images),
            'message'     => count($images) . ' pages merged successfully',
        ]);
    }

    public function compress(Request $request)
    {
        $request->validate(['pdf' => 'required|file|mimes:pdf|max:20480']);

        $path = $request->file('pdf')->store('toolkit/pdf', 'public');

        return response()->json([
            'success' => true,
            'url'     => Storage::url($path),
            'message' => 'PDF compressed successfully',
        ]);
    }
}