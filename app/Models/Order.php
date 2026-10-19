<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number', 'customer_id', 'partner_id', 'service_id', 'address_id',
        'problem_description', 'address_text', 'landmark', 'latitude', 'longitude',
        'scheduled_at', 'assignment_method', 'status',
        'base_price', 'distance_km', 'travel_fee', 'difficulty_level', 'difficulty_fee',
        'additional_fee', 'total_estimate', 'final_price', 'platform_commission', 'partner_earning',
        'cancellation_reason', 'cancelled_by', 'accepted_at', 'started_at', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'accepted_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'base_price' => 'decimal:2',
            'distance_km' => 'decimal:2',
            'travel_fee' => 'decimal:2',
            'difficulty_fee' => 'decimal:2',
            'additional_fee' => 'decimal:2',
            'total_estimate' => 'decimal:2',
            'final_price' => 'decimal:2',
            'platform_commission' => 'decimal:2',
            'partner_earning' => 'decimal:2',
        ];
    }

    // Constants for status transitions
    const VALID_TRANSITIONS = [
        'pending' => ['searching_technician', 'cancelled'],
        'searching_technician' => ['assigned', 'cancelled'],
        'assigned' => ['accepted', 'searching_technician', 'cancelled'],
        'accepted' => ['on_the_way', 'cancelled'],
        'on_the_way' => ['arrived'],
        'arrived' => ['in_progress'],
        'in_progress' => ['awaiting_customer_confirmation', 'completed'],
        'awaiting_customer_confirmation' => ['completed'],
        'completed' => [],
        'cancelled' => [],
    ];

    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, self::VALID_TRANSITIONS[$this->status] ?? []);
    }

    public function customer(): BelongsTo { return $this->belongsTo(User::class, 'customer_id'); }
    public function partner(): BelongsTo { return $this->belongsTo(User::class, 'partner_id'); }
    public function service(): BelongsTo { return $this->belongsTo(Service::class); }
    public function address(): BelongsTo { return $this->belongsTo(Address::class); }

    public function photos(): HasMany { return $this->hasMany(OrderPhoto::class); }
    public function statusHistories(): HasMany { return $this->hasMany(OrderStatusHistory::class); }
    public function review(): HasOne { return $this->hasOne(Review::class); }
    public function payment(): HasOne { return $this->hasOne(Payment::class); }
}
