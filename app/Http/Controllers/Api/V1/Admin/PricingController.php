<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Models\PricingRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PricingController extends BaseApiController
{
    /**
     * List all pricing rules
     */
    public function index(): JsonResponse
    {
        $rules = PricingRule::with(['service', 'category'])->get();

        return $this->sendSuccess($rules, 'Daftar aturan tarif berhasil dimuat.');
    }

    /**
     * Store new pricing rule
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'service_id' => ['nullable', 'exists:services,id'],
            'category_id' => ['nullable', 'exists:service_categories,id'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'travel_fee_per_km' => ['required', 'numeric', 'min:0'],
            'minimum_travel_fee' => ['nullable', 'numeric', 'min:0'],
            'difficulty_fee_light' => ['nullable', 'numeric', 'min:0'],
            'difficulty_fee_medium' => ['nullable', 'numeric', 'min:0'],
            'difficulty_fee_heavy' => ['nullable', 'numeric', 'min:0'],
            'platform_commission_rate' => ['required', 'numeric', 'between:0,100'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $rule = PricingRule::create($request->all());

        return $this->sendSuccess($rule, 'Aturan tarif berhasil ditambahkan.', 201);
    }

    /**
     * Update pricing rule
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $rule = PricingRule::findOrFail($id);

        $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'service_id' => ['nullable', 'exists:services,id'],
            'category_id' => ['nullable', 'exists:service_categories,id'],
            'base_price' => ['sometimes', 'numeric', 'min:0'],
            'travel_fee_per_km' => ['sometimes', 'numeric', 'min:0'],
            'minimum_travel_fee' => ['nullable', 'numeric', 'min:0'],
            'difficulty_fee_light' => ['nullable', 'numeric', 'min:0'],
            'difficulty_fee_medium' => ['nullable', 'numeric', 'min:0'],
            'difficulty_fee_heavy' => ['nullable', 'numeric', 'min:0'],
            'platform_commission_rate' => ['sometimes', 'numeric', 'between:0,100'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $rule->update($request->all());

        return $this->sendSuccess($rule, 'Aturan tarif berhasil diperbarui.');
    }

    /**
     * Delete pricing rule
     */
    public function destroy(int $id): JsonResponse
    {
        $rule = PricingRule::findOrFail($id);
        $rule->delete();

        return $this->sendSuccess(null, 'Aturan tarif berhasil dihapus.');
    }
}
