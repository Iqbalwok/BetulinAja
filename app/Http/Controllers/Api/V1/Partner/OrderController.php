<?php

namespace App\Http\Controllers\Api\V1\Partner;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Requests\Order\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Models\OrderPhoto;
use App\Models\OrderStatusHistory;
use App\Services\AssignmentService;
use App\Services\NotificationService;
use App\Services\PaymentService;
use App\Services\PricingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends BaseApiController
{
    /**
     * List all partner's orders
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Order::where('partner_id', $user->id)
            ->with(['service.category', 'customer:id,name,phone', 'payment', 'photos', 'review'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15);

        return $this->sendSuccess($orders, 'Daftar pekerjaan mitra berhasil dimuat.');
    }

    /**
     * Show single order detail for partner
     */
    public function show(int $id, Request $request): JsonResponse
    {
        $user = $request->user();
        $order = Order::with([
            'service.category',
            'customer:id,name,phone',
            'payment',
            'photos',
            'statusHistories.changedBy:id,name,role',
            'review',
        ])->findOrFail($id);

        $this->authorize('view', $order);

        return $this->sendSuccess($order, 'Detail pesanan berhasil dimuat.');
    }

    /**
     * List orders searching for technician matching partner skills & radius
     */
    public function available(Request $request): JsonResponse
    {
        $user = $request->user();
        $profile = $user->partnerProfile;

        if (!$profile || $profile->verification_status !== 'verified') {
            return $this->sendError('Hanya mitra terverifikasi yang dapat melihat pesanan tersedia.', null, 403);
        }

        $categoryIds = $profile->skills()->pluck('category_id')->toArray();
        $maxRadius = (float) config('betulinaja.max_partner_search_radius_km', 25.0);

        $orders = Order::where('status', 'searching_technician')
            ->whereNull('partner_id')
            ->whereHas('service', function ($q) use ($categoryIds) {
                $q->whereIn('category_id', $categoryIds);
            })
            ->with(['service.category', 'customer:id,name', 'photos'])
            ->latest()
            ->get();

        // Calculate distance if partner coordinates exist
        if ($profile->current_latitude && $profile->current_longitude) {
            $pLat = (float) $profile->current_latitude;
            $pLon = (float) $profile->current_longitude;

            $orders = $orders->filter(function ($order) use ($pLat, $pLon, $maxRadius) {
                if ($order->latitude && $order->longitude) {
                    $dist = PricingService::calculateDistance($pLat, $pLon, (float) $order->latitude, (float) $order->longitude);
                    $order->distance_to_customer_km = $dist;
                    return $dist <= $maxRadius;
                }
                return true;
            });
        }

        return $this->sendSuccess($orders->values(), 'Pesanan yang tersedia berhasil dimuat.');
    }

    /**
     * Partner accepts assigned order
     */
    public function accept(int $id, Request $request): JsonResponse
    {
        $user = $request->user();

        return DB::transaction(function () use ($id, $user) {
            $order = Order::where('id', $id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($order->partner_id !== $user->id) {
                return $this->sendError('Pesanan ini tidak ditugaskan kepada Anda.', null, 403);
            }

            if ($order->status !== 'assigned') {
                return $this->sendError("Status pesanan saat ini ({$order->status}) tidak dapat diterima.", null, 400);
            }

            $order->update([
                'status' => 'accepted',
                'accepted_at' => now(),
            ]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'changed_by' => $user->id,
                'old_status' => 'assigned',
                'new_status' => 'accepted',
                'note' => 'Teknisi menyetujui pesanan.',
            ]);

            // Notify customer
            NotificationService::send(
                $order->customer_id,
                'Teknisi Menerima Pesanan',
                "Teknisi {$user->name} telah menerima pesanan #{$order->order_number} dan akan segera menuju lokasi Anda.",
                'order_accepted',
                ['order_id' => $order->id, 'order_number' => $order->order_number]
            );

            return $this->sendSuccess($order, 'Pesanan berhasil diterima.');
        });
    }

    /**
     * Partner rejects assigned order
     */
    public function reject(int $id, Request $request): JsonResponse
    {
        $user = $request->user();
        $reason = $request->input('reason', 'Teknisi berhalangan menerima pesanan');

        return DB::transaction(function () use ($id, $user, $reason) {
            $order = Order::where('id', $id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($order->partner_id !== $user->id) {
                return $this->sendError('Pesanan ini tidak ditugaskan kepada Anda.', null, 403);
            }

            if (!in_array($order->status, ['assigned'])) {
                return $this->sendError('Pesanan tidak dapat ditolak pada tahap ini.', null, 400);
            }

            $order->update([
                'partner_id' => null,
                'status' => 'searching_technician',
            ]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'changed_by' => $user->id,
                'old_status' => 'assigned',
                'new_status' => 'searching_technician',
                'note' => "Teknisi {$user->name} menolak pesanan: {$reason}",
            ]);

            // Re-attempt auto-assignment
            AssignmentService::autoAssign($order);

            return $this->sendSuccess(null, 'Pesanan ditolak dan dialihkan kembali ke pencarian teknisi.');
        });
    }

    /**
     * Update order progress status
     */
    public function updateStatus(int $id, UpdateOrderStatusRequest $request): JsonResponse
    {
        $user = $request->user();
        $newStatus = $request->status;

        return DB::transaction(function () use ($id, $user, $newStatus, $request) {
            $order = Order::where('id', $id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($order->partner_id !== $user->id) {
                return $this->sendError('Pesanan ini tidak ditugaskan kepada Anda.', null, 403);
            }

            if (!$order->canTransitionTo($newStatus)) {
                return $this->sendError("Transisi status dari '{$order->status}' ke '{$newStatus}' tidak diizinkan.", null, 422);
            }

            $updateData = ['status' => $newStatus];

            if ($newStatus === 'in_progress' && is_null($order->started_at)) {
                $updateData['started_at'] = now();
            }

            if ($newStatus === 'completed') {
                $updateData['completed_at'] = now();
                if ($request->filled('final_price')) {
                    $updateData['final_price'] = (float) $request->final_price;
                    $commRate = (float) config('betulinaja.platform_commission_rate', 15.0);
                    $updateData['platform_commission'] = round(($request->final_price * $commRate) / 100, 2);
                    $updateData['partner_earning'] = round($request->final_price - $updateData['platform_commission'], 2);
                }
            }

            $oldStatus = $order->status;
            $order->update($updateData);

            // Handle work photo upload if provided
            if ($request->hasFile('photo')) {
                $photoFile = $request->file('photo');
                $photoType = $request->input('photo_type', $newStatus === 'completed' ? 'after_work' : 'before_work');
                $path = $photoFile->store("order-photos/{$order->id}", 'public');

                OrderPhoto::create([
                    'order_id' => $order->id,
                    'file_path' => $path,
                    'original_name' => $photoFile->getClientOriginalName(),
                    'photo_type' => $photoType,
                    'file_size' => $photoFile->getSize(),
                ]);
            }

            // Create status history
            $statusLabels = [
                'on_the_way' => 'Teknisi sedang menuju ke lokasi Anda.',
                'arrived' => 'Teknisi telah tiba di lokasi.',
                'in_progress' => 'Teknisi mulai melakukan perbaikan.',
                'awaiting_customer_confirmation' => 'Pekerjaan selesai, menunggu konfirmasi pelanggan.',
                'completed' => 'Pekerjaan telah selesai.',
            ];

            $note = $request->note ?: ($statusLabels[$newStatus] ?? "Status diperbarui menjadi {$newStatus}");

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'changed_by' => $user->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'note' => $note,
            ]);

            // Notify customer
            NotificationService::send(
                $order->customer_id,
                'Pembaruan Status Pesanan',
                "Status pesanan #{$order->order_number}: {$note}",
                "order_{$newStatus}",
                ['order_id' => $order->id, 'order_number' => $order->order_number, 'status' => $newStatus]
            );

            // Settle payment on completed
            if ($newStatus === 'completed' && $order->payment) {
                PaymentService::markAsPaid($order->payment);
            }

            return $this->sendSuccess($order->fresh(['photos', 'payment', 'statusHistories']), 'Status pesanan berhasil diperbarui.');
        });
    }
}
