<?php

namespace App\Http\Controllers\Api\V1\Partner;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Models\PartnerEarning;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EarningController extends BaseApiController
{
    /**
     * List partner earnings history and current balance
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $profile = $user->partnerProfile;

        $earnings = PartnerEarning::where('partner_id', $user->id)
            ->with('order:id,order_number,final_price,completed_at')
            ->latest()
            ->paginate(15);

        $totalEarnings = PartnerEarning::where('partner_id', $user->id)
            ->where('type', 'earning')
            ->sum('net_amount');

        $totalCommission = PartnerEarning::where('partner_id', $user->id)
            ->where('type', 'earning')
            ->sum('platform_commission');

        return $this->sendSuccess([
            'balance' => $profile ? (float) $profile->balance : 0.0,
            'total_earnings' => (float) $totalEarnings,
            'total_commission_paid' => (float) $totalCommission,
            'history' => $earnings,
        ], 'Rincian penghasilan berhasil dimuat.');
    }
}
