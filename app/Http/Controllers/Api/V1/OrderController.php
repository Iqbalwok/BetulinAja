<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Order\CreateOrderRequest;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderPhoto;
use App\Models\OrderStatusHistory;
use App\Models\Service;
use App\Models\User;
use App\Services\AssignmentService;
use App\Services\NotificationService;
use App\Services\OrderNumberService;
use App\Services\PaymentService;
use App\Services\PricingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderController extends BaseApiController
{
    /**
     * List authenticated customer's orders
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Order::where('customer_id', $user->id)
            ->with(['service.category', 'partner:id,name,phone', 'partner.partnerProfile', 'payment', 'photos', 'review'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);

        return $this->sendSuccess($orders, 'Daftar pesanan berhasil dimuat.');
    }

    /**
     * Create new order
     */
    public function store(CreateOrderRequest $request): JsonResponse
    {
        $customer = $request->user();
        $service = Service::with('pricingRule')->findOrFail($request->service_id);

        // Determine coordinates and address text
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $addressText = $request->address_text;
        $landmark = $request->landmark;

        if ($request->filled('address_id')) {
            $address = Address::where('id', $request->address_id)
                ->where('user_id', $customer->id)
                ->first();

            if ($address) {
                $addressText = $addressText ?: $address->address;
                $landmark = $landmark ?: $address->landmark;
                $latitude = $latitude ?: $address->latitude;
                $longitude = $longitude ?: $address->longitude;
            }
        }

        // Calculate distance
        $distanceKm = 0.0;
        if ($latitude && $longitude) {
            $partnerLat = -6.1818;
            $partnerLon = 106.8223;

            if ($request->filled('partner_id')) {
                $chosenPartner = User::find($request->partner_id);
                if ($chosenPartner && $chosenPartner->partnerProfile) {
                    $partnerLat = (float) $chosenPartner->partnerProfile->current_latitude ?: $partnerLat;
                    $partnerLon = (float) $chosenPartner->partnerProfile->current_longitude ?: $partnerLon;
                }
            }

            $distanceKm = PricingService::calculateDistance($partnerLat, $partnerLon, (float) $latitude, (float) $longitude);
        }

        $difficultyLevel = $request->input('difficulty_level', 'medium');
        $pricing = PricingService::calculatePrice($service, $difficultyLevel, $distanceKm);

        $order = DB::transaction(function () use ($customer, $request, $service, $pricing, $latitude, $longitude, $addressText, $landmark) {
            $orderNumber = OrderNumberService::generate();
            $assignmentMethod = $request->input('assignment_method', 'auto');

            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => $customer->id,
                'partner_id' => null,
                'service_id' => $service->id,
                'address_id' => $request->address_id,
                'problem_description' => $request->problem_description,
                'address_text' => $addressText,
                'landmark' => $landmark,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'scheduled_at' => $request->scheduled_at ?: now()->addHours(2),
                'assignment_method' => $assignmentMethod,
                'status' => 'pending',
                'base_price' => $pricing['base_price'],
                'distance_km' => $pricing['distance_km'],
                'travel_fee' => $pricing['travel_fee'],
                'difficulty_level' => $pricing['difficulty_level'],
                'difficulty_fee' => $pricing['difficulty_fee'],
                'additional_fee' => $pricing['additional_fee'],
                'total_estimate' => $pricing['total_estimate'],
                'final_price' => $pricing['total_estimate'],
                'platform_commission' => $pricing['platform_commission'],
                'partner_earning' => $pricing['partner_earning'],
            ]);

            // Save photos if uploaded
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photoFile) {
                    $path = $photoFile->store("order-photos/{$order->id}", 'public');
                    OrderPhoto::create([
                        'order_id' => $order->id,
                        'file_path' => $path,
                        'original_name' => $photoFile->getClientOriginalName(),
                        'photo_type' => 'damage',
                        'file_size' => $photoFile->getSize(),
                    ]);
                }
            }

            // Create initial status history
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'changed_by' => $customer->id,
                'old_status' => null,
                'new_status' => 'pending',
                'note' => 'Pesanan berhasil dibuat oleh pelanggan.',
            ]);

            // Create pending payment
            PaymentService::createPayment($order, 'cash');

            // Send notification to customer
            NotificationService::send(
                $customer->id,
                'Pesanan Dibuat',
                "Pesanan #{$order->order_number} berhasil dibuat. Kami sedang memproses pesanan Anda.",
                'order_created',
                ['order_id' => $order->id, 'order_number' => $order->order_number]
            );

            // Handle assignment
            if ($assignmentMethod === 'manual' && $request->filled('partner_id')) {
                $partner = User::where('id', $request->partner_id)
                    ->where('role', 'partner')
                    ->first();

                if ($partner) {
                    AssignmentService::assignManual($order, $partner, $customer);
                }
            } else {
                $order->update(['status' => 'searching_technician']);
                AssignmentService::autoAssign($order);
            }

            return $order;
        });

        $order->refresh();
        $order->load(['service.category', 'partner:id,name,phone', 'partner.partnerProfile', 'payment', 'photos', 'statusHistories']);

        return $this->sendSuccess($order, 'Pesanan berhasil dibuat.', 201);
    }

    /**
     * Show single order details
     */
    public function show(int $id, Request $request): JsonResponse
    {
        $order = Order::with([
            'service.category',
            'customer:id,name,phone,email',
            'partner:id,name,phone,email',
            'partner.partnerProfile',
            'address',
            'photos',
            'statusHistories.changedBy:id,name,role',
            'payment',
            'review',
        ])->findOrFail($id);

        // Authorize via policy
        $this->authorize('view', $order);

        return $this->sendSuccess($order, 'Detail pesanan berhasil dimuat.');
    }
}
