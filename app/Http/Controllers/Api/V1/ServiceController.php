<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends BaseApiController
{
    /**
     * List all active services, optional search & category filter
     */
    public function index(Request $request): JsonResponse
    {
        $query = Service::where('is_active', true)
            ->with(['category', 'pricingRule']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('category_slug')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category_slug);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $services = $query->orderBy('sort_order')->orderBy('name')->get();

        return $this->sendSuccess($services, 'Daftar layanan berhasil dimuat.');
    }

    /**
     * Show single service
     */
    public function show(string $slug): JsonResponse
    {
        $service = Service::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'pricingRule'])
            ->first();

        if (!$service) {
            return $this->sendError('Layanan tidak ditemukan.', null, 404);
        }

        return $this->sendSuccess($service, 'Detail layanan berhasil dimuat.');
    }
}
