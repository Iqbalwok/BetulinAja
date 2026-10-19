<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Estimate\EstimateRequest;
use App\Models\Service;
use App\Models\User;
use App\Services\PricingService;
use Illuminate\Http\JsonResponse;

class EstimateController extends BaseApiController
{
    /**
     * Calculate cost estimation based on service, difficulty level, and distance
     */
    public function calculate(EstimateRequest $request): JsonResponse
    {
        $service = Service::with('pricingRule')->findOrFail($request->service_id);
        $difficultyLevel = $request->input('difficulty_level', 'medium');
        $distanceKm = (float) $request->input('distance_km', 0.0);

        // If coordinates provided, calculate distance
        if ($request->filled('latitude') && $request->filled('longitude')) {
            $destLat = (float) $request->latitude;
            $destLon = (float) $request->longitude;

            $originLat = null;
            $originLon = null;

            if ($request->filled('partner_id')) {
                $partner = User::find($request->partner_id);
                if ($partner && $partner->partnerProfile) {
                    $originLat = (float) $partner->partnerProfile->current_latitude;
                    $originLon = (float) $partner->partnerProfile->current_longitude;
                }
            }

            // If no partner coords, use default city center (e.g. Jakarta Pusat -6.1818, 106.8223)
            if ($originLat === null || $originLon === null) {
                $originLat = -6.1818;
                $originLon = 106.8223;
            }

            $distanceKm = PricingService::calculateDistance($originLat, $originLon, $destLat, $destLon);
        }

        $estimate = PricingService::calculatePrice(
            $service,
            $difficultyLevel,
            $distanceKm
        );

        $estimate['service'] = [
            'id' => $service->id,
            'name' => $service->name,
            'estimated_duration_minutes' => $service->estimated_duration_minutes,
        ];

        return $this->sendSuccess($estimate, 'Estimasi biaya berhasil dihitung.');
    }
}
