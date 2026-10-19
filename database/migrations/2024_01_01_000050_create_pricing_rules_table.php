<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('service_categories')->nullOnDelete();
            $table->string('name');
            $table->decimal('base_price', 12, 2)->default(0);
            $table->decimal('travel_fee_per_km', 10, 2)->default(5000);
            $table->decimal('minimum_travel_fee', 10, 2)->default(0);
            $table->decimal('difficulty_fee_light', 10, 2)->default(0);
            $table->decimal('difficulty_fee_medium', 10, 2)->default(25000);
            $table->decimal('difficulty_fee_heavy', 10, 2)->default(60000);
            $table->decimal('platform_commission_rate', 5, 2)->default(15.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pricing_rules');
    }
};
