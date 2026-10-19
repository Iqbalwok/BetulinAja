<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends BaseApiController
{
    /**
     * Store new category
     */
    public function storeCategory(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $category = ServiceCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon,
            'sort_order' => $request->input('sort_order', 0),
            'is_active' => true,
        ]);

        return $this->sendSuccess($category, 'Kategori berhasil ditambahkan.', 201);
    }

    /**
     * Update category
     */
    public function updateCategory(int $id, Request $request): JsonResponse
    {
        $category = ServiceCategory::findOrFail($id);

        $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data = $request->only(['name', 'description', 'icon', 'is_active', 'sort_order']);
        if ($request->filled('name')) {
            $data['slug'] = Str::slug($request->name);
        }

        $category->update($data);

        return $this->sendSuccess($category, 'Kategori berhasil diperbarui.');
    }

    /**
     * Store new service
     */
    public function storeService(Request $request): JsonResponse
    {
        $request->validate([
            'category_id' => ['required', 'exists:service_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'estimated_duration_minutes' => ['nullable', 'integer', 'min:15'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $service = Service::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'base_price' => $request->base_price,
            'estimated_duration_minutes' => $request->input('estimated_duration_minutes', 60),
            'sort_order' => $request->input('sort_order', 0),
            'is_active' => true,
        ]);

        return $this->sendSuccess($service, 'Layanan berhasil ditambahkan.', 201);
    }

    /**
     * Update service
     */
    public function updateService(int $id, Request $request): JsonResponse
    {
        $service = Service::findOrFail($id);

        $request->validate([
            'category_id' => ['sometimes', 'exists:service_categories,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'base_price' => ['sometimes', 'numeric', 'min:0'],
            'estimated_duration_minutes' => ['nullable', 'integer', 'min:15'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data = $request->only(['category_id', 'name', 'description', 'base_price', 'estimated_duration_minutes', 'is_active', 'sort_order']);
        if ($request->filled('name')) {
            $data['slug'] = Str::slug($request->name);
        }

        $service->update($data);

        return $this->sendSuccess($service, 'Layanan berhasil diperbarui.');
    }

    /**
     * Delete service
     */
    public function destroyService(int $id): JsonResponse
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return $this->sendSuccess(null, 'Layanan berhasil dihapus.');
    }
}
