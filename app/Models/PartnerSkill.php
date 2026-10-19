<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerSkill extends Model
{
    protected $fillable = ['partner_id', 'category_id', 'experience_years'];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(PartnerProfile::class, 'partner_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }
}
