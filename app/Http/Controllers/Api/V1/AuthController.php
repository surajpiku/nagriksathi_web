<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate(['phone' => 'required|digits:10']);

        $otp   = rand(100000, 999999);
        $phone = $request->phone;

        Cache::put("otp_{$phone}", $otp, now()->addMinutes(10));

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
            'dev_otp' => $otp, // Remove in production
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10',
            'otp'   => 'required|digits:6',
        ]);

        $phone     = $request->phone;
        $cachedOtp = Cache::get("otp_{$phone}");

        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP',
            ], 422);
        }

        Cache::forget("otp_{$phone}");

        $isNew = !User::where('phone', $phone)->exists();

        $user = User::firstOrCreate(
            ['phone' => $phone],
            ['language' => 'hi', 'subscription_tier' => 'free']
        );

        if ($isNew || $user->roles()->count() === 0) {
            $user->assignRole(\Spatie\Permission\Models\Role::findByName('citizen', 'sanctum'));
        }

        $token    = $user->createToken('nagriksathi')->plainTextToken;
        $role     = $user->getRoleNames()->first() ?? 'citizen';
        $allRoles = $user->getRoleNames();

        $redirectTo = match($role) {
            'admin'       => 'admin_dashboard',
            'seva_mitra'  => 'csc_dashboard',
            'sathi_agent' => 'sathi_panel',
            'specialist'  => 'specialist_panel',
            default       => 'citizen_home',
        };

        // Load profile for full user object
        $user->load('profile');

        return response()->json([
            'success'     => true,
            'token'       => $token,
            'role'        => $role,
            'all_roles'   => $allRoles,
            'redirect_to' => $redirectTo,
            'is_new'      => $isNew,
            'user'        => [
                'id'                => $user->id,
                'name'              => $user->name,
                'email'             => $user->email,
                'phone'             => $user->phone,
                'language'          => $user->language,
                'subscription_tier' => $user->subscription_tier ?? 'free',
                'nagrik_score'      => $user->nagrik_score ?? 0,
                'role'              => $role,
                'all_roles'         => $allRoles,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    public function me(Request $request)
    {
        $user     = $request->user()->load('profile');
        $role     = $user->getRoleNames()->first() ?? 'citizen';
        $allRoles = $user->getRoleNames();

        return response()->json([
            'success' => true,
            'user'    => [
                'id'                => $user->id,
                'name'              => $user->name,
                'email'             => $user->email,
                'phone'             => $user->phone,
                'language'          => $user->language,
                'subscription_tier' => $user->subscription_tier ?? 'free',
                'nagrik_score'      => $user->nagrik_score ?? 0,
                'role'              => $role,
                'all_roles'         => $allRoles,
                'profile'           => $user->profile,
            ],
        ]);
    }
}