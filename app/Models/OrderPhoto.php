<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderPhoto extends Model
{
    protected $fillable = ['order_id', 'file_path', 'original_name', 'photo_type', 'file_size'];

    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
}
