<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Services\PricingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TechnicianController extends BaseApiController
{
    /**
     * List verified technicians
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::where('role', 'partner')
            ->where('status', 'active')
            ->whereHas('partnerProfile', function ($q) {
                $q->where('verification_status', 'verified');
            })
            ->with(['partnerProfile.skills.category']);

        if ($request->filled('category_id')) {
            $catId = $request->category_id;
            $query->whereHas('partnerProfile.skills', function ($q) use ($catId) {
                $q->where('category_id', $catId);
            });
        }

        if ($request->filled('min_rating')) {
            $minRating = (float) $request->min_rating;
            $query->whereHas('partnerProfile', function ($q) use ($minRating) {
                $q->where('average_rating', '>=', $minRating);
            });
        }

        if ($request->filled('availability')) {
            $status = $request->availability;
            $query->whereHas('partnerProfile', function ($q) use ($status) {
                $q->where('availability_status', $status);
            });
        }

        $technicians = $query->paginate(15);

        // Calculate distance if customer coords provided
        if ($request->filled('latitude') && $request->filled('longitude')) {
            $lat = (float) $request->latitude;
            $lon = (float) $request->longitude;

            $technicians->getCollection()->transform(function ($tech) use ($lat, $lon) {
                $profile = $tech->partnerProfile;
                if ($profile && $profile->current_latitude && $profile->current_longitude) {
                    $profile->distance_km = PricingService::calculateDistance(
                        $lat, $lon,
                        (float) $profile->current_latitude,
                        (float) $profile->current_longitude
                    );
                }
                return $tech;
            });
        }

        return $this->sendSuccess($technicians, 'Daftar teknisi berhasil dimuat.');
    }

    /**
     * Show single technician profile with reviews
     */
    public function show(int $id): JsonResponse
    {
        $technician = User::where('id', $id)
            ->where('role', 'partner')
            ->where('status', 'active')
            ->whereHas('partnerProfile', function ($q) {
                $q->where('verification_status', 'verified');
            })
            ->with([
                'partnerProfile.skills.category',
                'ordersAsPartner.review' => function ($q) {
                    $q->where('is_visible', true)->with('customer:id,name');
                },
            ])
            ->first();

        if (!$technician) {
            return $this->sendError('Teknisi tidak ditemukan atau belum terverifikasi.', null, 404);
        }

        return $this->sendSuccess($technician, 'Detail teknisi berhasil dimuat.');
    }
}
