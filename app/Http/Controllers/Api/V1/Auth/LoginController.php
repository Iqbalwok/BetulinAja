<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class LoginController extends BaseApiController
{
    /**
     * Handle login via email or phone
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $login = $request->login;

        $user = User::where('email', $login)
            ->orWhere('phone', $login)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendError('Kredensial yang diberikan tidak cocok dengan data kami.', null, 401);
        }

        if ($user->status === 'suspended') {
            return $this->sendError('Akun Anda ditangguhkan. Hubungi admin.', null, 403);
        }

        if ($user->status === 'inactive') {
            return $this->sendError('Akun Anda tidak aktif.', null, 403);
        }

        // Revoke old tokens if single session preferred, or keep multi-device
        $deviceName = $request->input('device_name', 'default_device');
        $token = $user->createToken($deviceName)->plainTextToken;

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'status' => $user->status,
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
