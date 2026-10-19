<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Models\Order;
use App\Models\PartnerProfile;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends BaseApiController
{
    /**
     * Get platform statistics for Admin
     */
    public function index(): JsonResponse
    {
        $totalCustomers = User::where('role', 'customer')->count();
        $totalPartners = User::where('role', 'partner')->count();
        $pendingPartners = PartnerProfile::where('verification_status', 'pending')->count();
        $verifiedPartners = PartnerProfile::where('verification_status', 'verified')->count();

        $totalOrders = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();
        $activeOrders = Order::whereNotIn('status', ['completed', 'cancelled'])->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        $totalGrossRevenue = Order::where('status', 'completed')->sum('final_price');
        $totalPlatformCommission = Order::where('status', 'completed')->sum('platform_commission');
        $totalPartnerPayouts = Order::where('status', 'completed')->sum('partner_earning');

        $recentOrders = Order::with(['customer:id,name', 'partner:id,name', 'service:id,name'])
            ->latest()
            ->limit(5)
            ->get();

        return $this->sendSuccess([
            'users' => [
                'customers' => $totalCustomers,
                'partners' => $totalPartners,
                'pending_partners' => $pendingPartners,
                'verified_partners' => $verifiedPartners,
            ],
            'orders' => [
                'total' => $totalOrders,
                'completed' => $completedOrders,
                'active' => $activeOrders,
                'cancelled' => $cancelledOrders,
            ],
            'financials' => [
                'gross_revenue' => (float) $totalGrossRevenue,
                'platform_commission' => (float) $totalPlatformCommission,
                'partner_payouts' => (float) $totalPartnerPayouts,
                'currency' => 'IDR',
            ],
            'recent_orders' => $recentOrders,
        ], 'Dashboard admin berhasil dimuat.');
    }
}
