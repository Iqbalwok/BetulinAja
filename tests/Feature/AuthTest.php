<?php

namespace Tests\Feature;

use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_register(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Ahmad Dani',
            'email' => 'ahmad@example.com',
            'phone' => '081234567899',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'customer',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id', 'name', 'email', 'phone', 'role'],
                    'needs_verification',
                    'masked_email',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'ahmad@example.com',
            'role' => 'customer',
        ]);
    }

    public function test_customer_cannot_login_before_email_verification(): void
    {
        config(['auth.email_verification_required' => true]);

        User::create([
            'name' => 'Unverified User',
            'email' => 'unverified@example.com',
            'phone' => '081299990001',
            'password' => bcrypt('password123'),
            'role' => 'customer',
            'status' => 'inactive',
            'email_verified_at' => null,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'login' => 'unverified@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'errors' => [
                    'needs_verification' => true,
                    'email' => 'unverified@example.com',
                ],
            ]);
    }

    public function test_customer_can_verify_otp_and_login(): void
    {
        $user = User::create([
            'name' => 'Verifying User',
            'email' => 'verify@example.com',
            'phone' => '081299990002',
            'password' => bcrypt('password123'),
            'role' => 'customer',
            'status' => 'inactive',
            'email_verified_at' => null,
        ]);

        $otpData = EmailOtp::generateOtp($user->email, 'registration', $user->id);

        $response = $this->postJson('/api/v1/auth/verify-otp', [
            'email' => $user->email,
            'otp' => $otpData['plain_otp'],
            'type' => 'registration',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'data' => ['user', 'token', 'token_type'],
            ]);

        $user->refresh();
        $this->assertNotNull($user->email_verified_at);
        $this->assertEquals('active', $user->status);

        // Now login should succeed
        $loginRes = $this->postJson('/api/v1/auth/login', [
            'login' => 'verify@example.com',
            'password' => 'password123',
        ]);
        $loginRes->assertStatus(200)->assertJson(['success' => true]);
    }

    public function test_invalid_otp_fails(): void
    {
        $otpData = EmailOtp::generateOtp('invalid@example.com', 'registration');

        $response = $this->postJson('/api/v1/auth/verify-otp', [
            'email' => 'invalid@example.com',
            'otp' => '000000',
            'type' => 'registration',
        ]);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    public function test_partner_can_register_and_creates_profile(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Budi Teknisi',
            'email' => 'budi.tech@example.com',
            'phone' => '081299998888',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'partner',
            'description' => 'Teknisi AC handal',
            'years_experience' => 4,
        ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('users', [
            'email' => 'budi.tech@example.com',
            'role' => 'partner',
        ]);

        $this->assertDatabaseHas('partner_profiles', [
            'verification_status' => 'pending',
            'availability_status' => 'offline',
            'years_experience' => 4,
        ]);
    }

    public function test_public_registration_cannot_register_as_admin(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Hacker Admin',
            'email' => 'hacker@example.com',
            'phone' => '081211112222',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ]);

        $response->assertStatus(422);
    }

    public function test_user_can_login_with_email_or_phone(): void
    {
        $user = User::factory()->customer()->create([
            'email' => 'loginuser@example.com',
            'phone' => '081234500000',
            'password' => bcrypt('secret123'),
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        // Login with email
        $resEmail = $this->postJson('/api/v1/auth/login', [
            'login' => 'loginuser@example.com',
            'password' => 'secret123',
        ]);

        $resEmail->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['data' => ['token', 'user']]);

        // Login with phone
        $resPhone = $this->postJson('/api/v1/auth/login', [
            'login' => '081234500000',
            'password' => 'secret123',
        ]);

        $resPhone->assertStatus(200)->assertJson(['success' => true]);
    }

    public function test_login_fails_with_invalid_password(): void
    {
        User::factory()->create([
            'email' => 'wrongpass@example.com',
            'password' => bcrypt('correct_password'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'login' => 'wrongpass@example.com',
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(401)
            ->assertJson(['success' => false]);
    }

    public function test_forgot_and_reset_password_workflow(): void
    {
        $user = User::factory()->create([
            'email' => 'resetuser@example.com',
            'password' => bcrypt('old_password123'),
        ]);

        // 1. Request forgot password
        $resForgot = $this->postJson('/api/v1/auth/forgot-password', [
            'email' => 'resetuser@example.com',
        ]);
        $resForgot->assertStatus(200)->assertJson(['success' => true]);

        $this->assertDatabaseHas('email_otps', [
            'email' => 'resetuser@example.com',
            'type' => 'password_reset',
        ]);

        // Generate known OTP to test reset
        $otpData = EmailOtp::generateOtp('resetuser@example.com', 'password_reset', $user->id);

        // 2. Reset password
        $resReset = $this->postJson('/api/v1/auth/reset-password', [
            'email' => 'resetuser@example.com',
            'otp' => $otpData['plain_otp'],
            'password' => 'new_password123',
            'password_confirmation' => 'new_password123',
        ]);

        $resReset->assertStatus(200)->assertJson(['success' => true]);

        // 3. Login with new password
        $resLogin = $this->postJson('/api/v1/auth/login', [
            'login' => 'resetuser@example.com',
            'password' => 'new_password123',
        ]);
        $resLogin->assertStatus(200)->assertJson(['success' => true]);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
