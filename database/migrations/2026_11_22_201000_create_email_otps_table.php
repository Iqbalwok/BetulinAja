<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Safely creates email_otps table for OTP-based email verification and password reset.
     */
    public function up(): void
    {
        if (!Schema::hasTable('email_otps')) {
            Schema::create('email_otps', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('email', 191)->index();
                $table->string('otp_hash', 64);
                $table->string('type', 30)->default('registration'); // registration, password_reset
                $table->dateTime('expires_at')->index();
                $table->unsignedTinyInteger('attempts')->default(0);
                $table->boolean('is_used')->default(false);
                $table->timestamps();

                $table->index(['email', 'type', 'is_used']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_otps');
    }
};
