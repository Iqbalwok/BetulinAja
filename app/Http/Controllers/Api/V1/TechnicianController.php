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
            ->with(['partnerProfile.skills.category', 'addresses']);

        // Search by keyword (name, description, category)
        if ($request->filled('search')) {
            $search = '%' . trim($request->search) . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhereHas('partnerProfile', function ($sub) use ($search) {
                      $sub->where('description', 'like', $search);
                  })
                  ->orWhereHas('partnerProfile.skills.category', function ($sub) use ($search) {
                      $sub->where('name', 'like', $search);
                  });
            });
        }

        // Category filter (slug or ID)
        if ($request->filled('category')) {
            $cat = $request->category;
            $query->whereHas('partnerProfile.skills.category', function ($q) use ($cat) {
                $q->where('slug', $cat)->orWhere('id', $cat);
            });
        } elseif ($request->filled('category_id')) {
            $catId = $request->category_id;
            $query->whereHas('partnerProfile.skills', function ($q) use ($catId) {
                $q->where('category_id', $catId);
            });
        }

        // City / Location filter
        if ($request->filled('city')) {
            $city = '%' . trim($request->city) . '%';
            $query->whereHas('addresses', function ($q) use ($city) {
                $q->where('city', 'like', $city)->orWhere('district', 'like', $city);
            });
        }

        // Minimum rating filter
        if ($request->filled('min_rating')) {
            $minRating = (float) $request->min_rating;
            $query->whereHas('partnerProfile', function ($q) use ($minRating) {
                $q->where('average_rating', '>=', $minRating);
            });
        }

        // Availability status filter
        if ($request->filled('availability')) {
            $status = $request->availability;
            $query->whereHas('partnerProfile', function ($q) use ($status) {
                $q->where('availability_status', $status);
            });
        }

        // Sorting options
        $sort = $request->get('sort', 'rating_desc');
        if ($sort === 'rating_desc') {
            $query->join('partner_profiles', 'users.id', '=', 'partner_profiles.user_id')
                  ->orderByDesc('partner_profiles.average_rating')
                  ->select('users.*');
        } elseif ($sort === 'experience_desc') {
            $query->join('partner_profiles', 'users.id', '=', 'partner_profiles.user_id')
                  ->orderByDesc('partner_profiles.years_experience')
                  ->select('users.*');
        } elseif ($sort === 'jobs_desc') {
            $query->join('partner_profiles', 'users.id', '=', 'partner_profiles.user_id')
                  ->orderByDesc('partner_profiles.completed_jobs')
                  ->select('users.*');
        } elseif ($sort === 'name_asc') {
            $query->orderBy('name', 'asc');
        }

        $perPage = min((int) $request->get('per_page', 50), 100);
        $technicians = $query->paginate($perPage);

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
                'addresses',
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
