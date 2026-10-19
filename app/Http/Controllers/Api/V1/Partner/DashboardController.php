<?php

namespace App\Http\Controllers\Api\V1\Partner;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Models\Order;
use App\Models\PartnerEarning;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends BaseApiController
{
    /**
     * Get partner dashboard metrics
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $profile = $user->partnerProfile;

        if (!$profile) {
            return $this->sendError('Profil mitra tidak ditemukan.', null, 404);
        }

        $activeJobsCount = Order::where('partner_id', $user->id)
            ->whereIn('status', ['assigned', 'accepted', 'on_the_way', 'arrived', 'in_progress', 'awaiting_customer_confirmation'])
            ->count();

        $todayEarnings = PartnerEarning::where('partner_id', $user->id)
            ->where('type', 'earning')
            ->whereDate('created_at', today())
            ->sum('net_amount');

        $totalEarnings = PartnerEarning::where('partner_id', $user->id)
            ->where('type', 'earning')
            ->sum('net_amount');

        $recentOrders = Order::where('partner_id', $user->id)
            ->with(['service', 'customer:id,name,phone'])
            ->latest()
            ->limit(5)
            ->get();

        return $this->sendSuccess([
            'profile' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'verification_status' => $profile->verification_status,
                'availability_status' => $profile->availability_status,
                'balance' => (float) $profile->balance,
                'average_rating' => (float) $profile->average_rating,
                'total_reviews' => (int) $profile->total_reviews,
                'completed_jobs' => (int) $profile->completed_jobs,
            ],
            'statistics' => [
                'active_jobs' => $activeJobsCount,
                'completed_jobs' => (int) $profile->completed_jobs,
                'today_earnings' => (float) $todayEarnings,
                'total_earnings' => (float) $totalEarnings,
                'balance' => (float) $profile->balance,
            ],
            'recent_orders' => $recentOrders,
        ], 'Dashboard mitra berhasil dimuat.');
    }
}
