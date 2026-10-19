<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Order\CancelOrderRequest;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderCancelController extends BaseApiController
{
    /**
     * Cancel an order
     */
    public function cancel(int $id, CancelOrderRequest $request): JsonResponse
    {
        $user = $request->user();
        $order = Order::findOrFail($id);

        $this->authorize('cancel', $order);

        $oldStatus = $order->status;

        DB::transaction(function () use ($order, $user, $request, $oldStatus) {
            $cancelledBy = match ($user->role) {
                'customer' => 'customer',
                'partner' => 'partner',
                'admin' => 'admin',
                default => 'customer',
            };

            $order->update([
                'status' => 'cancelled',
                'cancellation_reason' => $request->cancellation_reason,
                'cancelled_by' => $cancelledBy,
            ]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'changed_by' => $user->id,
                'old_status' => $oldStatus,
                'new_status' => 'cancelled',
                'note' => "Pesanan dibatalkan oleh {$user->name} ({$cancelledBy}): {$request->cancellation_reason}",
            ]);

            // Notify customer if cancelled by someone else
            if ($user->id !== $order->customer_id) {
                NotificationService::send(
                    $order->customer_id,
                    'Pesanan Dibatalkan',
                    "Pesanan #{$order->order_number} telah dibatalkan: {$request->cancellation_reason}",
                    'order_cancelled',
                    ['order_id' => $order->id, 'order_number' => $order->order_number]
                );
            }

            // Notify partner if cancelled by someone else
            if ($order->partner_id && $user->id !== $order->partner_id) {
                NotificationService::send(
                    $order->partner_id,
                    'Pesanan Dibatalkan',
                    "Pesanan #{$order->order_number} telah dibatalkan oleh {$cancelledBy}.",
                    'order_cancelled',
                    ['order_id' => $order->id, 'order_number' => $order->order_number]
                );
            }
        });

        return $this->sendSuccess($order->fresh(), 'Pesanan berhasil dibatalkan.');
    }
}
