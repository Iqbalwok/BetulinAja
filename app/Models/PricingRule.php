<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PricingRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id', 'category_id', 'name', 'base_price',
        'travel_fee_per_km', 'minimum_travel_fee',
        'difficulty_fee_light', 'difficulty_fee_medium', 'difficulty_fee_heavy',
        'platform_commission_rate', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'travel_fee_per_km' => 'decimal:2',
            'minimum_travel_fee' => 'decimal:2',
            'difficulty_fee_light' => 'decimal:2',
            'difficulty_fee_medium' => 'decimal:2',
            'difficulty_fee_heavy' => 'decimal:2',
            'platform_commission_rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function service(): BelongsTo { return $this->belongsTo(Service::class); }
    public function category(): BelongsTo { return $this->belongsTo(ServiceCategory::class, 'category_id'); }
}
