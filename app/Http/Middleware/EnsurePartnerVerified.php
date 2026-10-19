<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePartnerVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->role !== 'partner') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya mitra yang dapat mengakses layanan ini.',
            ], 403);
        }

        $profile = $user->partnerProfile;

        if (!$profile || $profile->verification_status !== 'verified') {
            return response()->json([
                'success' => false,
                'message' => 'Akun mitra Anda belum diverifikasi oleh admin.',
            ], 403);
        }

        return $next($request);
    }
}
