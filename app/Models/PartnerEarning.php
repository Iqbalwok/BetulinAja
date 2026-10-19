<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerEarning extends Model
{
    protected $table = 'partner_earnings';
    protected $fillable = [
        'partner_id', 'order_id', 'type', 'amount',
        'platform_commission', 'net_amount', 'description', 'status',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'platform_commission' => 'decimal:2',
            'net_amount' => 'decimal:2',
        ];
    }

    public function partner(): BelongsTo { return $this->belongsTo(User::class, 'partner_id'); }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
}
