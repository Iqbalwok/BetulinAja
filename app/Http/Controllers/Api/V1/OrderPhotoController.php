<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Order;
use App\Models\OrderPhoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderPhotoController extends BaseApiController
{
    /**
     * Upload photo to an order
     */
    public function upload(int $orderId, Request $request): JsonResponse
    {
        $request->validate([
            'photo' => ['required', 'file', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'photo_type' => ['nullable', 'in:damage,before_work,after_work,evidence'],
        ]);

        $order = Order::findOrFail($orderId);
        $user = $request->user();

        // Customer or assigned partner or admin can upload photo
        if (!$user->isAdmin() && $user->id !== $order->customer_id && $user->id !== $order->partner_id) {
            return $this->sendError('Akses ditolak.', null, 403);
        }

        $photoFile = $request->file('photo');
        $photoType = $request->input('photo_type', $user->isPartner() ? 'before_work' : 'damage');

        $path = $photoFile->store("order-photos/{$order->id}", 'public');

        $photo = OrderPhoto::create([
            'order_id' => $order->id,
            'file_path' => $path,
            'original_name' => $photoFile->getClientOriginalName(),
            'photo_type' => $photoType,
            'file_size' => $photoFile->getSize(),
        ]);

        return $this->sendSuccess($photo, 'Foto berhasil diunggah.', 201);
    }
}
