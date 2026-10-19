<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\Auth\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends BaseApiController
{
    /**
     * Get authenticated user profile
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->isPartner()) {
            $user->load('partnerProfile.skills.category');
        } elseif ($user->isCustomer()) {
            $user->load('addresses');
        }

        return $this->sendSuccess($user, 'Profil pengguna berhasil dimuat.');
    }

    public function show(Request $request): JsonResponse
    {
        return $this->me($request);
    }

    /**
     * Update authenticated user profile
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return $this->sendError('Password saat ini tidak sesuai.', [
                    'current_password' => ['Password saat ini salah.'],
                ], 422);
            }

            if ($request->filled('new_password')) {
                $user->password = Hash::make($request->new_password);
            }
        }

        $user->fill($request->only(['name', 'email', 'phone']));
        $user->save();

        return $this->sendSuccess($user, 'Profil berhasil diperbarui.');
    }
}
