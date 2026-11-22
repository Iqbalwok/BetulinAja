<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Mail\OtpNotificationMail;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class LoginController extends BaseApiController
{
    /**
     * Handle login via email or phone with role-based checks and email verification enforcement.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $login = trim($request->login);

        $user = User::where('email', strtolower($login))
            ->orWhere('phone', $login)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendError('Kredensial yang diberikan tidak cocok dengan data kami.', null, 401);
        }

        if ($user->status === 'suspended') {
            return $this->sendError('Akun Anda ditangguhkan. Silakan hubungi admin support.', null, 403);
        }

        // Enforce email verification for customers only if required by configuration
        $emailVerificationRequired = config('auth.email_verification_required', false);
        if ($emailVerificationRequired && $user->role === 'customer' && is_null($user->email_verified_at)) {
            // Auto-dispatch fresh OTP to email
            try {
                $otpData = EmailOtp::generateOtp($user->email, 'registration', $user->id);
                Mail::to($user->email)->send(new OtpNotificationMail(
                    $otpData['plain_otp'],
                    $user->name,
                    'registration'
                ));
            } catch (\Throwable $e) {
                logger()->error("SMTP send error during unverified login [{$user->email}]: " . $e->getMessage());
            }

            return $this->sendError(
                'Email Anda belum diverifikasi. Kode OTP verifikasi telah dikirimkan ke email Anda.',
                [
                    'needs_verification' => true,
                    'email' => $user->email,
                    'masked_email' => OtpController::maskEmail($user->email),
                ],
                403
            );
        }

        $deviceName = $request->input('device_name', 'browser_web');
        $token = $user->createToken($deviceName)->plainTextToken;

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'status' => $user->status,
            'email_verified_at' => $user->email_verified_at,
        ];

        if ($user->isPartner()) {
            $user->load('partnerProfile.skills.category');
            $userData['partner_profile'] = $user->partnerProfile;
        }

        return $this->sendSuccess([
            'user' => $userData,
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Login berhasil.');
    }
}
