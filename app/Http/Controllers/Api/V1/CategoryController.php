<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ServiceCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends BaseApiController
{
    /**
     * List all active categories
     */
    public function index(): JsonResponse
    {
        $categories = ServiceCategory::where('is_active', true)
            ->withCount('services')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return $this->sendSuccess($categories, 'Daftar kategori berhasil dimuat.');
    }

    /**
     * Show single category with its services
     */
    public function show(string $slug): JsonResponse
    {
        $category = ServiceCategory::where('slug', $slug)
            ->where('is_active', true)
            ->with(['services' => function ($q) {
                $q->where('is_active', true)->orderBy('sort_order');
            }])
            ->first();

        if (!$category) {
            return $this->sendError('Kategori tidak ditemukan.', null, 404);
        }

        return $this->sendSuccess($category, 'Detail kategori berhasil dimuat.');
    }
}
