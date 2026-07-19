<?php

namespace Modules\CscToolkit\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function index()
    {
        return view('csctoolkit::tools.print-optimizer');
    }

    public function layout(Request $request)
    {
        $request->validate([
            'layout_type' => 'required|in:passport_6,stamp_4,halfdoc_2,fullpage',
            'images'      => 'required|array|min:1',
        ]);

        $layouts = [
            'passport_6' => ['cols' => 3, 'rows' => 2, 'label' => '6 Passport Photos per A4'],
            'stamp_4'    => ['cols' => 2, 'rows' => 2, 'label' => '4 Stamp Photos per A4'],
            'halfdoc_2'  => ['cols' => 1, 'rows' => 2, 'label' => '2 Half-page Documents per A4'],
            'fullpage'   => ['cols' => 1, 'rows' => 1, 'label' => 'Full page per A4'],
        ];

        return response()->json([
            'success' => true,
            'layout'  => $layouts[$request->layout_type],
            'images'  => $request->images,
        ]);
    }
}