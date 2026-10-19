<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\PartnerProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AssignmentService
{
    /**
     * Find candidates for an order based on service category and location
     */
    public static function findCandidates(Order $order): Collection
    {
        $order->loadMissing('service');
        $categoryId = $order->service->category_id;
        $maxRadius = (float) config('betulinaja.max_partner_search_radius_km', 25.0);

        $query = User::query()
            ->where('role', 'partner')
            ->where('status', 'active')
            ->whereHas('partnerProfile', function ($q) {
                $q->where('verification_status', 'verified')
                  ->where('availability_status', 'online')
                  ->whereNotNull('current_latitude')
                  ->whereNotNull('current_longitude');
            })
            ->whereHas('partnerProfile.skills', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->with(['partnerProfile.skills']);

        $candidates = $query->get();

        // Calculate distance and filter by radius if order has coordinates
        if ($order->latitude && $order->longitude) {
            $candidates = $candidates->filter(function ($user) use ($order, $maxRadius) {
                $profile = $user->partnerProfile;
                $distance = PricingService::calculateDistance(
                    (float) $order->latitude,
                    (float) $order->longitude,
                    (float) $profile->current_latitude,
                    (float) $profile->current_longitude
                );
                $profile->calculated_distance = $distance;
                return $distance <= $maxRadius;
            });
        }

        return $candidates;
    }

    /**
     * Score candidates:
     * - Distance (weight: 40%): closer is better
     * - Rating (weight: 40%): 5 stars = 100%
     * - Completed jobs (weight: 20%)
     */
    public static function scoreCandidate(User $partner, Order $order): float
    {
        $profile = $partner->partnerProfile;
        $maxRadius = (float) config('betulinaja.max_partner_search_radius_km', 25.0);

        // Distance score
        $distance = $profile->calculated_distance ?? 10.0;
        $distanceScore = max(0, 100 - (($distance / $maxRadius) * 100));

        // Rating score (0-5 => 0-100)
        $rating = (float) ($profile->average_rating ?? 0);
        $ratingScore = ($rating / 5.0) * 100;

        // Experience score (completed jobs cap at 50)
        $jobs = (int) ($profile->completed_jobs ?? 0);
        $jobScore = min(100, ($jobs / 50.0) * 100);

        return (0.40 * $distanceScore) + (0.40 * $ratingScore) + (0.20 * $jobScore);
    }

    /**
     * Auto-assign technician to an order with pessimistic locking to prevent race conditions
     */
    public static function autoAssign(Order $order): ?User
    {
        return DB::transaction(function () use ($order) {
            // Lock the order record
            $lockedOrder = Order::where('id', $order->id)
                ->lockForUpdate()
                ->first();

            if (!$lockedOrder || !in_array($lockedOrder->status, ['pending', 'searching_technician'])) {
                return null;
            }

            $candidates = self::findCandidates($lockedOrder);

            if ($candidates->isEmpty()) {
                $lockedOrder->update(['status' => 'searching_technician']);
                return null;
            }

            // Find candidate with highest score
            $bestCandidate = null;
            $highestScore = -1;

            foreach ($candidates as $candidate) {
                $score = self::scoreCandidate($candidate, $lockedOrder);
                if ($score > $highestScore) {
                    $highestScore = $score;
                    $bestCandidate = $candidate;
                }
            }

            if ($bestCandidate) {
                $oldStatus = $lockedOrder->status;
                $lockedOrder->update([
                    'partner_id' => $bestCandidate->id,
                    'status' => 'assigned',
                ]);

                OrderStatusHistory::create([
                    'order_id' => $lockedOrder->id,
                    'changed_by' => $lockedOrder->customer_id,
                    'old_status' => $oldStatus,
                    'new_status' => 'assigned',
                    'note' => "Penugasan otomatis ke teknisi: {$bestCandidate->name}",
                ]);

                // Notify partner
                NotificationService::send(
                    $bestCandidate->id,
                    'Pesanan Baru Ditugaskan',
                    "Anda ditugaskan untuk pesanan #{$lockedOrder->order_number}. Segera konfirmasi pesanan.",
                    'order_assigned',
                    ['order_id' => $lockedOrder->id, 'order_number' => $lockedOrder->order_number]
                );

                // Notify customer
                NotificationService::send(
                    $lockedOrder->customer_id,
                    'Teknisi Ditemukan',
                    "Teknisi {$bestCandidate->name} telah ditugaskan untuk pesanan Anda #{$lockedOrder->order_number}.",
                    'order_assigned',
                    ['order_id' => $lockedOrder->id, 'order_number' => $lockedOrder->order_number, 'partner_name' => $bestCandidate->name]
                );

                return $bestCandidate;
            }

            return null;
        });
    }

    /**
     * Manually assign technician to an order
     */
    public static function assignManual(Order $order, User $partner, ?User $actor = null): bool
    {
        return DB::transaction(function () use ($order, $partner, $actor) {
            $lockedOrder = Order::where('id', $order->id)
                ->lockForUpdate()
                ->first();

            if (!$lockedOrder || !in_array($lockedOrder->status, ['pending', 'searching_technician'])) {
                return false;
            }

            $profile = $partner->partnerProfile;
            if (!$profile || $profile->verification_status !== 'verified') {
                return false;
            }

            $oldStatus = $lockedOrder->status;
            $lockedOrder->update([
                'partner_id' => $partner->id,
                'assignment_method' => 'manual',
                'status' => 'assigned',
            ]);

            OrderStatusHistory::create([
                'order_id' => $lockedOrder->id,
                'changed_by' => $actor ? $actor->id : $lockedOrder->customer_id,
                'old_status' => $oldStatus,
                'new_status' => 'assigned',
                'note' => "Pemilihan teknisi: {$partner->name}",
            ]);

            // Notify partner
            NotificationService::send(
                $partner->id,
                'Pesanan Baru',
                "Pelanggan memilih Anda untuk pesanan #{$lockedOrder->order_number}.",
                'order_assigned',
                ['order_id' => $lockedOrder->id, 'order_number' => $lockedOrder->order_number]
            );

            return true;
        });
    }
}
