<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PartnerEarning;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /**
     * Create payment record for an order
     */
    public static function createPayment(Order $order, string $paymentMethod = 'cash'): Payment
    {
        $amount = $order->final_price ?? $order->total_estimate;

        return Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'payment_method' => $paymentMethod,
                'amount' => $amount,
                'status' => 'pending',
            ]
        );
    }

    /**
     * Mark payment as paid and credit partner balance
     */
    public static function markAsPaid(Payment $payment, ?string $providerReference = null, ?array $providerResponse = null): Payment
    {
        return DB::transaction(function () use ($payment, $providerReference, $providerResponse) {
            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
                'provider_reference' => $providerReference,
                'provider_response' => $providerResponse ? json_encode($providerResponse) : null,
            ]);

            $order = $payment->order;
            if ($order && $order->partner_id) {
                $commission = $order->platform_commission ?? 0;
                $netEarning = $order->partner_earning ?? ($payment->amount - $commission);

                // Create partner earning ledger entry if not exists
                $earning = PartnerEarning::firstOrCreate(
                    [
                        'partner_id' => $order->partner_id,
                        'order_id' => $order->id,
                        'type' => 'earning',
                    ],
                    [
                        'amount' => $payment->amount,
                        'platform_commission' => $commission,
                        'net_amount' => $netEarning,
                        'description' => "Penghasilan dari pesanan #{$order->order_number}",
                        'status' => 'settled',
                    ]
                );

                // Update partner profile balance and completed jobs
                $partnerProfile = $order->partner->partnerProfile ?? null;
                if ($partnerProfile) {
                    $partnerProfile->increment('balance', $netEarning);
                    $partnerProfile->increment('completed_jobs');
                }
            }

            return $payment;
        });
    }

    /**
     * Mark payment as refunded
     */
    public static function refund(Payment $payment): Payment
    {
        $payment->update(['status' => 'refunded']);
        return $payment;
    }
}
