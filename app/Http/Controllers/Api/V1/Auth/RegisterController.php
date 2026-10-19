<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\PartnerProfile;
use App\Models\PartnerSkill;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

        $user = DB::transaction(function () use ($request, $role) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => $role,
                'status' => 'active',
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

        return $this->sendSuccess([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'status' => $user->status,
                'partner_profile' => $user->partnerProfile,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Registrasi berhasil.', 201);
    }
}
