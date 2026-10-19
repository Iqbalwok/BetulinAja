<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Models\Order;
use App\Models\PartnerEarning;
use App\Models\ServiceCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends BaseApiController
{
    /**
     * Financial report by date range
     */
    public function transactions(Request $request): JsonResponse
    {
        $startDate = $request->input('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $orders = Order::where('status', 'completed')
            ->whereDate('completed_at', '>=', $startDate)
            ->whereDate('completed_at', '<=', $endDate)
            ->with(['customer:id,name', 'partner:id,name', 'service:id,name']);

        $totalRevenue = (clone $orders)->sum('final_price');
        $totalCommission = (clone $orders)->sum('platform_commission');
        $totalPartnerPayouts = (clone $orders)->sum('partner_earning');
        $orderCount = (clone $orders)->count();

        // Group by category
        $revenueByCategory = DB::table('orders')
            ->join('services', 'orders.service_id', '=', 'services.id')
            ->join('service_categories', 'services.category_id', '=', 'service_categories.id')
            ->where('orders.status', 'completed')
            ->whereDate('orders.completed_at', '>=', $startDate)
            ->whereDate('orders.completed_at', '<=', $endDate)
            ->select('service_categories.name as category_name', DB::raw('SUM(orders.final_price) as total_amount'), DB::raw('COUNT(orders.id) as order_count'))
            ->groupBy('service_categories.id', 'service_categories.name')
            ->get();

        return $this->sendSuccess([
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'summary' => [
                'total_completed_orders' => $orderCount,
                'total_gross_revenue' => (float) $totalRevenue,
                'total_platform_commission' => (float) $totalCommission,
                'total_partner_payouts' => (float) $totalPartnerPayouts,
                'currency' => 'IDR',
            ],
            'revenue_by_category' => $revenueByCategory,
            'recent_completed_orders' => $orders->latest('completed_at')->paginate(15),
        ], 'Laporan transaksi berhasil dimuat.');
    }
}
