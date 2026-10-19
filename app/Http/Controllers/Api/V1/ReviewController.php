<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Review\CreateReviewRequest;
use App\Models\Order;
use App\Models\Review;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends BaseApiController
{
    /**
     * Submit review for a completed order
     */
    public function store(CreateReviewRequest $request): JsonResponse
    {
        $customer = $request->user();
        $order = Order::findOrFail($request->order_id);

        $this->authorize('review', $order);

        $review = DB::transaction(function () use ($request, $order, $customer) {
            $review = Review::create([
                'order_id' => $order->id,
                'customer_id' => $customer->id,
                'partner_id' => $order->partner_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'is_visible' => true,
            ]);

            // Update partner profile ratings
            if ($order->partner && $order->partner->partnerProfile) {
                $profile = $order->partner->partnerProfile;
                $allReviews = Review::where('partner_id', $order->partner_id)
                    ->where('is_visible', true)
                    ->get();

                $totalReviews = $allReviews->count();
                $avgRating = $totalReviews > 0 ? $allReviews->avg('rating') : 0.0;

                $profile->update([
                    'average_rating' => round($avgRating, 2),
                    'total_reviews' => $totalReviews,
                ]);
            }

            // Notify partner
            NotificationService::send(
                $order->partner_id,
                'Ulasan Baru Diterima',
                "Pelanggan memberikan rating {$request->rating}/5 untuk pesanan #{$order->order_number}.",
                'review_received',
                ['order_id' => $order->id, 'rating' => $request->rating]
            );

            return $review;
        });

        return $this->sendSuccess($review, 'Ulasan berhasil dikirim.', 201);
    }
}
