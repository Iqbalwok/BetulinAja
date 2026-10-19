<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Models\PartnerProfile;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PartnerVerificationController extends BaseApiController
{
    /**
     * List partners by verification status (default pending)
     */
    public function index(Request $request): JsonResponse
    {
        $status = $request->input('status', 'pending');

        $partners = User::where('role', 'partner')
            ->whereHas('partnerProfile', function ($q) use ($status) {
                if ($status !== 'all') {
                    $q->where('verification_status', $status);
                }
            })
            ->with(['partnerProfile.skills.category'])
            ->latest()
            ->paginate(15);

        return $this->sendSuccess($partners, 'Daftar verifikasi mitra berhasil dimuat.');
    }

    /**
     * Approve/verify a partner
     */
    public function verify(int $id, Request $request): JsonResponse
    {
        $user = User::where('id', $id)->where('role', 'partner')->firstOrFail();
        $profile = $user->partnerProfile;

        if (!$profile) {
            return $this->sendError('Profil mitra tidak ditemukan.', null, 404);
        }

        $profile->update([
            'verification_status' => 'verified',
            'verification_note' => $request->input('note', 'Akun telah diverifikasi oleh admin.'),
            'availability_status' => 'online',
        ]);

        NotificationService::send(
            $user->id,
            'Akun Mitra Diverifikasi',
            'Selamat! Akun mitra Anda telah diverifikasi oleh admin. Sekarang Anda dapat menerima pesanan.',
            'partner_verified'
        );

        return $this->sendSuccess($profile, 'Mitra berhasil diverifikasi.');
    }

    /**
     * Reject a partner
     */
    public function reject(int $id, Request $request): JsonResponse
    {
        $request->validate([
            'note' => ['required', 'string', 'min:5'],
        ]);

        $user = User::where('id', $id)->where('role', 'partner')->firstOrFail();
        $profile = $user->partnerProfile;

        if (!$profile) {
            return $this->sendError('Profil mitra tidak ditemukan.', null, 404);
        }

        $profile->update([
            'verification_status' => 'rejected',
            'verification_note' => $request->note,
            'availability_status' => 'offline',
        ]);

        NotificationService::send(
            $user->id,
            'Verifikasi Mitra Ditolak',
            "Pengajuan verifikasi mitra Anda ditolak: {$request->note}",
            'partner_rejected',
            ['note' => $request->note]
        );

        return $this->sendSuccess($profile, 'Verifikasi mitra ditolak.');
    }
}
