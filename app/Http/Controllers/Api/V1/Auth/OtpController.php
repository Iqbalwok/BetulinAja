<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Mail\OtpNotificationMail;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class OtpController extends BaseApiController
{
    /**
     * Helper to partially mask email for privacy (e.g. iqb***@gmail.com)
     */
    public static function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return $email;
        }

        $name = $parts[0];
        $domain = $parts[1];

        $len = strlen($name);
        if ($len <= 2) {
            $maskedName = substr($name, 0, 1) . '***';
        } else {
            $maskedName = substr($name, 0, 3) . str_repeat('*', max(3, $len - 3));
        }

        return $maskedName . '@' . $domain;
    }

    /**
     * Verify submitted 6-digit OTP for registration or password reset.
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'otp' => ['required', 'string', 'size:6'],
            'type' => ['sometimes', 'string', 'in:registration,password_reset'],
        ]);

        $email = strtolower(trim($request->email));
        $otp = trim($request->otp);
        $type = $request->input('type', 'registration');

        $result = EmailOtp::verifyOtp($email, $otp, $type);

        if (!$result['valid']) {
            return $this->sendError($result['message'], [
                'remaining_attempts' => $result['remaining_attempts'] ?? 0,
            ], 422);
        }

        $user = User::where('email', $email)->first();

        // 1. Handling Registration OTP
        if ($type === 'registration') {
            if ($user) {
                $user->email_verified_at = now();
                $user->status = 'active';
                $user->save();

                // Issue Sanctum token for instant auto-login
                $token = $user->createToken('web_registration')->plainTextToken;

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
                ], 'Email Anda berhasil diverifikasi! Selamat datang di BetulinAja.');
            }

            return $this->sendSuccess(null, 'Verifikasi email berhasil.');
        }

        // 2. Handling Password Reset OTP (Verification only, password will be submitted in reset-password)
        return $this->sendSuccess([
            'email' => $email,
            'verified' => true,
        ], 'Kode OTP reset kata sandi valid. Silakan masukkan kata sandi baru Anda.');
    }

    /**
     * Resend a fresh OTP with 60-second cooldown enforcement.
     */
    public function resendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'type' => ['sometimes', 'string', 'in:registration,password_reset'],
        ]);

        $email = strtolower(trim($request->email));
        $type = $request->input('type', 'registration');

        // Enforce 60s cooldown based on latest OTP created
        $latestOtp = EmailOtp::where('email', $email)
            ->where('type', $type)
            ->latest('id')
            ->first();

        if ($latestOtp && $latestOtp->created_at->diffInSeconds(now()) < 60) {
            $secondsRemaining = 60 - $latestOtp->created_at->diffInSeconds(now());
            return $this->sendError("Harap tunggu {$secondsRemaining} detik lagi sebelum meminta kode OTP baru.", [
                'cooldown_seconds' => $secondsRemaining,
            ], 429);
        }

        $user = User::where('email', $email)->first();

        // If registration resend and user already verified, decline
        if ($type === 'registration' && $user && !is_null($user->email_verified_at)) {
            return $this->sendError('Akun ini sudah terverifikasi sebelumnya. Silakan langsung masuk.', null, 400);
        }

        $otpData = EmailOtp::generateOtp($email, $type, $user?->id);

        try {
            Mail::to($email)->send(new OtpNotificationMail(
                $otpData['plain_otp'],
                $user?->name ?? 'Pengguna',
                $type
            ));
        } catch (\Throwable $e) {
            logger()->error("SMTP send error in resendOtp [{$email}]: " . $e->getMessage());
        }

        return $this->sendSuccess([
            'masked_email' => self::maskEmail($email),
            'expires_at' => $otpData['expires_at'],
            'cooldown_seconds' => 60,
        ], 'Kode OTP baru telah dikirimkan ke email Anda.');
    }

    /**
     * Handle Forgot Password Request: Generate and send password reset OTP.
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $email = strtolower(trim($request->email));
        $user = User::where('email', $email)->first();

        // Security best practice: Always return generic response to prevent user enumeration
        $genericMessage = 'Jika alamat email Anda terdaftar, kode OTP reset kata sandi telah dikirimkan.';

        if (!$user) {
            return $this->sendSuccess([
                'masked_email' => self::maskEmail($email),
                'cooldown_seconds' => 60,
            ], $genericMessage);
        }

        // Check 60s cooldown
        $latestOtp = EmailOtp::where('email', $email)
            ->where('type', 'password_reset')
            ->latest('id')
            ->first();

        if ($latestOtp && $latestOtp->created_at->diffInSeconds(now()) < 60) {
            $secondsRemaining = 60 - $latestOtp->created_at->diffInSeconds(now());
            return $this->sendError("Harap tunggu {$secondsRemaining} detik lagi sebelum meminta kode OTP baru.", [
                'cooldown_seconds' => $secondsRemaining,
            ], 429);
        }

        $otpData = EmailOtp::generateOtp($email, 'password_reset', $user->id);

        try {
            Mail::to($email)->send(new OtpNotificationMail(
                $otpData['plain_otp'],
                $user->name,
                'password_reset'
            ));
        } catch (\Throwable $e) {
            logger()->error("SMTP send error in forgotPassword [{$email}]: " . $e->getMessage());
        }

        return $this->sendSuccess([
            'masked_email' => self::maskEmail($email),
            'expires_at' => $otpData['expires_at'],
            'cooldown_seconds' => 60,
        ], $genericMessage);
    }

    /**
     * Handle Reset Password: Verify OTP and save new password hash.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'otp' => ['required', 'string', 'size:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $email = strtolower(trim($request->email));
        $otp = trim($request->otp);

        $result = EmailOtp::verifyOtp($email, $otp, 'password_reset');

        if (!$result['valid']) {
            return $this->sendError($result['message'], [
                'remaining_attempts' => $result['remaining_attempts'] ?? 0,
            ], 422);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return $this->sendError('Pengguna dengan email ini tidak ditemukan.', null, 404);
        }

        // Update password with secure Hash
        $user->password = Hash::make($request->password);
        $user->save();

        // Revoke all existing Sanctum tokens for security
        $user->tokens()->delete();

        return $this->sendSuccess(null, 'Kata sandi Anda berhasil diperbarui. Silakan masuk menggunakan kata sandi baru Anda.');
    }
}
