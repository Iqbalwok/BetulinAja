<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\OtpNotificationMail;
use App\Models\EmailOtp;
use App\Models\PartnerProfile;
use App\Models\PartnerSkill;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends BaseApiController
{
    /**
     * Handle public registration for customer or partner
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $role = $request->input('role', 'customer');

        // Security: never allow admin role via public registration
        if ($role === 'admin') {
            return $this->sendError('Role admin tidak dapat didaftarkan secara publik.', null, 403);
        }

        $emailVerificationRequired = (bool) config('auth.email_verification_required', false);

        $user = DB::transaction(function () use ($request, $role, $emailVerificationRequired) {
            $user = User::create([
                'name' => $request->name,
                'email' => strtolower(trim($request->email)),
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => $role,
                'status' => $emailVerificationRequired ? 'inactive' : 'active',
                'email_verified_at' => $emailVerificationRequired ? null : now(),
            ]);

            if ($role === 'partner') {
                $profile = PartnerProfile::create([
                    'user_id' => $user->id,
                    'description' => $request->description,
                    'id_card_number' => $request->id_card_number,
                    'verification_status' => 'pending',
                    'availability_status' => 'offline',
                    'years_experience' => $request->input('years_experience', 0),
                ]);

                if ($request->has('category_ids') && is_array($request->category_ids)) {
                    foreach ($request->category_ids as $catId) {
                        PartnerSkill::create([
                            'partner_id' => $profile->id,
                            'category_id' => $catId,
                            'experience_years' => $request->input('years_experience', 0),
                        ]);
                    }
                }
            }

            return $user;
        });

        $token = $user->createToken('auth_token')->plainTextToken;

        // If email verification is mandatory, generate and send OTP
        if ($emailVerificationRequired) {
            $otpData = EmailOtp::generateOtp($user->email, 'registration', $user->id);

            try {
                Mail::to($user->email)->send(new OtpNotificationMail(
                    $otpData['plain_otp'],
                    $user->name,
                    'registration'
                ));
            } catch (\Throwable $e) {
                logger()->error("SMTP send error in register [{$user->email}]: " . $e->getMessage());
            }

            return $this->sendSuccess([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role,
                    'status' => $user->status,
                    'email_verified_at' => $user->email_verified_at,
                    'partner_profile' => $user->partnerProfile,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
                'needs_verification' => true,
                'masked_email' => OtpController::maskEmail($user->email),
                'expires_at' => $otpData['expires_at'],
            ], 'Registrasi berhasil. Silakan masukkan kode OTP yang dikirim ke email Anda.', 201);
        }

        return $this->sendSuccess([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'status' => $user->status,
                'email_verified_at' => $user->email_verified_at,
                'partner_profile' => $user->partnerProfile,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
            'needs_verification' => false,
            'masked_email' => OtpController::maskEmail($user->email),
        ], 'Registrasi berhasil. Selamat datang di BetulinAja!', 201);
    }
}
