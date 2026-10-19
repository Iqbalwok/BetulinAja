<?php

namespace App\Http\Controllers\Api\V1\Partner;

use App\Http\Controllers\Api\V1\BaseApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatusController extends BaseApiController
{
    /**
     * Toggle availability status and update location coordinates
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'availability_status' => ['nullable', 'in:online,offline,busy'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $user = $request->user();
        $profile = $user->partnerProfile;

        if (!$profile) {
            return $this->sendError('Profil mitra tidak ditemukan.', null, 404);
        }

        if ($request->filled('availability_status')) {
            // Unverified partners cannot go online
            if ($request->availability_status === 'online' && $profile->verification_status !== 'verified') {
                return $this->sendError('Akun mitra Anda belum diverifikasi admin. Tidak dapat online.', null, 403);
            }
            $profile->availability_status = $request->availability_status;
        }

        if ($request->filled('latitude')) {
            $profile->current_latitude = $request->latitude;
        }

        if ($request->filled('longitude')) {
            $profile->current_longitude = $request->longitude;
        }

        $profile->save();

        return $this->sendSuccess([
            'availability_status' => $profile->availability_status,
            'current_latitude' => $profile->current_latitude,
            'current_longitude' => $profile->current_longitude,
        ], 'Status ketersediaan berhasil diperbarui.');
    }
}
