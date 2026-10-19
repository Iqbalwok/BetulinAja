<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\User;
use App\Services\AssignmentService;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends BaseApiController
{
    /**
     * List all platform orders with comprehensive filtering
     */
    public function index(Request $request): JsonResponse
    {
        $query = Order::with(['customer:id,name,phone,email', 'partner:id,name,phone', 'service.category', 'payment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('partner_id')) {
            $query->where('partner_id', $request->partner_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('problem_description', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($c) use ($search) {
                      $c->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(15);

        return $this->sendSuccess($orders, 'Daftar semua pesanan berhasil dimuat.');
    }

    /**
     * Show order detail for Admin
     */
    public function show(int $id): JsonResponse
    {
        $order = Order::with([
            'customer',
            'partner.partnerProfile',
            'service.category',
            'photos',
            'statusHistories.changedBy',
            'payment',
            'review',
        ])->findOrFail($id);

        return $this->sendSuccess($order, 'Detail pesanan berhasil dimuat.');
    }

    /**
     * Admin force-assign a partner to an order
     */
    public function assignPartner(int $id, Request $request): JsonResponse
    {
        $request->validate([
            'partner_id' => ['required', 'exists:users,id'],
        ]);

        $order = Order::findOrFail($id);
        $partner = User::where('id', $request->partner_id)->where('role', 'partner')->firstOrFail();

        $success = AssignmentService::assignManual($order, $partner, $request->user());

        if (!$success) {
            return $this->sendError('Gagal menugaskan teknisi. Pastikan status pesanan valid dan mitra sudah diverifikasi.', null, 400);
        }

        return $this->sendSuccess($order->fresh(['partner']), 'Teknisi berhasil ditugaskan.');
    }

    /**
     * Admin force-cancel an order
     */
    public function cancel(int $id, Request $request): JsonResponse
    {
        $request->validate([
            'cancellation_reason' => ['required', 'string', 'min:3'],
        ]);

        $order = Order::findOrFail($id);

        if (in_array($order->status, ['completed', 'cancelled'])) {
            return $this->sendError('Pesanan yang sudah selesai atau dibatalkan tidak dapat diubah.', null, 400);
        }

        $oldStatus = $order->status;

        DB::transaction(function () use ($order, $request, $oldStatus) {
            $order->update([
                'status' => 'cancelled',
                'cancellation_reason' => $request->cancellation_reason,
                'cancelled_by' => 'admin',
            ]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'changed_by' => $request->user()->id,
                'old_status' => $oldStatus,
                'new_status' => 'cancelled',
                'note' => "Dibatalkan oleh Admin: {$request->cancellation_reason}",
            ]);

            NotificationService::send(
                $order->customer_id,
                'Pesanan Dibatalkan oleh Admin',
                "Pesanan #{$order->order_number} telah dibatalkan oleh admin: {$request->cancellation_reason}",
                'order_cancelled'
            );

            if ($order->partner_id) {
                NotificationService::send(
                    $order->partner_id,
                    'Pesanan Dibatalkan oleh Admin',
                    "Pesanan #{$order->order_number} telah dibatalkan oleh admin.",
                    'order_cancelled'
                );
            }
        });

        return $this->sendSuccess($order->fresh(), 'Pesanan berhasil dibatalkan oleh admin.');
    }
}
