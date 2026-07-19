<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $documents = UserDocument::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json(['success' => true, 'data' => $documents]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'doc_type' => 'required|string',
            'file'     => 'required|file|max:10240|mimes:jpg,jpeg,png,pdf',
        ]);

        $user  = $request->user();
        $limits = ['free' => 5, 'plus' => 30, 'pro' => PHP_INT_MAX];
        $limit  = $limits[$user->subscription_tier];
        $count  = UserDocument::where('user_id', $user->id)->count();

        if ($count >= $limit) {
            return response()->json([
                'success' => false,
                'message' => 'Document limit reached. Please upgrade your plan.',
            ], 429);
        }

        $path = $request->file('file')->store("documents/{$user->id}", 'public');

        $document = UserDocument::create([
            'user_id'    => $user->id,
            'doc_type'   => $request->doc_type,
            'file_url'   => Storage::url($path),
            'file_size'  => $request->file('file')->getSize(),
            'ocr_status' => 'pending',
        ]);

        return response()->json(['success' => true, 'data' => $document]);
    }

    public function ocr(Request $request, $id)
    {
        $document = UserDocument::where('user_id', $request->user()->id)
            ->findOrFail($id);

        // TODO: Integrate Google Vision API here
        $document->update(['ocr_status' => 'processing']);

        return response()->json([
            'success' => true,
            'message' => 'OCR processing started',
            'data'    => $document,
        ]);
    }

    public function expiring(Request $request)
    {
        $documents = UserDocument::where('user_id', $request->user()->id)
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<=', now()->addDays(90))
            ->orderBy('expiry_date')
            ->get();

        return response()->json(['success' => true, 'data' => $documents]);
    }
}