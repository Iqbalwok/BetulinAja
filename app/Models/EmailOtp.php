<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'otp_hash',
        'type',
        'expires_at',
        'attempts',
        'is_used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
        'attempts' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a new 6-digit cryptographic OTP, invalidate older unused OTPs for this email+type,
     * and persist the SHA-256 hash in database.
     */
    public static function generateOtp(string $email, string $type = 'registration', ?int $userId = null): array
    {
        $normalizedEmail = strtolower(trim($email));

        // Invalidate older unused OTPs of the same type
        self::where('email', $normalizedEmail)
            ->where('type', $type)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        // Cryptographically secure 6-digit OTP
        $plainOtp = sprintf('%06d', random_int(0, 999999));
        $hash = hash('sha256', $plainOtp);

        $record = self::create([
            'user_id' => $userId,
            'email' => $normalizedEmail,
            'otp_hash' => $hash,
            'type' => $type,
            'expires_at' => now()->addMinutes(5),
            'attempts' => 0,
            'is_used' => false,
        ]);

        return [
            'plain_otp' => $plainOtp,
            'record' => $record,
            'expires_at' => $record->expires_at,
        ];
    }

    /**
     * Verify submitted OTP against stored hash, enforcing expiration and max 5 attempts.
     */
    public static function verifyOtp(string $email, string $code, string $type = 'registration'): array
    {
        $normalizedEmail = strtolower(trim($email));
        $cleanCode = trim($code);

        $record = self::where('email', $normalizedEmail)
            ->where('type', $type)
            ->where('is_used', false)
            ->latest('id')
            ->first();

        if (!$record) {
            return [
                'valid' => false,
                'message' => 'Kode OTP tidak ditemukan atau telah kedaluwarsa. Silakan minta kode baru.',
            ];
        }

        if (now()->greaterThan($record->expires_at)) {
            $record->update(['is_used' => true]);
            return [
                'valid' => false,
                'message' => 'Kode OTP telah kedaluwarsa (berlaku 5 menit). Silakan minta kode baru.',
            ];
        }

        if ($record->attempts >= 5) {
            $record->update(['is_used' => true]);
            return [
                'valid' => false,
                'message' => 'Batas maksimal 5 kali percobaan verifikasi terlampaui. Silakan minta kode baru.',
            ];
        }

        $inputHash = hash('sha256', $cleanCode);
        if (!hash_equals($record->otp_hash, $inputHash)) {
            $record->increment('attempts');
            $remaining = 5 - $record->attempts;
            return [
                'valid' => false,
                'message' => "Kode OTP salah. Sisa kesempatan mencoba: {$remaining} kali.",
                'remaining_attempts' => $remaining,
            ];
        }

        // Successfully verified
        $record->update(['is_used' => true]);

        return [
            'valid' => true,
            'record' => $record,
            'message' => 'Verifikasi kode OTP berhasil.',
        ];
    }
}
