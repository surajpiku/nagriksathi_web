<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\EmailOtpService;
use Illuminate\Http\Request;

class EmailAuthController extends Controller
{
    public function __construct(private EmailOtpService $emailOtpService) {}

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $result = $this->emailOtpService->sendOtp(
            $request->email,
            $request->ip()
        );

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6',
        ]);

        $result = $this->emailOtpService->verifyOtp(
            $request->email,
            $request->otp
        );

        return response()->json($result, $result['success'] ? 200 : 422);
    }
}