<?php

namespace App\Services;

use App\Models\PricingRule;
use App\Models\Service;

class PricingService
{
    /**
     * Calculate distance between two coordinates using Haversine formula
     * and apply road adjustment factor.
     */
    public static function calculateDistance(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $earthRadiusKm = 6371.0;
        $adjustmentFactor = (float) config('betulinaja.distance_adjustment_factor', 1.3);

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $straightDistance = $earthRadiusKm * $c;

        $adjustedDistance = $straightDistance * $adjustmentFactor;

        return round($adjustedDistance, 2);
    }

    /**
     * Calculate price estimate for a service
     *
     * @param Service $service
     * @param string $difficultyLevel ('light', 'medium', 'heavy')
     * @param float $distanceKm
     * @param float $additionalFee
     * @return array
     */
    public static function calculatePrice(
        Service $service,
        string $difficultyLevel = 'medium',
        float $distanceKm = 0.0,
        float $additionalFee = 0.0
    ): array {
        // Find pricing rule: specific service rule first, then category rule, else default
        $rule = PricingRule::where('service_id', $service->id)
            ->where('is_active', true)
            ->first();

        if (!$rule && $service->category_id) {
            $rule = PricingRule::where('category_id', $service->category_id)
                ->whereNull('service_id')
                ->where('is_active', true)
                ->first();
        }

        $basePrice = $rule ? (float) $rule->base_price : (float) $service->base_price;
        $travelFeePerKm = $rule ? (float) $rule->travel_fee_per_km : 5000.0;
        $minTravelFee = $rule ? (float) $rule->minimum_travel_fee : 0.0;
        $commissionRate = $rule ? (float) $rule->platform_commission_rate : (float) config('betulinaja.platform_commission_rate', 15.0);

        // Calculate travel fee
        $rawTravelFee = $distanceKm * $travelFeePerKm;
        $travelFee = max($rawTravelFee, $minTravelFee);

        // Calculate difficulty fee
        $difficultyFee = match ($difficultyLevel) {
            'light' => $rule ? (float) $rule->difficulty_fee_light : 0.0,
            'heavy' => $rule ? (float) $rule->difficulty_fee_heavy : 60000.0,
            default => $rule ? (float) $rule->difficulty_fee_medium : 25000.0,
        };

        $totalEstimate = $basePrice + $travelFee + $difficultyFee + $additionalFee;
        $platformCommission = round(($totalEstimate * $commissionRate) / 100, 2);
        $partnerEarning = round($totalEstimate - $platformCommission, 2);

        return [
            'base_price' => round($basePrice, 2),
            'distance_km' => round($distanceKm, 2),
            'travel_fee' => round($travelFee, 2),
            'difficulty_level' => $difficultyLevel,
            'difficulty_fee' => round($difficultyFee, 2),
            'additional_fee' => round($additionalFee, 2),
            'total_estimate' => round($totalEstimate, 2),
            'platform_commission_rate' => $commissionRate,
            'platform_commission' => $platformCommission,
            'partner_earning' => $partnerEarning,
            'currency' => 'IDR',
        ];
    }
}
