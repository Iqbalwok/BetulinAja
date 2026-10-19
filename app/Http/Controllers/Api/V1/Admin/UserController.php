<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseApiController
{
    /**
     * List users with optional role & status filter
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::with(['partnerProfile']);

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15);

        return $this->sendSuccess($users, 'Daftar pengguna berhasil dimuat.');
    }

    /**
     * Show single user detail
     */
    public function show(int $id): JsonResponse
    {
        $user = User::with(['partnerProfile.skills.category', 'addresses', 'ordersAsCustomer', 'ordersAsPartner'])
            ->findOrFail($id);

        return $this->sendSuccess($user, 'Detail pengguna berhasil dimuat.');
    }

    /**
     * Update user status (active, inactive, suspended)
     */
    public function updateStatus(int $id, Request $request): JsonResponse
    {
        $request->validate([
            'status' => ['required', 'in:active,inactive,suspended'],
        ]);

        $user = User::findOrFail($id);

        // Prevent self suspension
        if ($user->id === $request->user()->id) {
            return $this->sendError('Anda tidak dapat mengubah status akun Anda sendiri.', null, 400);
        }

        $user->update(['status' => $request->status]);

        return $this->sendSuccess($user, "Status pengguna berhasil diperbarui menjadi {$request->status}.");
    }
}
