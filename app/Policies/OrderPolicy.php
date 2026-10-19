<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Determine whether the user can view the order.
     */
    public function view(User $user, Order $order): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isCustomer() && $user->id === $order->customer_id) {
            return true;
        }

        if ($user->isPartner()) {
            if ($user->id === $order->partner_id) {
                return true;
            }
            // Partner can view unassigned orders in searching status to evaluate
            if ($order->status === 'searching_technician' && is_null($order->partner_id)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can cancel the order.
     */
    public function cancel(User $user, Order $order): bool
    {
        if ($user->isAdmin()) {
            return !in_array($order->status, ['completed', 'cancelled']);
        }

        if ($user->isCustomer() && $user->id === $order->customer_id) {
            // Customer can cancel before technician arrives
            return in_array($order->status, ['pending', 'searching_technician', 'assigned', 'accepted']);
        }

        if ($user->isPartner() && $user->id === $order->partner_id) {
            // Partner can decline/cancel if assigned or accepted
            return in_array($order->status, ['assigned', 'accepted']);
        }

        return false;
    }

    /**
     * Determine whether partner can update status of the order.
     */
    public function updateStatus(User $user, Order $order): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isPartner() && $user->id === $order->partner_id;
    }

    /**
     * Determine whether customer can add review.
     */
    public function review(User $user, Order $order): bool
    {
        return $user->isCustomer() &&
            $user->id === $order->customer_id &&
            $order->status === 'completed' &&
            is_null($order->review);
    }
}
