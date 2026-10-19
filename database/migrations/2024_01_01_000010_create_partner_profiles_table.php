<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('partner_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('photo')->nullable();
            $table->text('description')->nullable();
            $table->string('id_card_number', 30)->nullable();
            $table->string('id_card_photo')->nullable();
            $table->string('certificate_photo')->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->string('verification_note')->nullable();
            $table->enum('availability_status', ['online', 'offline', 'busy'])->default('offline');
            $table->decimal('current_latitude', 10, 8)->nullable();
            $table->decimal('current_longitude', 11, 8)->nullable();
            $table->decimal('average_rating', 3, 2)->default(0.00);
            $table->integer('total_reviews')->default(0);
            $table->integer('completed_jobs')->default(0);
            $table->integer('years_experience')->default(0);
            $table->decimal('balance', 15, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('partner_profiles');
    }
};
