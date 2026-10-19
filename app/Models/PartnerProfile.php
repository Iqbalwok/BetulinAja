<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartnerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'photo', 'description', 'id_card_number', 'id_card_photo',
        'certificate_photo', 'verification_status', 'verification_note',
        'availability_status', 'current_latitude', 'current_longitude',
        'average_rating', 'total_reviews', 'completed_jobs', 'years_experience', 'balance',
    ];

    protected function casts(): array
    {
        return [
            'average_rating' => 'decimal:2',
            'balance' => 'decimal:2',
            'current_latitude' => 'decimal:8',
            'current_longitude' => 'decimal:8',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skills(): HasMany
    {
        return $this->hasMany(PartnerSkill::class, 'partner_id');
    }

    public function isVerified(): bool { return $this->verification_status === 'verified'; }
    public function isOnline(): bool { return $this->availability_status === 'online'; }
    public function isBusy(): bool { return $this->availability_status === 'busy'; }
}
