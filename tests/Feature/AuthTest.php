<?php

namespace Tests\Feature;

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
                'message' => 'Registrasi berhasil.',
            ])
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id', 'name', 'email', 'phone', 'role'],
                    'token',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'ahmad@example.com',
            'role' => 'customer',
        ]);
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

        $response->assertStatus(422); // Validation rejects role:in:customer,partner or 403
    }

    public function test_user_can_login_with_email_or_phone(): void
    {
        $user = User::factory()->customer()->create([
            'email' => 'loginuser@example.com',
            'phone' => '081234500000',
            'password' => bcrypt('secret123'),
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
