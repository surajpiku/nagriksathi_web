<?php

namespace App\Services;

use App\Models\EmailOtpRequest;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class EmailOtpService
{
    const OTP_EXPIRY_MINUTES = 10;
    const MAX_OTP_PER_HOUR   = 3;

    public function sendOtp(string $email, string $ip = null): array
    {
        $email = strtolower($email);

        $recentCount = EmailOtpRequest::where('email', $email)
            ->where('created_at', '>=', now()->subHour())
            ->whereNull('verified_at')
            ->count();

        if ($recentCount >= self::MAX_OTP_PER_HOUR) {
            return [
                'success' => false,
                'message' => 'Too many OTP requests. Please wait 1 hour.',
            ];
        }

        $otp     = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $otpHash = Hash::make($otp);

        EmailOtpRequest::create([
            'email'      => $email,
            'otp_hash'   => $otpHash,
            'expires_at' => now()->addMinutes(self::OTP_EXPIRY_MINUTES),
            'ip_address' => $ip,
        ]);

        try {
            Mail::send('emails.otp', ['otp' => $otp, 'email' => $email], function ($message) use ($email) {
                $message->to($email)
                    ->subject('Your NagrikSathi OTP — ' . now()->format('H:i'));
            });
        } catch (\Exception $e) {
            \Log::error('Email OTP send failed: ' . $e->getMessage());
        }

        return [
            'success' => true,
            'message' => 'OTP sent to ' . $email,
            'dev_otp' => app()->isLocal() ? $otp : null,
        ];
    }

    public function verifyOtp(string $email, string $otp): array
    {
        $email = strtolower($email);

        $request = EmailOtpRequest::where('email', $email)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$request) {
            return ['success' => false, 'message' => 'OTP expired or not found. Please request a new one.'];
        }

        if (!Hash::check($otp, $request->otp_hash)) {
            return ['success' => false, 'message' => 'Invalid OTP. Please try again.'];
        }

        $request->update(['verified_at' => now()]);

        $isNew = !User::where('email', $email)->exists();
        $user  = User::firstOrCreate(
            ['email' => $email],
            [
                'language'          => 'hi',
                'subscription_tier' => 'free',
                'auth_method'       => 'email',
                'email_verified_at' => now(),
            ]
        );

        if (!$isNew && $user->auth_method === 'phone') {
            $user->update(['auth_method' => 'both', 'email_verified_at' => now()]);
        }

        if ($isNew || $user->roles->isEmpty()) {
            $user->assignRole(\Spatie\Permission\Models\Role::findByName('citizen', 'sanctum'));
        }

        // Reload to get latest subscription_tier from DB
        $user->refresh();

        $token    = $user->createToken('nagriksathi')->plainTextToken;
        $role     = $user->getRoleNames()->first() ?? 'citizen';
        $allRoles = $user->getRoleNames();

        $redirectTo = match($role) {
            'admin'       => 'admin_dashboard',
            'seva_mitra'  => 'csc_dashboard',
            'sathi_agent' => 'sathi_panel',
            default       => 'citizen_home',
        };

        return [
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
        ];
    }
}