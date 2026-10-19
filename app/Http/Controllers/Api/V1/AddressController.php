<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Address\AddressRequest;
use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends BaseApiController
{
    /**
     * List user addresses
     */
    public function index(Request $request): JsonResponse
    {
        $addresses = Address::where('user_id', $request->user()->id)
            ->orderByDesc('is_primary')
            ->latest()
            ->get();

        return $this->sendSuccess($addresses, 'Daftar alamat berhasil dimuat.');
    }

    /**
     * Store new address
     */
    public function store(AddressRequest $request): JsonResponse
    {
        $user = $request->user();

        // If marked primary, unmark others
        if ($request->boolean('is_primary')) {
            Address::where('user_id', $user->id)->update(['is_primary' => false]);
        }

        $address = Address::create([
            'user_id' => $user->id,
            'label' => $request->label,
            'address' => $request->address,
            'landmark' => $request->landmark,
            'city' => $request->city,
            'district' => $request->district,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_primary' => $request->boolean('is_primary', false),
        ]);

        return $this->sendSuccess($address, 'Alamat berhasil disimpan.', 201);
    }

    /**
     * Show single address
     */
    public function show(int $id, Request $request): JsonResponse
    {
        $address = Address::findOrFail($id);
        $this->authorize('view', $address);

        return $this->sendSuccess($address, 'Detail alamat berhasil dimuat.');
    }

    /**
     * Update address
     */
    public function update(int $id, AddressRequest $request): JsonResponse
    {
        $address = Address::findOrFail($id);
        $this->authorize('update', $address);

        if ($request->boolean('is_primary')) {
            Address::where('user_id', $request->user()->id)
                ->where('id', '!=', $address->id)
                ->update(['is_primary' => false]);
        }

        $address->update($request->validated());

        return $this->sendSuccess($address, 'Alamat berhasil diperbarui.');
    }

    /**
     * Delete address
     */
    public function destroy(int $id, Request $request): JsonResponse
    {
        $address = Address::findOrFail($id);
        $this->authorize('delete', $address);

        $address->delete();

        return $this->sendSuccess(null, 'Alamat berhasil dihapus.');
    }
}
