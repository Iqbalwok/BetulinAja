<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderNumberService
{
    /**
     * Generate unique order number in format BA{YYYYMMDD}{XXXX}
     */
    public static function generate(): string
    {
        $datePrefix = Carbon::now()->format('Ymd');
        $prefix = 'BA' . $datePrefix;

        return DB::transaction(function () use ($prefix) {
            $lastOrder = Order::withTrashed()
                ->where('order_number', 'LIKE', $prefix . '%')
                ->lockForUpdate()
                ->orderByDesc('order_number')
                ->first();

            if (!$lastOrder) {
                $sequence = 1;
            } else {
                $lastSequence = (int) substr($lastOrder->order_number, -4);
                $sequence = $lastSequence + 1;
            }

            return $prefix . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
        });
    }
}
