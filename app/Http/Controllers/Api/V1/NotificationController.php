<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\AppNotification;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends BaseApiController
{
    /**
     * List user notifications
     */
    public function index(Request $request): JsonResponse
    {
        $notifications = AppNotification::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        $unreadCount = AppNotification::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->count();

        return $this->sendSuccess([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ], 'Daftar notifikasi berhasil dimuat.');
    }

    /**
     * Mark single notification as read
     */
    public function markAsRead(int $id, Request $request): JsonResponse
    {
        $updated = NotificationService::markAsRead($id, $request->user()->id);

        if (!$updated) {
            return $this->sendError('Notifikasi tidak ditemukan atau sudah dibaca.', null, 404);
        }

        return $this->sendSuccess(null, 'Notifikasi telah ditandai sebagai dibaca.');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $count = NotificationService::markAllAsRead($request->user()->id);

        return $this->sendSuccess(['marked_count' => $count], 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}
