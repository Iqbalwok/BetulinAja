<?php

namespace Tests\Feature;

use App\Models\EmailOtp;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OtpEmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['auth.email_verification_required' => true]);
    }

    public function test_registration_creates_otp_and_unverified_customer(): void
    {
        $res = $this->postJson('/api/v1/auth/register', [
            'name' => 'Iqbal Adityajaya',
            'email' => 'iqbal.test@example.com',
            'phone' => '085714310516',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'customer',
        ]);

        $res->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'needs_verification' => true,
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'iqbal.test@example.com',
            'email_verified_at' => null,
            'status' => 'inactive',
        ]);

        $this->assertDatabaseHas('email_otps', [
            'email' => 'iqbal.test@example.com',
            'type' => 'registration',
            'is_used' => false,
        ]);
    }

    public function test_correct_otp_verifies_email_and_issues_token(): void
    {
        $user = User::create([
            'name' => 'Verif User',
            'email' => 'verif.user@example.com',
            'phone' => '081234567891',
            'password' => bcrypt('password123'),
            'role' => 'customer',
            'status' => 'inactive',
            'email_verified_at' => null,
        ]);

        $otpData = EmailOtp::generateOtp($user->email, 'registration', $user->id);

        $res = $this->postJson('/api/v1/auth/verify-otp', [
            'email' => $user->email,
            'otp' => $otpData['plain_otp'],
            'type' => 'registration',
        ]);

        $res->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['data' => ['token', 'user']]);

        $user->refresh();
        $this->assertNotNull($user->email_verified_at);
        $this->assertEquals('active', $user->status);
    }

    public function test_incorrect_otp_fails_and_tracks_attempts(): void
    {
        EmailOtp::generateOtp('wrong.otp@example.com', 'registration');

        $res = $this->postJson('/api/v1/auth/verify-otp', [
            'email' => 'wrong.otp@example.com',
            'otp' => '111111',
            'type' => 'registration',
        ]);

        $res->assertStatus(422)
            ->assertJson(['success' => false]);

        $otp = EmailOtp::where('email', 'wrong.otp@example.com')->first();
        $this->assertEquals(1, $otp->attempts);
    }

    public function test_five_failed_attempts_locks_otp(): void
    {
        $otpData = EmailOtp::generateOtp('max.attempts@example.com', 'registration');
        $otp = $otpData['record'];
        $otp->update(['attempts' => 5]);

        // Even with correct OTP, it should be rejected because attempts >= 5
        $res = $this->postJson('/api/v1/auth/verify-otp', [
            'email' => 'max.attempts@example.com',
            'otp' => $otpData['plain_otp'],
            'type' => 'registration',
        ]);

        $res->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    public function test_expired_otp_is_rejected(): void
    {
        $otpData = EmailOtp::generateOtp('expired@example.com', 'registration');
        $otp = $otpData['record'];
        $otp->update(['expires_at' => now()->subMinute()]);

        $res = $this->postJson('/api/v1/auth/verify-otp', [
            'email' => 'expired@example.com',
            'otp' => $otpData['plain_otp'],
            'type' => 'registration',
        ]);

        $res->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    public function test_resend_otp_enforces_60_second_cooldown(): void
    {
        EmailOtp::generateOtp('cooldown@example.com', 'registration');

        // Immediate resend should return 429
        $res = $this->postJson('/api/v1/auth/resend-otp', [
            'email' => 'cooldown@example.com',
            'type' => 'registration',
        ]);

        $res->assertStatus(429)
            ->assertJson(['success' => false]);
    }

    public function test_unverified_customer_cannot_create_order(): void
    {
        $cat = ServiceCategory::create([
            'name' => 'AC',
            'slug' => 'ac',
            'icon' => 'bi-snow',
        ]);

        $service = Service::create([
            'category_id' => $cat->id,
            'name' => 'Cuci AC',
            'slug' => 'cuci-ac',
            'base_price' => 75000,
        ]);

        $unverifiedUser = User::create([
            'name' => 'Unverified Customer',
            'email' => 'unverified.order@example.com',
            'phone' => '081299990005',
            'password' => bcrypt('password123'),
            'role' => 'customer',
            'status' => 'inactive',
            'email_verified_at' => null,
        ]);

        $token = $unverifiedUser->createToken('test')->plainTextToken;

        $res = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/orders', [
                'service_id' => $service->id,
                'problem_description' => 'Test order',
                'address_text' => 'Jakarta',
                'scheduled_at' => now()->addDay()->toDateTimeString(),
            ]);

        $res->assertStatus(403)
            ->assertJson(['success' => false]);
    }
}
