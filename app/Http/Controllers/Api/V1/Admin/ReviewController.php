<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends BaseApiController
{
    /**
     * List all reviews with filtering
     */
    public function index(Request $request): JsonResponse
    {
        $query = Review::with(['customer:id,name', 'partner:id,name', 'order:id,order_number']);

        if ($request->filled('partner_id')) {
            $query->where('partner_id', $request->partner_id);
        }

        if ($request->filled('is_visible')) {
            $query->where('is_visible', $request->boolean('is_visible'));
        }

        $reviews = $query->latest()->paginate(15);

        return $this->sendSuccess($reviews, 'Daftar ulasan berhasil dimuat.');
    }

    /**
     * Toggle review visibility / moderate review
     */
    public function toggleVisibility(int $id, Request $request): JsonResponse
    {
        $review = Review::findOrFail($id);
        $review->update([
            'is_visible' => !$review->is_visible,
            'moderated_at' => now(),
        ]);

        return $this->sendSuccess($review, 'Status visibilitas ulasan berhasil diperbarui.');
    }

    /**
     * Delete review
     */
    public function destroy(int $id): JsonResponse
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return $this->sendSuccess(null, 'Ulasan berhasil dihapus.');
    }
}
